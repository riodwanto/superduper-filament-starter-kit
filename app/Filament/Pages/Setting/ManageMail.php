<?php

namespace App\Filament\Pages\Setting;

use App\Mail\TestMail;
use App\Settings\MailSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail;

use function Filament\Support\is_app_url;

class ManageMail extends SettingsPage
{
    use HasPageShield;

    protected static string $settings = MailSettings::class;

    protected static ?int $navigationSort = 99;
    protected static ?string $navigationIcon = 'fluentui-mail-settings-20';

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
        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill(app(static::getSettings())->toArray());

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Mail Settings')
                    ->tabs([
                        Tabs\Tab::make('General Configuration')
                            ->icon('fluentui-calendar-settings-32-o')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Section::make('SMTP Configuration')
                                            ->description('Configure your email server connection details')
                                            ->icon('fluentui-server-20-o')
                                            ->schema([
                                                Forms\Components\Select::make('driver')
                                                    ->label(fn () => __('page.mail_settings.fields.driver'))
                                                    ->options([
                                                        "smtp" => "SMTP (Standard Email Server) - Recommended",
                                                        "mailgun" => "Mailgun API Service - Untested",
                                                        "ses" => "Amazon SES (AWS Email Service) - Untested",
                                                        "postmark" => "Postmark API Service - Untested",
                                                    ])
                                                    ->helperText('Select your preferred mail delivery method. Alternative providers require API credentials.')
                                                    ->native(false)
                                                    ->live()
                                                    ->required()
                                                    ->columnSpan(2),
                                                Forms\Components\TextInput::make('host')
                                                    ->label(fn () => __('page.mail_settings.fields.host'))
                                                    ->required()
                                                    ->visible(fn (callable $get) => $get('driver') === 'smtp'),
                                                Forms\Components\TextInput::make('port')
                                                    ->label(fn () => __('page.mail_settings.fields.port'))
                                                    ->numeric()
                                                    ->visible(fn (callable $get) => $get('driver') === 'smtp'),
                                                Forms\Components\Select::make('encryption')
                                                    ->label(fn () => __('page.mail_settings.fields.encryption'))
                                                    ->options([
                                                        "ssl" => "SSL",
                                                        "tls" => "TLS",
                                                    ])
                                                    ->native(false)
                                                    ->visible(fn (callable $get) => $get('driver') === 'smtp'),
                                                Forms\Components\TextInput::make('timeout')
                                                    ->label(fn () => __('page.mail_settings.fields.timeout'))
                                                    ->numeric()
                                                    ->visible(fn (callable $get) => $get('driver') === 'smtp'),
                                                Forms\Components\TextInput::make('username')
                                                    ->label(fn () => __('page.mail_settings.fields.username'))
                                                    ->visible(fn (callable $get) => $get('driver') === 'smtp'),
                                                Forms\Components\TextInput::make('password')
                                                    ->label(fn () => __('page.mail_settings.fields.password'))
                                                    ->password()
                                                    ->revealable()
                                                    ->visible(fn (callable $get) => $get('driver') === 'smtp'),
                                                Forms\Components\TextInput::make('local_domain')
                                                    ->label('Local Domain')
                                                    ->helperText('Optional domain name to use in SMTP HELO command')
                                                    ->visible(fn (callable $get) => $get('driver') === 'smtp'),
                                            ])
                                            ->columns(3)
                                            ->columnSpan(2),

                                        Forms\Components\Section::make('Alternative Providers')
                                            ->description('Configure settings for alternative mail providers')
                                            ->icon('fluentui-plug-connected-20-o')
                                            ->schema([
                                                Forms\Components\Placeholder::make('provider_instructions')
                                                    ->content('Please select a mail driver to configure provider-specific settings.')
                                                    ->visible(fn (callable $get) => $get('driver') === 'smtp' || $get('driver') === null),

                                                // Mailgun
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('providers.mailgun.domain')
                                                            ->label('Mailgun Domain')
                                                            ->required(fn (callable $get) => $get('driver') === 'mailgun')
                                                            ->columnSpan(2),
                                                        Forms\Components\TextInput::make('providers.mailgun.secret')
                                                            ->label('Mailgun Secret')
                                                            ->password()
                                                            ->revealable()
                                                            ->required(fn (callable $get) => $get('driver') === 'mailgun')
                                                            ->columnSpan(2),
                                                        Forms\Components\TextInput::make('providers.mailgun.endpoint')
                                                            ->label('Mailgun Endpoint')
                                                            ->default('api.mailgun.net')
                                                            ->required(fn (callable $get) => $get('driver') === 'mailgun')
                                                            ->columnSpan(2),
                                                    ])
                                                    ->visible(fn (callable $get) => $get('driver') === 'mailgun')
                                                    ->columns(2),

                                                // Postmark
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('providers.postmark.token')
                                                            ->label('Postmark Token')
                                                            ->password()
                                                            ->revealable()
                                                            ->required(fn (callable $get) => $get('driver') === 'postmark'),
                                                    ])
                                                    ->visible(fn (callable $get) => $get('driver') === 'postmark'),

