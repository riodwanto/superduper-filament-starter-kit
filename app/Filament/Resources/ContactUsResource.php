<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactUsResource\Actions\ReplyAction;
use App\Filament\Resources\ContactUsResource\Pages;
use App\Models\ContactUs;
use Filament\Tables\Actions as TablesActions;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static ?string $slug = 'contact-us/inbox';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'fluentui-mail-inbox-28';

    protected static ?int $navigationSort = 0;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Contact Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('firstname'),
                        Infolists\Components\TextEntry::make('lastname'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('phone'),
                        Infolists\Components\TextEntry::make('company'),
                        Infolists\Components\TextEntry::make('employees'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Message')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label('Subject'),
                        Infolists\Components\TextEntry::make('message')
                            ->columnSpanFull(),
                    ]),

                // Show reply section if a reply has been sent
                Infolists\Components\Section::make('Your Reply')
                    ->schema([
                        Infolists\Components\TextEntry::make('reply_title')
                            ->label('Subject'),
                        Infolists\Components\TextEntry::make('reply_message')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->visible(
                        fn(ContactUs $record): bool =>
                        !empty($record->reply_message) && !empty($record->reply_title)
                    )
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('avatar')
                        ->getStateUsing(fn(
                            $record
                        ) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=FFFFFF&background=111827')
                        ->toggleable()
                        ->circular()
                        ->grow(false),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight('bold')
                            ->toggleable()
                            ->searchable()
                            ->limit(20)
                            ->sortable(),
                        Tables\Columns\TextColumn::make('email')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('phone')
                            ->searchable(),
                    ]),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('company')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('employees')
                            ->formatStateUsing(fn(string $state): string => $state . ' People')
                            ->searchable(),
                    ]),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('created_at')
                            ->sortable()
                            ->searchable()
                            ->toggleable()
                            ->dateTime()
                            ->label('Sent at'),
                        Tables\Columns\TextColumn::make('status')
                            ->formatStateUsing(fn(string $state): string => $state)
                            ->sortable()
                            ->searchable()
                            ->toggleable()
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'new' => 'danger',
                                'read' => 'success',
                                default => '',
                            }),
                    ]),

                ])
            ])
            ->recordClasses(fn(ContactUs $record) => match ($record->status) {
                'new' => 'border-s-2 border-danger-600 dark:border-danger-300',
                'read' => 'border-s-2 border-success-600 dark:border-success-300',
                default => '',
            })
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options([
                        'new' => 'NEW',
                        'read' => 'READ',
                    ]),
            ])
            ->bulkActions([
                TablesActions\DeleteBulkAction::make(),
                TablesActions\ForceDeleteBulkAction::make(),
                TablesActions\RestoreBulkAction::make(),
            ])
            ->actions([
                ReplyAction::make()
                    ->visible(
                        fn(ContactUs $record): bool =>
                        empty($record->reply_message) || empty($record->reply_title)
                    ),
                TablesActions\ActionGroup::make([
                    TablesActions\ViewAction::make('view'),
                    TablesActions\DeleteAction::make('delete'),
                    TablesActions\ForceDeleteAction::make(),
                    TablesActions\RestoreAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListContactUs::route('/'),
        ];
    }

    public static function getLabel(): string
    {
        return 'Inbox';
    }

    public static function getPluralLabel(): string
    {
        return 'Contact Us';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationLabel(): string
    {
        return 'Inbox';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }
}
