<?php

namespace App\Filament\Pages\Setting;

use App\Settings\SiteSocialSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;

use function Filament\Support\is_app_url;

class ManageSiteSocial extends SettingsPage
{
    use HasPageShield;
    protected static string $settings = SiteSocialSettings::class;

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-share';

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
                Forms\Components\Section::make('Social Media Profiles')
                    ->description('Links to your social media profiles')
                    ->icon('heroicon-o-link')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('facebook_url')
                                ->label('Facebook URL')
                                ->url()
                                ->prefix('https://')
                                ->helperText('e.g., facebook.com/yourpage'),
                            Forms\Components\TextInput::make('twitter_url')
                                ->label('Twitter/X URL')
                                ->url()
                                ->prefix('https://')
                                ->helperText('e.g., twitter.com/yourusername'),
                            Forms\Components\TextInput::make('instagram_url')
                                ->label('Instagram URL')
                                ->url()
                                ->prefix('https://')
                                ->helperText('e.g., instagram.com/yourusername'),
                            Forms\Components\TextInput::make('linkedin_url')
                                ->label('LinkedIn URL')
                                ->url()
                                ->prefix('https://')
                                ->helperText('e.g., linkedin.com/company/yourcompany'),
                            Forms\Components\TextInput::make('youtube_url')
                                ->label('YouTube URL')
                                ->url()
                                ->prefix('https://')
                                ->helperText('e.g., youtube.com/channel/your-channel'),
                            Forms\Components\TextInput::make('pinterest_url')
                                ->label('Pinterest URL')
                                ->url()
                                ->prefix('https://')
                                ->helperText('e.g., pinterest.com/yourusername'),
                            Forms\Components\TextInput::make('tiktok_url')
                                ->label('TikTok URL')
                                ->url()
                                ->prefix('https://')
                                ->helperText('e.g., tiktok.com/@yourusername'),
                        ])->columns(2),
                    ]),

                Forms\Components\Section::make('Social Sharing')
                    ->description('Configure social sharing options')
                    ->icon('heroicon-o-share')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Toggle::make('social_share_enabled')
                                ->label('Enable Social Sharing Buttons')
                                ->required(),
                            Forms\Components\CheckboxList::make('social_share_platforms')
                                ->label('Platforms to Include')
                                ->options([
                                    'facebook' => 'Facebook',
                                    'twitter' => 'Twitter/X',
                                    'linkedin' => 'LinkedIn',
                                    'pinterest' => 'Pinterest',
                                    'reddit' => 'Reddit',
                                    'whatsapp' => 'WhatsApp',
                                    'telegram' => 'Telegram',
                                    'email' => 'Email',
                                ])
                                ->columns(2)
                                ->required()
                                ->helperText('Select which platforms to include in your sharing buttons'),
                        ])->columns(2),

                        Forms\Components\FileUpload::make('social_share_default_image')
                            ->label('Default Share Image')
                            ->image()
                            ->directory('sites')
                            ->visibility('public')
                            ->imagePreviewHeight('100')
                            ->helperText('This image will be used when sharing pages that don\'t have a specific image set. Recommended size: 1200x630 pixels'),
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
                ->body('Your social media settings have been updated.')
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
        return 'Social Media';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Site Social Media Settings';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Site Social Media Settings';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Manage your social media profiles and sharing options';
    }
}
