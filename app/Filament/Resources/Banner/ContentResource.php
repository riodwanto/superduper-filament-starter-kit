<?php

namespace App\Filament\Resources\Banner;

use App\Filament\Resources\Banner\ContentResource\Pages;
use App\Models\Banner\Category;
use App\Models\Banner\Content;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $slug = 'banner/contents';

    protected static int $globalSearchResultsLimit = 10;

    protected static ?int $navigationSort = -2;
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static function getLastSortValue(): int
    {
        return Content::max('sort') ?? 0;
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
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                                                Forms\Components\TextInput::make('slug')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique(Category::class, 'slug', ignoreRecord: true)
                                                    ->helperText('URL-friendly version of the title - generated automatically')
                                                    ->suffixAction(
                                                        Forms\Components\Actions\Action::make('editSlug')
                                                            ->icon('heroicon-o-pencil-square')
                                                            ->modalHeading('Edit Slug')
                                                            ->modalDescription('Customize the URL slug for this Category. Use lowercase letters, numbers, and hyphens only.')
                                                            ->modalIcon('heroicon-o-link')
                                                            ->modalSubmitActionLabel('Update Slug')
                                                            ->form([
                                                                Forms\Components\TextInput::make('new_slug')
                                                                    ->hiddenLabel()
                                                                    ->required()
                                                                    ->maxLength(255)
                                                                    ->live(debounce: 500)
                                                                    ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                                                        $set('new_slug', Str::slug($state));
                                                                    })
                                                                    ->unique(Category::class, 'slug', ignoreRecord: true)
                                                                    ->helperText('The slug will be automatically formatted as you type.')
                                                            ])
                                                            ->action(function (array $data, Forms\Set $set) {
                                                                $set('slug', $data['new_slug']);

                                                                Notification::make()
                                                                    ->title('Slug updated')
                                                                    ->success()
                                                                    ->send();
                                                            })
                                                    ),
                                                Forms\Components\Toggle::make('is_active')
                                                    ->label('Active')
                                                    ->default(true),
                                            ])
                                            ->required(),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active')
                                            ->helperText('Control banner visibility')
                                            ->default(true),
                                        Forms\Components\TextInput::make('title')
                                            ->label('Title')
                                            ->maxLength(255)
                                            ->columnSpan(2),
                                        Forms\Components\MarkdownEditor::make('description')
                                            ->label('Description')
                                            ->helperText('Provide a description for the banner')
                                            ->maxLength(500)
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('locale')
                                            ->options([
                                                'en' => 'English',
                                                'id' => 'Indonesian',
                                                'zh' => 'Chinese',
                                                'ja' => 'Japanese',
                                                // Add more languages as needed
                                            ])
                                            ->default('en')
                                            ->required(),
                                    ])
                                    ->compact()
                                    ->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Banner Image')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Section::make('Image')
                                    ->description('Upload banner image here')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('banners')
                                            ->collection('banners')
                                            ->multiple(false)
                                            ->maxFiles(1)
                                            ->imagePreviewHeight('250')
                                            ->panelLayout('compact')
                                            ->imageResizeMode('cover')
                                            ->imageResizeTargetWidth('1200')
                                            ->imageResizeTargetHeight('800')
                                            ->acceptedFileTypes(['image/*'])
                                            ->helperText('Upload a banner image. Recommended size: 1200x800px')
                                            ->columnSpanFull(),
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
                                            ->helperText('Select the start date and time')
                                            ->nullable(),
                                        Forms\Components\DateTimePicker::make('end_date')
                                            ->label('End Date')
                                            ->helperText('Select the end date and time')
                                            ->nullable()
                                            ->after('start_date'),
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Publish Date')
                                            ->helperText('When should this banner be published?')
                                            ->nullable(),
                                    ])
                                    ->compact()
                                    ->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Link & Tracking')
                            ->icon('heroicon-o-link')
                            ->schema([
                                Forms\Components\Section::make('Click Settings')
                                    ->description('Configure link and tracking options')
                                    ->schema([
                                        Forms\Components\TextInput::make('click_url')
                                            ->label('Click URL')
                                            ->helperText('Enter the URL to navigate to when the banner is clicked')
                                            ->url()
                                            ->maxLength(255),
                                        Forms\Components\Select::make('click_url_target')
                                            ->label('Click URL Target')
                                            ->helperText('Select how the URL should be opened')
                                            ->options([
                                                '_blank' => 'New Tab',
                                                '_self' => 'Current Tab',
                                            ])
                                            ->default('_self')
                                            ->native(false),
                                    ])
                                    ->compact()
                                    ->columns(2),
                                Forms\Components\Section::make('Tracking')
                                    ->description('Banner tracking statistics')
                                    ->schema([
                                        Forms\Components\Placeholder::make('impression_count')
                                            ->label('Impressions')
                                            ->content(fn(Content $record): string => number_format($record->impression_count ?? 0)),
                                        Forms\Components\Placeholder::make('click_count')
                                            ->label('Clicks')
                                            ->content(fn(Content $record): string => number_format($record->click_count ?? 0)),
                                        Forms\Components\Placeholder::make('ctr')
                                            ->label('CTR (Click Through Rate)')
                                            ->content(function (Content $record): string {
                                                if (($record->impression_count ?? 0) > 0) {
                                                    $ctr = ($record->click_count / $record->impression_count) * 100;
                                                    return number_format($ctr, 2) . '%';
                                                }
                                                return '0.00%';
                                            }),
                                    ])
                                    ->compact()
                                    ->columns(3)
                                    ->visible(fn(?Content $record) => $record !== null),
                            ]),
                        Forms\Components\Tabs\Tab::make('Advanced Settings')
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
                                        Forms\Components\KeyValue::make('options')
                                            ->keyLabel('Option Name')
                                            ->valueLabel('Option Value')
                                            ->helperText('Custom JSON options for this banner')
                                            ->addable()
                                            ->reorderable()
                                            ->columnSpanFull(),
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
                SpatieMediaLibraryImageColumn::make('banners')
                    ->label('Image')
                    ->collection('banners')
                    ->conversion('thumbnail')
                    ->size(60)
                    ->circular(false)
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Model $record): string => Str::limit(strip_tags($record->description), 100))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('impression_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('click_count')
                    ->label('Clicks')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('locale')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('banner_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\SelectFilter::make('locale')
                    ->options([
                        'en' => 'English',
                        'id' => 'Indonesian',
                        'zh' => 'Chinese',
                        'ja' => 'Japanese',
                    ]),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->where(function ($q) use ($date) {
                                    $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
                                }),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->where(function ($q) use ($date) {
                                    $q->whereNull('start_date')->orWhere('start_date', '<=', $date);
                                }),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Active from ' . $data['from']->format('M j, Y');
                        }

                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Active until ' . $data['until']->format('M j, Y');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('View'),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('preview')
                        ->label('Preview Banner')
                        ->icon('heroicon-m-eye')
                        ->url(fn(Content $record) => $record->getImageUrl('large'))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('clone')
                        ->label('Clone Banner')
                        ->icon('heroicon-m-document-duplicate')
                        ->requiresConfirmation()
                        ->action(function (Content $record) {
                            // Get only the fillable attributes
                            $attributes = $record->only($record->getFillable());

                            // Create a new instance and fill it with the attributes
                            $clone = new Content($attributes);

                            // Set the new title
                            $clone->title = "{$record->title} (Clone)";

                            // Update the sort value
                            $clone->sort = static::getLastSortValue() + 1;

                            // Set the creator/updater
                            $clone->created_by = auth()->id();
                            $clone->updated_by = auth()->id();

                            // Reset counters
                            $clone->impression_count = 0;
                            $clone->click_count = 0;

                            // Save the clone
                            $clone->save();

                            // If the original has media, copy it to the clone
                            if ($record->hasMedia('banners')) {
                                $media = $record->getFirstMedia('banners');
                                $media->copy($clone, 'banners');
                            }

                            // Redirect to the edit page of the new clone
                            return redirect()->route('filament.admin.resources.banner.contents.edit', ['record' => $clone->id]);
                        }),
                    Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Set Active')
                        ->icon('heroicon-m-check-circle')
                        ->requiresConfirmation()
                        ->action(fn(\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Set Inactive')
                        ->icon('heroicon-m-x-circle')
                        ->requiresConfirmation()
                        ->action(fn(\Illuminate\Database\Eloquent\Collection $records) => $records->each->update(['is_active' => false])),
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
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
            'view' => Pages\ViewContent::route('/{record}'),
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
        return ['title', 'description', 'category.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Category' => $record->category->name,
            'Status' => $record->is_active ? 'Active' : 'Inactive',
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.banner");
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'gray';
    }
}
