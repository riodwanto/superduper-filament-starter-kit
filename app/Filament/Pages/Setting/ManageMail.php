<?php

namespace App\Filament\Pages\Setting;

use App\Mail\TestMail;
use App\Settings\MailSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
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

        $settings = app(static::getSettings());

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        // dd($data);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Configuration')
                            ->label(fn () => __('page.mail_settings.sections.config.title'))
                            ->icon('fluentui-calendar-settings-32-o')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Select::make('driver')->label(fn () => __('page.mail_settings.fields.driver'))
                                            ->options([
                                                "smtp" => "SMTP (Recommended)",
                                                "mailgun" => "Mailgun",
                                                "ses" => "Amazon SES",
                                                "postmark" => "Postmark",
                                            ])
                                            ->native(false)
                                            ->required()
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('host')->label(fn () => __('page.mail_settings.fields.host'))
                                            ->required(),
                                        Forms\Components\TextInput::make('port')->label(fn () => __('page.mail_settings.fields.port')),
                                        Forms\Components\Select::make('encryption')->label(fn () => __('page.mail_settings.fields.encryption'))
                                            ->options([
                                                "ssl" => "SSL",
                                                "tls" => "TLS",
                                            ])
                                            ->native(false),
                                        Forms\Components\TextInput::make('timeout')->label(fn () => __('page.mail_settings.fields.timeout')),
                                        Forms\Components\TextInput::make('username')->label(fn () => __('page.mail_settings.fields.username')),
                                        Forms\Components\TextInput::make('password')->label(fn () => __('page.mail_settings.fields.password'))
                                            ->password()
                                            ->revealable(),
                                    ])
                                    ->columns(3),
                            ])
                    ])
                    ->columnSpan([
                        "md" => 2
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('From (Sender)')
                            ->label(fn () => __('page.mail_settings.section.sender.title'))
                            ->icon('fluentui-person-mail-48-o')
                            ->schema([
                                Forms\Components\TextInput::make('from_address')->label(fn () => __('page.mail_settings.fields.email'))
                                    ->required(),
                                Forms\Components\TextInput::make('from_name')->label(fn () => __('page.mail_settings.fields.name'))
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Mail to')
                            ->label(fn () => __('page.mail_settings.section.mail_to.title'))
                            ->schema([
                                Forms\Components\TextInput::make('mail_to')
                                    ->label(fn () => __('page.mail_settings.fields.mail_to'))
                                    ->hiddenLabel()
                                    ->placeholder(fn () => __('page.mail_settings.fields.placeholder.receiver_email')),
                                Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('Send Test Mail')
                                            ->label(fn () => __('page.mail_settings.actions.send_test_mail'))
                                            ->action('sendTestMail')
                                            ->color('warning')
                                            ->icon('fluentui-mail-alert-28-o')
                                    ])->fullWidth(),
                            ])
                    ])
                    ->columnSpan([
                        "md" => 1
                    ]),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $settings = app(static::getSettings());

            $settings->fill($data);
            $settings->save();

            $this->callHook('afterSave');

            $this->sendSuccessNotification('Mail Settings updated.');

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            throw $th;
            $this->sendErrorNotification('Failed to update settings. '.$th->getMessage());
        }
    }

    public function sendTestMail()
    {
        $settings = $this->form->getState();

        config([
            'mail.mailers.smtp.host' => $settings['host'],
            'mail.mailers.smtp.port' => $settings['port'],
            'mail.mailers.smtp.encryption' => $settings['encryption'],
            'mail.mailers.smtp.username' => $settings['username'],
            'mail.mailers.smtp.password' => $settings['password'],
            'mail.from.address' => $settings['from_address'],
            'mail.from.name' => $settings['from_name'],
        ]);

        try {
            $mailTo = $settings['mail_to'];
            $mailData = [
                'title' => 'This is a test email to verify SMTP settings',
                'body' => 'This is for testing email using smtp.'
            ];

            Mail::to($mailTo)->send(new TestMail($mailData));

            $this->sendSuccessNotification('Mail Sent to: '.$mailTo);
        } catch (\Exception $e) {
            $this->sendErrorNotification($e->getMessage());
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
                ->error()
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
