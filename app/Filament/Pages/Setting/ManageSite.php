<?php

namespace App\Filament\Pages\Setting;

use App\Settings\SiteSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;

use function Filament\Support\is_app_url;

class ManageSite extends SettingsPage
{
    use HasPageShield;
    protected static string $settings = SiteSettings::class;

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $settings = app(static::getSettings());

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->description('General website configuration')
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Toggle::make('is_maintenance')
                                ->label('Maintenance Mode')
                                ->helperText('When enabled, your site will display a maintenance page to visitors')
                                ->required(),
                            Forms\Components\TextInput::make('name')
                                ->label('Site Name')
                                ->required()
                                ->maxLength(100),
                            Forms\Components\TextInput::make('tagline')
                                ->label('Site Tagline')
                                ->helperText('A short phrase describing your site')
                                ->maxLength(150),
                            Forms\Components\Textarea::make('description')
                                ->label('Site Description')
                                ->helperText('A detailed description of your website')
                                ->rows(3)
                                ->maxLength(500),
                        ])->columns(2),
                        Forms\Components\FileUpload::make('logo')
                            ->label('Site Logo')
                            ->image()
                            ->directory('sites')
                            ->visibility('public')
                            ->imagePreviewHeight('100')
                            ->maxSize(1024)
                            ->helperText('Recommended size: 200x50 pixels'),
                    ]),

                Forms\Components\Section::make('Company Information')
                    ->description('Contact details and location information')
                    ->icon('heroicon-o-building-office')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('company_name')
                                ->label('Company Name')
                                ->required()
                                ->maxLength(100),
                            Forms\Components\TextInput::make('company_email')
                                ->label('Company Email')
                                ->email()
                                ->required()
                                ->maxLength(100),
                            Forms\Components\TextInput::make('company_phone')
                                ->label('Company Phone')
                                ->tel()
                                ->maxLength(20),
                            Forms\Components\Textarea::make('company_address')
                                ->label('Company Address')
                                ->rows(2)
                                ->maxLength(200),
                        ])->columns(2),
                    ]),

                Forms\Components\Section::make('Regional Settings')
                    ->description('Language and time settings')
                    ->icon('heroicon-o-globe-alt')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Select::make('default_language')
                                ->label('Default Language')
                                ->options([
                                    'en' => 'English',
                                    'fr' => 'French',
                                    'es' => 'Spanish',
                                    'de' => 'German',
                                    'it' => 'Italian',
                                    'pt' => 'Portuguese',
                                    'ru' => 'Russian',
                                    'zh' => 'Chinese',
                                    'ja' => 'Japanese',
                                    'ar' => 'Arabic',
                                ])
                                ->searchable()
                                ->required(),
                            Forms\Components\Select::make('timezone')
                                ->label('Timezone')
                                ->options(function () {
                                    $timezones = [];
                                    foreach (timezone_identifiers_list() as $timezone) {
                                        $timezones[$timezone] = $timezone;
                                    }
                                    return $timezones;
                                })
                                ->searchable()
                                ->required(),
                        ])->columns(2),
                    ]),

                Forms\Components\Section::make('Legal Information')
                    ->description('Copyright and legal page URLs')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('copyright_text')
                                ->label('Copyright Text')
                                ->maxLength(200),
                            Forms\Components\TextInput::make('terms_url')
                                ->label('Terms & Conditions URL')
                                ->maxLength(100)
                                ->prefix(function (Forms\Get $get) {
                                    return url('/');
                                }),
                            Forms\Components\TextInput::make('privacy_url')
                                ->label('Privacy Policy URL')
                                ->maxLength(100)
                                ->prefix(function (Forms\Get $get) {
                                    return url('/');
                                }),
                            Forms\Components\TextInput::make('cookie_policy_url')
                                ->label('Cookie Policy URL')
                                ->maxLength(100)
                                ->prefix(function (Forms\Get $get) {
                                    return url('/');
                                }),
                        ])->columns(2),
                    ]),

                Forms\Components\Section::make('Error Messages')
                    ->description('Custom error messages for your website')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Textarea::make('custom_404_message')
                                ->label('404 Not Found Message')
                                ->rows(2)
                                ->maxLength(500),
                            Forms\Components\Textarea::make('custom_500_message')
                                ->label('500 Server Error Message')
                                ->rows(2)
                                ->maxLength(500),
                        ])->columns(2),
                    ]),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->mutateFormDataBeforeSave($this->form->getState());

            $settings = app(static::getSettings());

            $settings->fill($data);
            $settings->save();

            Notification::make()
                ->title('Settings saved successfully!')
                ->body('Your site general settings have been updated.')
                ->success()
                ->send();

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Error saving settings')
                ->body($th->getMessage())
                ->danger()
                ->send();

            throw $th;
        }
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.sites");
    }

    public static function getNavigationLabel(): string
    {
        return 'Settings';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Site Settings';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Site Settings';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Manage your website\'s general configuration';
    }
}