                                                // Amazon SES
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('providers.ses.key')
                                                            ->label('AWS Access Key')
                                                            ->required(fn (callable $get) => $get('driver') === 'ses'),
                                                        Forms\Components\TextInput::make('providers.ses.secret')
                                                            ->label('AWS Secret Key')
                                                            ->password()
                                                            ->revealable()
                                                            ->required(fn (callable $get) => $get('driver') === 'ses'),
                                                        Forms\Components\Select::make('providers.ses.region')
                                                            ->label('AWS Region')
                                                            ->options([
                                                                'us-east-1' => 'US East (N. Virginia)',
                                                                'us-east-2' => 'US East (Ohio)',
                                                                'us-west-1' => 'US West (N. California)',
                                                                'us-west-2' => 'US West (Oregon)',
                                                                'ap-south-1' => 'Asia Pacific (Mumbai)',
                                                                'ap-northeast-2' => 'Asia Pacific (Seoul)',
                                                                'ap-southeast-1' => 'Asia Pacific (Singapore)',
                                                                'ap-southeast-2' => 'Asia Pacific (Sydney)',
                                                                'ap-northeast-1' => 'Asia Pacific (Tokyo)',
                                                                'ca-central-1' => 'Canada (Central)',
                                                                'eu-central-1' => 'EU (Frankfurt)',
                                                                'eu-west-1' => 'EU (Ireland)',
                                                                'eu-west-2' => 'EU (London)',
                                                                'sa-east-1' => 'South America (São Paulo)',
                                                            ])
                                                            ->default('us-east-1')
                                                            ->required(fn (callable $get) => $get('driver') === 'ses'),
                                                    ])
                                                    ->visible(fn (callable $get) => $get('driver') === 'ses')
                                                    ->columns(3),
                                            ])
                                            ->collapsible(),
                                    ])
                                    ->columnSpan(2),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Section::make('Email Identity')
                                            ->description('Set the sender details for all outgoing emails')
                                            ->icon('fluentui-person-mail-48-o')
                                            ->schema([
                                                Forms\Components\TextInput::make('from_address')
                                                    ->label('From Email Address')
                                                    ->email()
                                                    ->required(),
                                                Forms\Components\TextInput::make('from_name')
                                                    ->label('From Name')
                                                    ->required(),
                                                Forms\Components\TextInput::make('reply_to_address')
                                                    ->label('Reply-To Email Address')
                                                    ->email()
                                                    ->nullable(),
                                                Forms\Components\TextInput::make('reply_to_name')
                                                    ->label('Reply-To Name')
                                                    ->nullable(),
                                            ]),

                                        Forms\Components\Section::make('Test Email')
                                            ->description('Send a test email to verify your configuration')
                                            ->schema([
                                                Forms\Components\TextInput::make('test_to_address')
                                                    ->label('Send Test To')
                                                    ->placeholder('recipient@example.com')
                                                    ->email()
                                                    ->required(),
                                                Forms\Components\Toggle::make('include_sample_attachment')
                                                    ->label('Include Sample Attachment')
                                                    ->helperText('Adds a text file attachment to test attachment handling')
                                                    ->default(false),
                                                Forms\Components\Actions::make([
                                                    Forms\Components\Actions\Action::make('sendTestMail')
                                                        ->label('Send Test Email')
                                                        ->action('sendTestMail')
                                                        ->color('warning')
                                                        ->icon('fluentui-mail-alert-28-o')
                                                ])
                                                ->fullWidth(),
                                            ]),
                                    ])
                                    ->columnSpan(1),
                            ])
                            ->columns(3),

                        Tabs\Tab::make('Advanced Settings')
                            ->icon('fluentui-settings-32-o')
                            ->schema([
                                Forms\Components\Section::make('Queue Settings')
                                    ->description('Configure email queueing for better performance')
                                    ->schema([
                                        Forms\Components\Toggle::make('queue_emails')
                                            ->label('Queue Emails')
                                            ->helperText('Process emails in the background for better performance')
                                            ->default(true),
                                        Forms\Components\TextInput::make('queue_name')
                                            ->label('Queue Name')
                                            ->default('emails')
                                            ->required()
                                            ->visible(fn (callable $get) => $get('queue_emails')),
                                        Forms\Components\Select::make('queue_connection')
                                            ->label('Queue Connection')
                                            ->options([
                                                'sync' => 'Synchronous (No Queue)',
                                                'database' => 'Database',
                                                'redis' => 'Redis',
                                                'sqs' => 'Amazon SQS',
                                            ])
                                            ->default('database')
                                            ->required()
                                            ->visible(fn (callable $get) => $get('queue_emails')),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Rate Limiting')
                                    ->description('Prevent abuse by limiting email sending rates')
                                    ->schema([
                                        Forms\Components\Toggle::make('rate_limiting.enabled')
                                            ->label('Enable Rate Limiting')
                                            ->default(true),
                                        Forms\Components\TextInput::make('rate_limiting.attempts')
                                            ->label('Max Attempts')
                                            ->numeric()
                                            ->default(5)
                                            ->visible(fn (callable $get) => $get('rate_limiting.enabled')),
                                        Forms\Components\TextInput::make('rate_limiting.per_minutes')
                                            ->label('Time Window (minutes)')
                                            ->numeric()
                                            ->default(1)
                                            ->visible(fn (callable $get) => $get('rate_limiting.enabled')),
                                    ])
                                    ->columns(3),

                                Forms\Components\Section::make('Notification Settings')
                                    ->description('Configure which types of notifications to send')
                                    ->schema([
                                        Forms\Components\Toggle::make('notifications_enabled')
                                            ->label('Enable All Notifications')
                                            ->default(true),
                                        Forms\Components\Toggle::make('notification_types.account')
                                            ->label('Account Notifications')
                                            ->helperText('Welcome emails, password resets, and account alerts')
                                            ->default(true)
                                            ->visible(fn (callable $get) => $get('notifications_enabled')),
                                        Forms\Components\Toggle::make('notification_types.system')
                                            ->label('System Notifications')
                                            ->helperText('System alerts, updates, and critical information')
                                            ->default(true)
                                            ->visible(fn (callable $get) => $get('notifications_enabled')),
                                        Forms\Components\Toggle::make('notification_types.marketing')
                                            ->label('Marketing Emails')
                                            ->helperText('Promotional content and newsletter updates')
                                            ->default(false)
                                            ->visible(fn (callable $get) => $get('notifications_enabled')),
                                        Forms\Components\Toggle::make('notification_types.blog')
                                            ->label('Blog Updates')
                                            ->helperText('New content and article notifications')
                                            ->default(false)
                                            ->visible(fn (callable $get) => $get('notifications_enabled')),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Debug Options')
                                    ->description('Advanced settings for troubleshooting')
                                    ->schema([
                                        Forms\Components\Toggle::make('test_mode')
                                            ->label('Enable Test Mode')
                                            ->helperText('All emails will be sent to the test address instead of actual recipients')
                                            ->default(false),
                                        Forms\Components\Select::make('log_channel')
                                            ->label('Log Channel')
                                            ->options([
                                                'stack' => 'Default Stack',
                                                'single' => 'Single File',
                                                'daily' => 'Daily Files',
                                                'slack' => 'Slack',
                                                'null' => 'Null (No Logging)',
                                            ])
                                            ->default('stack'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Email Template')
                            ->icon('fluentui-design-ideas-24')
                            ->schema([
                                Forms\Components\Section::make('Email Appearance')
                                    ->description('Customize the look and feel of your emails')
                                    ->schema([
                                        Forms\Components\Select::make('template_theme')
                                            ->label('Email Template Theme')
                                            ->options([
                                                'default' => 'Default Theme',
                                                'minimal' => 'Minimal',
                                                'corporate' => 'Corporate',
                                                'modern' => 'Modern',
                                                'dark' => 'Dark Mode',
                                            ])
                                            ->default('default')
                                            ->columnSpan(2),
                                        Forms\Components\ColorPicker::make('primary_color')
                                            ->label('Primary Color')
                                            ->default('#2D2B8D'),
                                        Forms\Components\ColorPicker::make('secondary_color')
                                            ->label('Secondary Color')
                                            ->default('#FFC903'),
                                        FileUpload::make('logo_path')
                                            ->label('Email Logo')
                                            ->directory('sites')
                                            ->image()
                                            ->nullable()
                                            ->imageResizeMode('cover')
                                            ->imageCropAspectRatio('4:1')
                                            ->imageResizeTargetWidth('300')
                                            ->imageResizeTargetHeight('75')
                                            ->columnSpan(2),
                                        Forms\Components\Textarea::make('footer_text')
                                            ->label('Email Footer Text')
                                            ->default('© ' . date('Y') . ' SuperDuper Starter. All rights reserved.')
                                            ->rows(2)
                                            ->columnSpan(2),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Preview')
                                    ->description('See how your emails will appear to recipients')
                                    ->schema([
                                        Forms\Components\View::make('filament.components.email-template-preview')
                                            ->extraAttributes(function (callable $get) {
                                                return [
                                                    'primary-color' => $get('primary_color') ?? '#2D2B8D',
                                                    'secondary-color' => $get('secondary_color') ?? '#FFC903',
                                                    'logo-path' => $get('logo_path'),
                                                    'theme-name' => $get('template_theme') ?? 'default',
                                                    'footer-text' => $get('footer_text') ?? ('© ' . date('Y') . ' SuperDuper Starter. All rights reserved.'),
                                                ];
                                            })
                                            ->columnSpan('full'),
                                    ]),
                            ]),
                    ])
                    ->columnSpan('full'),
            ])
            ->statePath('data');
    }

    public function save(MailSettings $settings = null): void
    {
        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            // Prepare and sanitize the data before saving
            $data = $this->mutateFormDataBeforeSave($data);

            $this->prepareNullableFields($data);

            $this->callHook('beforeSave');

            $settings->fill($data);
            $settings->save();

            $this->callHook('afterSave');

            $this->sendSuccessNotification('Mail settings updated successfully.');

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            $this->sendErrorNotification('Failed to update settings: '.$th->getMessage());
            throw $th;
        }
    }

    /**
     * Prepare nullable fields with proper defaults
     */
    protected function prepareNullableFields(array &$data): void
    {
        $stringDefaults = [
            'logo_path' => 'sites/email-logo.png',
            'reply_to_address' => $data['from_address'] ?? 'noreply@superduperstarter.com',
            'reply_to_name' => $data['from_name'] ?? 'SuperDuper Filament Starter',
            'footer_text' => '© ' . date('Y') . ' SuperDuper Starter. All rights reserved.',
            'template_theme' => 'default',
            'primary_color' => '#2D2B8D',
            'secondary_color' => '#FFC903',
            'log_channel' => 'stack',
        ];

        foreach ($stringDefaults as $key => $default) {
            if (!isset($data[$key]) || $data[$key] === null || $data[$key] === '') {
                $data[$key] = $default;
            }
        }

        if (!isset($data['providers']) || !is_array($data['providers'])) {
            $data['providers'] = [
                'mailgun' => ['domain' => null, 'secret' => null, 'endpoint' => 'api.mailgun.net'],
                'postmark' => ['token' => null],
                'ses' => ['key' => null, 'secret' => null, 'region' => 'us-east-1'],
            ];
        }

        if (!isset($data['rate_limiting']) || !is_array($data['rate_limiting'])) {
            $data['rate_limiting'] = [
                'enabled' => true,
                'attempts' => 5,
                'per_minutes' => 1,
            ];
        }

        if (!isset($data['notification_types']) || !is_array($data['notification_types'])) {
            $data['notification_types'] = [
                'account' => true,
                'system' => true,
                'marketing' => false,
                'blog' => false,
            ];
        }

        $booleanDefaults = [
            'queue_emails' => true,
            'notifications_enabled' => true,
            'test_mode' => false,
        ];

        foreach ($booleanDefaults as $key => $default) {
            if (!isset($data[$key])) {
                $data[$key] = $default;
            }
        }

        $integerFields = ['port', 'timeout', 'rate_limiting.attempts', 'rate_limiting.per_minutes'];
        foreach ($integerFields as $field) {
            if (str_contains($field, '.')) {
                [$parent, $child] = explode('.', $field);
                if (isset($data[$parent][$child]) && $data[$parent][$child] !== null) {
                    $data[$parent][$child] = (int) $data[$parent][$child];
                }
            } else {
                if (isset($data[$field]) && $data[$field] !== null) {
                    $data[$field] = (int) $data[$field];
                }
            }
        }
    }

    public function sendTestMail(MailSettings $settings = null)
    {
        $data = $this->form->getState();

        // Prepare data for configuration
        $this->prepareNullableFields($data);

        $settings->loadMailSettingsToConfig($data);

        try {
            $mailTo = $data['test_to_address'] ?? null;

            if (!$mailTo) {
                $this->sendErrorNotification('Please provide a recipient email address');
                return;
            }

            $mailData = [
                'title' => 'Email Testing from SuperDuper Filament Starter',
                'body' => 'This is a test email to verify your email configuration settings are working correctly.',
                'theme' => [
                    'logo' => $data['logo_path'] ?? 'sites/logo.png',
                    'primaryColor' => $data['primary_color'] ?? '#2D2B8D',
                    'secondaryColor' => $data['secondary_color'] ?? '#FFC903',
                    'footer' => $data['footer_text'] ?? ('© ' . date('Y') . ' SuperDuper Starter. All rights reserved.'),
                    'theme' => $data['template_theme'] ?? 'default',
                ],
                'include_sample_attachment' => $data['include_sample_attachment'] ?? false,
            ];

            Mail::to($mailTo)->send(new TestMail($mailData));

            $this->sendSuccessNotification('Test email sent successfully to ' . $mailTo .
                ($mailData['include_sample_attachment'] ? ' with attachment' : ''));
        } catch (\Exception $e) {
            $this->sendErrorNotification('Failed to send test email: ' . $e->getMessage());
        }
    }


    public function sendSuccessNotification($title)
    {
        Notification::make()
                ->title($title)
                ->success()
                ->send();
    }

    public function sendErrorNotification($title)
    {
        Notification::make()
                ->title($title)
                ->danger()
                ->send();
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("page.mail_settings.navigationLabel");
    }

    public function getTitle(): string|Htmlable
    {
        return __("page.mail_settings.title");
    }

    public function getHeading(): string|Htmlable
    {
        return __("page.mail_settings.heading");
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __("page.mail_settings.subheading");
    }
}

