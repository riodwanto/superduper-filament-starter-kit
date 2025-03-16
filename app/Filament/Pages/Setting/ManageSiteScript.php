<?php

namespace App\Filament\Pages\Setting;

use App\Settings\SiteScriptSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Riodwanto\FilamentAceEditor\AceEditor;

use function Filament\Support\is_app_url;

class ManageSiteScript extends SettingsPage
{
    use HasPageShield;
    protected static string $settings = SiteScriptSettings::class;

    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-code-bracket-square';

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
                Forms\Components\Tabs::make('Script Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Scripts')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Section::make('Header Scripts')
                                    ->description('Scripts to be placed in the <head> section')
                                    ->schema([
                                        AceEditor::make('header_scripts')
                                            ->label('Header Scripts')
                                            ->mode('html')
                                            ->height('200px')
                                            ->helperText('These scripts will be added to the <head> section of your site'),
                                    ]),
                                Forms\Components\Section::make('Body Scripts')
                                    ->description('Scripts to be placed in the <body> section')
                                    ->schema([
                                        AceEditor::make('body_start_scripts')
                                            ->label('Body Start Scripts')
                                            ->mode('html')
                                            ->height('150px')
                                            ->helperText('These scripts will be added right after the opening <body> tag'),

                                        AceEditor::make('body_end_scripts')
                                            ->label('Body End Scripts')
                                            ->mode('html')
                                            ->height('150px')
                                            ->helperText('These scripts will be added right before the closing </body> tag'),
                                    ]),
                                Forms\Components\Section::make('Footer Scripts')
                                    ->description('Scripts to be placed in the footer')
                                    ->schema([
                                        AceEditor::make('footer_scripts')
                                            ->label('Footer Scripts')
                                            ->mode('html')
                                            ->height('150px')
                                            ->helperText('These scripts will be added to the footer section of your site'),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Cookie Consent')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Section::make('Cookie Consent Banner')
                                    ->description('Configure the cookie consent banner')
                                    ->schema([
                                        Forms\Components\Grid::make()->schema([
                                            Forms\Components\Toggle::make('cookie_consent_enabled')
                                                ->label('Enable Cookie Consent Banner')
                                                ->required(),
                                            Forms\Components\TextInput::make('cookie_consent_policy_url')
                                                ->label('Cookie Policy URL')
                                                ->prefix(function (Forms\Get $get) {
                                                    return url('/');
                                                })
                                                ->helperText('The URL to your cookie policy page'),
                                        ])->columns(2),
                                        Forms\Components\Textarea::make('cookie_consent_text')
                                            ->label('Consent Text')
                                            ->rows(2)
                                            ->maxLength(300)
                                            ->helperText('The text to display in the cookie consent banner'),
                                        Forms\Components\TextInput::make('cookie_consent_button_text')
                                            ->label('Consent Button Text')
                                            ->maxLength(30)
                                            ->helperText('The text for the accept button'),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Custom Code')
                            ->icon('heroicon-o-pencil-square')
                            ->schema([
                                Forms\Components\Section::make('Custom CSS and JavaScript')
                                    ->description('Add custom styles and scripts')
                                    ->schema([
                                        AceEditor::make('custom_css')
                                            ->label('Custom CSS')
                                            ->mode('css')
                                            ->height('300px')
                                            ->helperText('Custom CSS styles will be added to the head of your site'),

                                        AceEditor::make('custom_js')
                                            ->label('Custom JavaScript')
                                            ->mode('javascript')
                                            ->height('300px')
                                            ->helperText('Custom JavaScript will be added before the closing body tag'),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ])
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
                ->body('Your script settings have been updated.')
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
        return 'Scripts & Analytics';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Site Scripts & Analytics Settings';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Site Scripts & Analytics Settings';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Manage your website\'s scripts, analytics, and custom code';
    }
}
