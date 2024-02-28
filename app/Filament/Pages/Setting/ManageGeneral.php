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
                    ->description('Manage basic settings.')
                    ->icon('fluentui-web-asset-24-o')
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('brand_name')->label('Brand Name')
                                ->required(),
                            Forms\Components\Select::make('site_active')->label('Site Status')
                                ->options([
                                    0 => "Not Active",
                                    1 => "Active",
                                ])
                                ->native(false)
                                ->required(),
                        ]),
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Grid::make()->schema([
                                Forms\Components\TextInput::make('brand_logoHeight')->label('Brand Logo Height')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\FileUpload::make('brand_logo')->label('Brand Logo')
                                    ->image()
                                    ->required()
                                    ->columnSpan(2),
                            ])
                            ->columnSpan(2),
                            Forms\Components\FileUpload::make('site_favicon')->label('Site Favicon')
                                ->image()
                                ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon'])
                                ->required(),
                        ])->columns(4),
                    ]),
                Forms\Components\Section::make('Theme')
                    ->description('Change default theme.')
                    ->icon('fluentui-color-24-o')
                    ->schema([
                        Forms\Components\ColorPicker::make('site_theme.primary')->label('Primary')->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.secondary')->label('Secondary')->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.gray')->label('Gray')->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.success')->label('Success')->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.danger')->label('Danger')->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.info')->label('Info')->rgb(),
                        Forms\Components\ColorPicker::make('site_theme.warning')->label('Warning')->rgb(),
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
