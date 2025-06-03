<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Tables\Actions\ImpersonateTableAction;
use App\Models\User;
use App\Settings\MailSettings;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static int $globalSearchResultsLimit = 20;

    protected static ?int $navigationSort = -1;
    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->hiddenLabel()
                            ->avatar()
                            ->collection('avatars')
                            ->alignCenter()
                            ->columnSpanFull()
                            ->image()
                            ->imagePreviewHeight('80')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('256')
                            ->imageResizeTargetHeight('256')
                            ->maxSize(1024)
                            ->maxFiles(1)
                            ->helperText(fn () => new HtmlString('<div class="text-center text-gray-300">'.__('resource.user.avatar_helper').'</div>')),

                        Forms\Components\Actions::make([
                            Action::make('resend_verification')
                                ->label(__('resource.user.actions.resend_verification'))
                                ->color('info')
                                ->action(fn(MailSettings $settings, Model $record) => static::doResendEmailVerification($settings, $record))
                                ->hidden(fn($record) => $record?->email_verified_at !== null),
                        ])
                            ->hiddenOn('create')
                            ->fullWidth(),

                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                    ->dehydrated(fn(?string $state): bool => filled($state))
                                    ->revealable()
                                    ->required(),
                                Forms\Components\TextInput::make('passwordConfirmation')
                                    ->password()
                                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                    ->dehydrated(fn(?string $state): bool => filled($state))
                                    ->revealable()
                                    ->same('password')
                                    ->required(),
                            ])
                            ->compact()
                            ->hidden(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\Placeholder::make('user_info')
                            ->hiddenLabel()
                            ->content(function (?User $record): HtmlString {
                                if (!$record) {
                                    return new HtmlString('<span class="text-sm text-gray-500">Save to see user details! 😊</span>');
                                }
                                $name = trim(($record->firstname ?? '') . ' ' . ($record->lastname ?? ''));
                                $joined = $record->created_at?->format('M j, Y \a\t g:i A') ?? 'just now';
                                $updated = $record->updated_at?->diffForHumans() ?? 'never';
                                $updatedExact = $record->updated_at?->format('M j, Y \a\t g:i A') ?? '';
                                if ($record->email_verified_at) {
                                    $verified = $record->email_verified_at->format('M j, Y \a\t g:i A');
                                    $sentence = "<span class='font-semibold'>$name</span> joined $joined and has been <span class='font-semibold' title='Verified on $verified'>verified</span> since then. Last updated <span class='font-semibold' title='Updated on $updatedExact'>$updated</span>.";
                                } else {
                                    $sentence = "<span class='font-semibold'>$name</span> joined $joined and hasn't verified their email yet. Last updated <span class='font-semibold' title='Updated on $updatedExact'>$updated</span>.";
                                }
                                return new HtmlString($sentence);
                            })
                            ->hidden(fn(string $operation): bool => $operation === 'create'),
                    ])
                    ->columnSpan(1),

                Forms\Components\Tabs::make()
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Details')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\TextInput::make('username')
                                    ->required()
                                    ->maxLength(255)
                                    ->live()
                                    ->rules(function ($record) {
                                        $userId = $record?->id;
                                        return $userId
                                            ? ['unique:users,username,' . $userId]
                                            : ['unique:users,username'];
                                    }),

                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->rules(function ($record) {
                                        $userId = $record?->id;
                                        return $userId
                                            ? ['unique:users,email,' . $userId]
                                            : ['unique:users,email'];
                                    })
                                    ->disabled(fn(string $operation) => $operation === 'edit')
                                    ->helperText(fn () => new HtmlString('<div class="text-gray-300">'.__('resource.user.email_edit_warning').'</div>')),

                                Forms\Components\TextInput::make('firstname')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('lastname')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('Roles')
                            ->icon('fluentui-shield-task-48')
                            ->schema([
                                Select::make('roles')
                                    ->hiddenLabel()
                                    ->relationship('roles', 'name')
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::headline($record->name))
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->optionsLimit(5)
                                    ->columnSpanFull()
                                    ->helperText(__('resource.user.roles_tooltip')),
                            ])
                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'lg' => 2
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')->label('Avatar')
                    ->collection('avatars')
                    ->wrap()
                    ->circular()
                    ->extraAttributes(['alt' => __('resource.user.avatar_alt')]),
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->getStateUsing(fn(Model $record) => $record->firstname . ' ' . $record->lastname)
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')->label('Username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label(__('resource.user.roles'))
                    ->formatStateUsing(fn($state): string => $state ? Str::headline($state) : __('resource.user.no_roles'))
                    ->colors(['info'])
                    ->badge()
                    ->tooltip(__('resource.user.roles_tooltip')),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('fluentui-checkmark-starburst-24')
                    ->falseIcon('fluentui-error-circle-24')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn(Model $record) => $record->email_verified_at ? __('resource.user.status.verified_tooltip') : __('resource.user.status.unverified_tooltip')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    ImpersonateTableAction::make()->tooltip('Impersonate this user'),
                    Tables\Actions\EditAction::make()->tooltip('Edit user'),
                    Tables\Actions\DeleteAction::make()->tooltip('Delete user'),
                ])->label('Actions')->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->email;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'firstname', 'lastname'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->firstname . ' ' . $record->lastname,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.access");
    }

    public static function doResendEmailVerification($settings = null, $user): void
    {
        if (!method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        if ($settings->isMailSettingsConfigured()) {
            $notification = new VerifyEmail();
            $notification->url = Filament::getVerifyEmailUrl($user);

            $settings->loadMailSettingsToConfig();

            $user->notify($notification);


            Notification::make()
                ->title(__('resource.user.notifications.verify_sent.title'))
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title(__('resource.user.notifications.verify_warning.title'))
                ->body(__('resource.user.notifications.verify_warning.description'))
                ->warning()
                ->send();
        }
    }
}
