<?php

namespace App\Filament\Pages\Setting;

use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Storage;

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

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $settings = app(static::getSettings());

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site')
                    ->label(fn () => __('page.general_settings.sections.site'))
                    ->description(fn () => __('page.general_settings.sections.site.description'))
                    ->icon('fluentui-web-asset-24-o')
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('brand_name')
                                ->label(fn () => __('page.general_settings.fields.brand_name'))
                                ->required(),
                            Forms\Components\Select::make('site_active')
                                ->label(fn () => __('page.general_settings.fields.site_active'))
                                ->options([
                                    0 => "Not Active",
                                    1 => "Active",
                                ])
                                ->native(false)
                                ->required(),
                        ]),
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Grid::make()->schema([
                                Forms\Components\TextInput::make('brand_logoHeight')
                                    ->label(fn () => __('page.general_settings.fields.brand_logoHeight'))
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('brand_logo')
                                    ->label(fn () => __('page.general_settings.fields.brand_logo'))
                                    ->image()
                                    ->required()
                                    ->columnSpan(2),
                            ])
                            ->columnSpan(2),
                            Forms\Components\FileUpload::make('site_favicon')
                                ->label(fn () => __('page.general_settings.fields.site_favicon'))
                                ->image()
                                ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon'])
                                ->required(),
                        ])->columns(4),
                    ]),
                Forms\Components\Section::make('Theme')
                    ->label(fn () => __('page.general_settings.sections.theme.title'))
                    ->description(fn () => __('page.general_settings.sections.theme.description'))
                    ->icon('fluentui-color-24-o')
                    ->schema([
                        Forms\Components\ColorPicker::make('site_theme.primary')
                            ->label(fn () => __('page.general_settings.fields.primary'))->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.secondary')
                            ->label(fn () => __('page.general_settings.fields.secondary'))->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.gray')
                            ->label(fn () => __('page.general_settings.fields.gray'))->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.success')
                            ->label(fn () => __('page.general_settings.fields.success'))->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.danger')
                            ->label(fn () => __('page.general_settings.fields.danger'))->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.info')
                            ->label(fn () => __('page.general_settings.fields.info'))->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.warning')
                            ->label(fn () => __('page.general_settings.fields.warning'))->rgb(),
                    ])
                    ->columns(3),
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

            $data = $this->handleUpload($data);

            $settings = app(static::getSettings());

            $settings->fill($data);
            $settings->save();

            $this->callHook('afterSave');

            Notification::make()
                ->title('Settings updated.')
                ->success()
                ->send();

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            throw $th;
            Notification::make()
                ->title('Failed to update settings.')
                ->danger()
                ->send();
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleUpload(array $data): array
    {
        $data['brand_logo'] = collect($data['brand_logo'])->first();
        if (!is_string($data['brand_logo'])) {
            Storage::move('livewire-tmp/' . $data['brand_logo']->getFilename(), 'public/sites/logo.png');
            $data['brand_logo'] = 'sites/logo.png';
        }

        $data['site_favicon'] = collect($data['site_favicon'])->first();
        if (!is_string($data['site_favicon'])) {
            Storage::move('livewire-tmp/' . $data['site_favicon']->getFilename(), 'public/sites/favicon.ico');
            $data['site_favicon'] = 'sites/favicon.ico';
        }

        return $data;
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
