<?php

namespace App\Filament\Pages\Setting;

use App\Settings\SiteSeoSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Riodwanto\FilamentAceEditor\AceEditor;

use function Filament\Support\is_app_url;

class ManageSiteSeo extends SettingsPage
{
    use HasPageShield;
    protected static string $settings = SiteSeoSettings::class;

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

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
                Forms\Components\Tabs::make('Seo Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic SEO')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Section::make('Meta Information')
                                    ->description('Basic meta tags configuration')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title_format')
                                            ->label('Meta Title Format')
                                            ->required()
                                            ->helperText('Use {page_title} and {site_name} as placeholders')
                                            ->maxLength(100),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Default Meta Description')
                                            ->rows(2)
                                            ->maxLength(160)
                                            ->helperText('Recommended length: 150-160 characters'),
                                        Forms\Components\TagsInput::make('meta_keywords')
                                            ->label('Default Meta Keywords')
                                            ->separator(',')
                                            ->helperText('Enter keywords separated by commas'),
                                        Forms\Components\TextInput::make('canonical_url')
                                            ->label('Canonical URL')
                                            ->prefix(function (Forms\Get $get) {
                                                return url('/');
                                            })
                                            ->helperText('Leave empty to use current URL as canonical'),
                                    ]),
                                Forms\Components\Section::make('Indexing Control')
                                    ->description('Control how search engines index your site')
                                    ->schema([
                                        Forms\Components\Grid::make()->schema([
                                            Forms\Components\Toggle::make('robots_indexing')
                                                ->label('Allow Search Indexing')
                                                ->helperText('When disabled, search engines won\'t index your site'),
                                            Forms\Components\Toggle::make('robots_following')
                                                ->label('Allow Link Following')
                                                ->helperText('When disabled, search engines won\'t follow links on your site'),
                                        ])->columns(2),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Open Graph')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Section::make('Open Graph Tags')
                                    ->description('Settings for Facebook and other social media platforms')
                                    ->schema([
                                        Forms\Components\Select::make('og_type')
                                            ->label('Default OG Type')
                                            ->options([
                                                'website' => 'Website',
                                                'article' => 'Article',
                                                'product' => 'Product',
                                                'profile' => 'Profile',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('og_title')
                                            ->label('Default OG Title')
                                            ->helperText('Use {page_title} as placeholder. Leave empty to use meta title')
                                            ->maxLength(100),
                                        Forms\Components\Textarea::make('og_description')
                                            ->label('Default OG Description')
                                            ->rows(2)
                                            ->helperText('Use {meta_description} as placeholder')
                                            ->maxLength(200),
                                        Forms\Components\FileUpload::make('og_image')
                                            ->label('Default OG Image')
                                            ->image()
                                            ->directory('sites')
                                            ->visibility('public')
                                            ->imagePreviewHeight('100')
                                            ->helperText('Recommended size: 1200x630 pixels'),
                                        Forms\Components\TextInput::make('og_site_name')
                                            ->label('OG Site Name')
                                            ->helperText('Use {site_name} as placeholder')
                                            ->maxLength(100),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Twitter')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Forms\Components\Section::make('Twitter Card')
                                    ->description('Settings for Twitter sharing')
                                    ->schema([
                                        Forms\Components\Select::make('twitter_card_type')
                                            ->label('Twitter Card Type')
                                            ->options([
                                                'summary' => 'Summary',
                                                'summary_large_image' => 'Summary with Large Image',
                                                'app' => 'App',
                                                'player' => 'Player',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('twitter_site')
                                            ->label('Twitter Site Username')
                                            ->prefix('@')
                                            ->maxLength(15),
                                        Forms\Components\TextInput::make('twitter_creator')
                                            ->label('Twitter Creator Username')
                                            ->prefix('@')
                                            ->maxLength(15),
                                        Forms\Components\TextInput::make('twitter_title')
                                            ->label('Default Twitter Title')
                                            ->helperText('Use {page_title} as placeholder. Leave empty to use meta title')
                                            ->maxLength(70),
                                        Forms\Components\Textarea::make('twitter_description')
                                            ->label('Default Twitter Description')
                                            ->rows(2)
                                            ->helperText('Use {meta_description} as placeholder')
                                            ->maxLength(200),
                                        Forms\Components\FileUpload::make('twitter_image')
                                            ->label('Default Twitter Image')
                                            ->image()
                                            ->directory('sites')
                                            ->visibility('public')
                                            ->imagePreviewHeight('100')
                                            ->helperText('Recommended size: 800x418 pixels'),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Schema')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Section::make('Structured Data')
                                    ->description('JSON-LD Schema settings for rich results')
                                    ->schema([
                                        Forms\Components\Select::make('schema_type')
                                            ->label('Organization/Person Type')
                                            ->options([
                                                'Organization' => 'Organization',
                                                'Person' => 'Person',
                                                'LocalBusiness' => 'Local Business',
                                                'Restaurant' => 'Restaurant',
                                                'Hotel' => 'Hotel',
                                                'WebSite' => 'Website',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('schema_name')
                                            ->label('Schema Name')
                                            ->helperText('Use {site_name} as placeholder')
                                            ->required()
                                            ->maxLength(100),
                                        Forms\Components\Textarea::make('schema_description')
                                            ->label('Schema Description')
                                            ->helperText('Use {meta_description} as placeholder')
                                            ->rows(2)
                                            ->maxLength(200),
                                        Forms\Components\FileUpload::make('schema_logo')
                                            ->label('Schema Logo')
                                            ->image()
                                            ->directory('sites')
                                            ->visibility('public')
                                            ->imagePreviewHeight('100')
                                            ->helperText('Recommended size: 112x112 pixels'),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Additional')
                            ->icon('heroicon-o-plus-circle')
                            ->schema([
                                Forms\Components\Section::make('Additional Meta Tags')
                                    ->description('Add custom meta tags to the head section')
                                    ->schema([
                                        AceEditor::make('head_additional_meta')
                                            ->label('Additional Head Meta Tags')
                                            ->mode('html')
                                            ->height('200px')
                                            ->helperText('Enter additional meta tags, scripts, or links to be included in the head section'),
                                    ]),
                                Forms\Components\Section::make('Site Verification')
                                    ->description('Add verification codes for search engines')
                                    ->schema([
                                        Forms\Components\TextInput::make('verification_codes.google')
                                            ->label('Google Search Console')
                                            ->helperText('Enter only the content value, not the full meta tag'),
                                        Forms\Components\TextInput::make('verification_codes.bing')
                                            ->label('Bing Webmaster Tools')
                                            ->helperText('Enter only the content value, not the full meta tag'),
                                        Forms\Components\TextInput::make('verification_codes.yandex')
                                            ->label('Yandex Webmaster')
                                            ->helperText('Enter only the content value, not the full meta tag'),
                                        Forms\Components\TextInput::make('verification_codes.baidu')
                                            ->label('Baidu Webmaster Tools')
                                            ->helperText('Enter only the content value, not the full meta tag'),
                                    ])->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Robots & Sitemap')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Forms\Components\Section::make('Robots.txt')
                                    ->description('Configure your robots.txt file')
                                    ->schema([
                                        AceEditor::make('robots_txt_content')
                                            ->label('Robots.txt Content')
                                            ->mode('text')
                                            ->height('200px')
                                            ->helperText('Use {site_url} as a placeholder for your site URL'),
                                    ]),
                                Forms\Components\Section::make('Sitemap')
                                    ->description('Configure XML sitemap settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('sitemap_enabled')
                                            ->label('Enable XML Sitemap')
                                            ->required(),
                                        Forms\Components\Grid::make()->schema([
                                            Forms\Components\Toggle::make('sitemap_include_pages')
                                                ->label('Include Pages'),
                                            Forms\Components\Toggle::make('sitemap_include_posts')
                                                ->label('Include Posts'),
                                            Forms\Components\Toggle::make('sitemap_include_categories')
                                                ->label('Include Categories'),
                                            Forms\Components\Toggle::make('sitemap_include_tags')
                                                ->label('Include Tags'),
                                        ])->columns(2),
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
                ->body('Your SEO settings have been updated.')
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
        return 'SEO';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Site SEO Settings';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Site SEO Settings';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Manage your website\'s search engine optimization';
    }
}
