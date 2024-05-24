<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\CommonMarkConverter;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static int $globalSearchResultsLimit = 10;

    protected static ?int $navigationSort = -1;
    protected static ?string $navigationIcon = 'fluentui-image-shadow-24';

    protected static function getLastSortValue(): int
    {
        return Banner::max('sort') ?? 0;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Banner Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Section::make('Main Details')
                                    ->description('Fill out the main details of the banner')
                                    ->icon('heroicon-o-clipboard')
                                    ->schema([
                                        Forms\Components\Select::make('banner_category_id')
                                            ->label('Category')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->required(),
                                        Forms\Components\Select::make('is_visible')
                                            ->label('Is Visible')
                                            ->default(1)
                                            ->options([
                                                0 => "No",
                                                1 => "Yes",
                                            ])
                                            ->native(false)
                                            ->required(),
                                        Forms\Components\TextInput::make('title')
                                            ->label('Title')
                                            ->maxLength(255)
                                            ->columnSpan(2),
                                        Forms\Components\MarkdownEditor::make('description')
                                            ->label('Description')
                                            ->helperText('Provide a description for the banner')
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                    ])
                                    ->compact()
                                    ->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Images')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Section::make('Image')
                                    ->description('Upload banner images here')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('media')
                                            ->hiddenLabel()
                                            ->helperText('Select and upload images for the banner')
                                            ->collection('banners')
                                            ->multiple()
                                            ->reorderable()
                                            ->required(),
                                    ])
                                    ->compact(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Scheduling')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                Forms\Components\Section::make('Schedule')
                                    ->description('Set the scheduling details for the banner')
                                    ->schema([
                                        Forms\Components\DateTimePicker::make('start_date')
                                            ->label('Start Date')
                                            ->helperText('Select the start date and time'),
                                        Forms\Components\DateTimePicker::make('end_date')
                                            ->label('End Date')
                                            ->helperText('Select the end date and time'),
                                    ])
                                    ->compact()
                                    ->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Additional Settings')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Forms\Components\Section::make('Settings')
                                    ->description('Additional settings for the banner')
                                    ->schema([
                                        Forms\Components\TextInput::make('sort')
                                            ->label('Sort Order')
                                            ->helperText('Set the sort order of the banner')
                                            ->required()
                                            ->numeric()
                                            ->default(static::getLastSortValue() + 1),
                                        Forms\Components\TextInput::make('click_url')
                                            ->label('Click URL')
                                            ->helperText('Enter the URL to navigate to when the banner is clicked')
                                            ->default('#')
                                            ->maxLength(255),
                                        Forms\Components\Select::make('click_url_target')
                                            ->label('Click URL Target')
                                            ->helperText('Select how the URL should be opened')
                                            ->options([
                                                '_blank' => 'New Tab',
                                                '_self' => 'Current Tab',
                                                '_parent' => 'Parent Frame',
                                                '_top' => 'Full Body of the Window'
                                            ])
                                            ->native(false),
                                    ])
                                    ->compact(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')->label('Images')
                    ->collection('banners')
                    ->wrap(),
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Model $record): string => strip_tags((new CommonMarkConverter())->convert($record->description)->getContent()))
                    ->lineClamp(2)
                    ->wrap()
                    ->searchable()
                    ->extraAttributes(['class' => '!w-96']),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->alignCenter()
                    ->lineClamp(2),
                Tables\Columns\IconColumn::make('is_visible')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('click_url')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->trueLabel('Visible')
                    ->falseLabel('Hidden')
                    ->nullable(),
                Tables\Filters\Filter::make('start_date')
                    ->form([
                        Forms\Components\DatePicker::make('start_date'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['start_date'] ?? null, fn($query, $date) => $query->whereDate('start_date', '>=', $date));
                    }),
                Tables\Filters\Filter::make('end_date')
                    ->form([
                        Forms\Components\DatePicker::make('end_date'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['end_date'] ?? null, fn($query, $date) => $query->whereDate('end_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort', 'asc')
            ->reorderable('sort');
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['category']);
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->title;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'category.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Category' => $record->category->name,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.banner");
    }
}
