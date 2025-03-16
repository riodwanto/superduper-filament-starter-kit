<?php

namespace App\Filament\Pages\Setting;

use App\Services\FileService;
use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Riodwanto\FilamentAceEditor\AceEditor;

use function Filament\Support\is_app_url;

class ManageGeneral extends SettingsPage
{
    use HasPageShield;
    protected static string $settings = GeneralSettings::class;

    protected static ?int $navigationSort = 99;
    protected static ?string $navigationIcon = 'fluentui-settings-20';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public string $themePath = '';

    public string $twConfigPath = '';

    public function mount(): void
    {
        $this->themePath = resource_path('css/filament/admin/theme.css');
        $this->twConfigPath = resource_path('css/filament/admin/tailwind.config.js');

        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $settings = app(static::getSettings());

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        $fileService = new FileService;

        $data['theme-editor'] = $fileService->readfile($this->themePath);

        $data['tw-config-editor'] = $fileService->readfile($this->twConfigPath);

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site')
                    ->label(fn() => __('page.general_settings.sections.site'))
                    ->description(fn() => __('page.general_settings.sections.site.description'))
                    ->icon('fluentui-web-asset-24-o')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('brand_name')
                                ->label(fn() => __('page.general_settings.fields.brand_name'))
                                ->required(),
                            Forms\Components\Toggle::make('search_engine_indexing')
                                ->label('Admin Panel Indexing')
                                ->helperText('When disabled, search engines will be instructed not to index the admin panel')
                                ->default(true),
                        ]),
                    ]),

                Forms\Components\Section::make('Branding')
                    ->label('Branding & Visuals')
                    ->description('Customize the visual identity of your application')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('brand_logoHeight')
                                ->label(fn() => __('page.general_settings.fields.brand_logoHeight'))
                                ->numeric()
                                ->suffix('px')
                                ->required(),
                        ])->columnSpan(3),

                        Forms\Components\Grid::make()->schema([
                            Forms\Components\FileUpload::make('brand_logo')
                                ->label(fn() => __('page.general_settings.fields.brand_logo'))
                                ->image()
                                ->directory('sites')
                                ->visibility('public')
                                ->moveFiles()
                                ->imagePreviewHeight('100')
                                ->helperText('Upload your site logo (optional)'),

                            Forms\Components\FileUpload::make('site_favicon')
                                ->label(fn() => __('page.general_settings.fields.site_favicon'))
                                ->image()
                                ->directory('sites')
                                ->visibility('public')
                                ->moveFiles()
                                ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/jpeg'])
                                ->helperText('Supports .ico, .png, and .jpg formats (optional)'),
                        ])->columns(2)->columnSpan(3),
                    ])->columns(3),

                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Color Palette')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Forms\Components\Section::make('Theme Colors')
                                    ->description('Customize your admin panel color scheme')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\ColorPicker::make('site_theme.primary')
                                            ->label(fn() => __('page.general_settings.fields.primary'))
                                            ->helperText('Used for primary buttons and links'),
                                        Forms\Components\ColorPicker::make('site_theme.secondary')
                                            ->label(fn() => __('page.general_settings.fields.secondary'))
                                            ->helperText('Used for secondary elements'),
                                        Forms\Components\ColorPicker::make('site_theme.gray')
                                            ->label(fn() => __('page.general_settings.fields.gray'))
                                            ->helperText('Used for neutral backgrounds and text'),
                                    ])->columns(3),
                                Forms\Components\Section::make('Status Colors')
                                    ->description('Define colors for different states and notifications')
                                    ->compact()
                                    ->schema([
                                        Forms\Components\ColorPicker::make('site_theme.success')
                                            ->label(fn() => __('page.general_settings.fields.success'))
                                            ->helperText('Used for success states and confirmations'),
                                        Forms\Components\ColorPicker::make('site_theme.danger')
                                            ->label(fn() => __('page.general_settings.fields.danger'))
                                            ->helperText('Used for errors and dangerous actions'),
                                        Forms\Components\ColorPicker::make('site_theme.info')
                                            ->label(fn() => __('page.general_settings.fields.info'))
                                            ->helperText('Used for informational notifications'),
                                        Forms\Components\ColorPicker::make('site_theme.warning')
                                            ->label(fn() => __('page.general_settings.fields.warning'))
                                            ->helperText('Used for warnings and cautions'),
                                    ])->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Code Editor')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Grid::make()->schema([
                                    AceEditor::make('theme-editor')
                                        ->label('theme.css')
                                        ->mode('css')
                                        ->height('24rem')
                                        ->helperText('Edit the CSS theme directly (changes will be applied after saving)'),
                                    AceEditor::make('tw-config-editor')
                                        ->label('tailwind.config.js')
                                        ->mode('javascript')
                                        ->height('24rem')
                                        ->helperText('Edit the Tailwind configuration (changes will be applied after saving)'),
                                ])->columns(1)
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
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

            $fileService = new FileService;
            $fileService->writeFile($this->themePath, $data['theme-editor']);
            $fileService->writeFile($this->twConfigPath, $data['tw-config-editor']);

            Notification::make()
                ->title('Settings updated successfully!')
                ->body('Your changes have been saved.')
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
        return __("menu.nav_group.settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("page.general_settings.navigationLabel");
    }

    public function getTitle(): string|Htmlable
    {
        return __("page.general_settings.title");
    }

    public function getHeading(): string|Htmlable
    {
        return __("page.general_settings.heading");
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __("page.general_settings.subheading");
    }
}
