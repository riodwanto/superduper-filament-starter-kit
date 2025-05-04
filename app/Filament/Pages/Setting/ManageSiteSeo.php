<?php

namespace App\Filament\Pages\Setting;

use App\Settings\SiteSeoSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Riodwanto\FilamentAceEditor\AceEditor;
use Filament\Actions\Action;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Support\HtmlString;

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['meta_keywords']) && is_string($data['meta_keywords'])) {
            $data['meta_keywords'] = array_map('trim', explode(',', $data['meta_keywords']));
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['meta_keywords']) && is_array($data['meta_keywords'])) {
            $data['meta_keywords'] = implode(', ', $data['meta_keywords']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('seo_settings_guide')
                ->label('SEO Settings Guide')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->iconPosition(IconPosition::Before)
                ->size('sm')
                ->modalHeading('SEO Settings Guide')
                ->modalDescription('A comprehensive guide to using Settings in your website.')
                ->modalIcon('heroicon-o-document-text')
                ->modalWidth('4xl')
                ->action(function () {
                    // Just close the modal
                })
                ->modalContent(view('filament.components.seo-guide-modal'))
                ->modalSubmitAction(false)
                ->modalCancelAction(false),

            \Filament\Actions\ActionGroup::make([
                Action::make('seo_preview')
                    ->label('Preview Meta Tags')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('SEO Preview')
                    ->modalWidth('md')
                    ->modalContent(fn() => new HtmlString($this->renderSeoPreview()))
                    ->modalSubmitAction(false),

                Action::make('export_settings')
                    ->label('Export Settings')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn() => $this->exportSettings()),

                Action::make('import_settings')
                    ->label('Import Settings')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        Forms\Components\FileUpload::make('settings_file')
                            ->label('Settings JSON File')
                            ->acceptedFileTypes(['application/json'])
                            ->required(),
                    ])
                    ->action(fn(array $data) => $this->importSettings($data['settings_file'])),
            ])
                ->color('primary')
                ->hiddenLabel()
                ->button()
                ->iconPosition(IconPosition::After),
        ];
    }

    protected function renderSeoPreview(): string
    {
        $formData = $this->form->getState();

        // This is a simple preview - in a real implementation, you'd replace placeholders
        // with actual sample data

        $separator = $formData['title_separator'] ?? '|';
        $sampleTitle = str_replace(
            ['{page_title}', '{separator}', '{site_name}'],
            ['Sample Page', $separator, 'Your Website'],
            $formData['meta_title_format'] ?? '{page_title} {separator} {site_name}'
        );

        $metaDescription = $formData['meta_description'] ?? 'Default meta description for the website';

        // Build HTML using concatenation instead of heredoc
        $preview = '<div class="space-y-4">';

        // Search result preview
        $preview .= '<div class="p-4 border rounded-lg bg-gray-50">';
        $preview .= '<div class="text-lg font-medium text-blue-600">' . $sampleTitle . '</div>';
        $preview .= '<div class="text-sm text-green-600">https://example.com/sample-page</div>';
        $preview .= '<div class="mt-1 text-sm text-gray-600">' . $metaDescription . '</div>';
        $preview .= '</div>';

        // Open Graph preview
        $preview .= '<div class="p-4 border rounded-lg">';
        $preview .= '<h3 class="font-medium">Open Graph Preview</h3>';
        $preview .= '<div class="p-3 mt-2 border rounded bg-blue-50">';
        $preview .= '<div class="font-medium text-md">' . $sampleTitle . '</div>';
        $preview .= '<div class="mt-1 text-xs text-gray-500">example.com</div>';
        $preview .= '<div class="mt-1 text-sm">' . $metaDescription . '</div>';
        $preview .= '</div>';
        $preview .= '</div>';

        // Twitter Card preview
        $preview .= '<div class="p-4 border rounded-lg">';
        $preview .= '<h3 class="font-medium">Twitter Card Preview</h3>';
        $preview .= '<div class="p-3 mt-2 border rounded bg-blue-50">';
        $preview .= '<div class="font-medium text-md">' . $sampleTitle . '</div>';
        $preview .= '<div class="mt-1 text-sm">' . $metaDescription . '</div>';
        $preview .= '<div class="mt-1 text-xs text-gray-500">example.com</div>';
        $preview .= '</div>';
        $preview .= '</div>';

        $preview .= '</div>';

        return $preview;
    }

    protected function exportSettings()
    {
        $settings = app(static::getSettings())->toArray();

        return response()->streamDownload(function () use ($settings) {
            echo json_encode($settings, JSON_PRETTY_PRINT);
        }, 'seo-settings-' . now()->format('Y-m-d') . '.json');
    }

    protected function importSettings($file)
    {
        try {
            $settingsJson = file_get_contents($file->getRealPath());
            $settings = json_decode($settingsJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format');
            }

            $this->form->fill($settings);

            Notification::make()
                ->title('Settings imported successfully!')
                ->success()
                ->send();

        } catch (\Throwable $th) {
            Notification::make()
                ->title('Error importing settings')
                ->body($th->getMessage())
                ->danger()
                ->send();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Seo Settings')
                    ->tabs([
                        $this->getBasicSeoTab(),
                        $this->getOpenGraphTab(),
                        $this->getTwitterTab(),
                        $this->getSchemaTab(),
                        $this->getAdditionalTab(),
                        $this->getRobotsSitemapTab(),
                    ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getBasicSeoTab(): Tab
    {

        return Tab::make('Basic SEO')
            ->icon('heroicon-o-document-text')
            ->schema([
                Section::make('Meta Information')
                    ->description('Basic meta tags configuration')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('title_separator')
                            ->label('Title Separator')
                            ->options([
                                '|' => 'Pipe (|)',
                                '-' => 'Dash (-)',
                                '—' => 'Em Dash (—)',
                                '·' => 'Dot (·)',
                                ':' => 'Colon (:)',
                                '»' => 'Double Angle Quotation (»)',
                            ])
                            ->default('|')
                            ->helperText('This defines the character that will replace {separator} in title formats')
                            ->required(),
                        Forms\Components\TextInput::make('meta_title_format')
                            ->label('Default Page Title Format')
                            ->required()
                            ->placeholder('{page_title} {separator} {site_name}')
                            ->helperText('Use {page_title}, {site_name}, and {separator} as placeholders.')
                            ->maxLength(100)
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('previewTitleFormat')
                                    ->icon('heroicon-m-eye')
                                    ->tooltip('Preview with sample data')
                                    ->action(function ($state, $livewire) {
                                        $separator = $livewire->data['title_separator'] ?? '|';
                                        $preview = str_replace(
                                            ['{page_title}', '{separator}', '{site_name}'],
                                            ['Sample Page', $separator, 'Your Website'],
                                            $state
                                        );

                                        Notification::make()
                                            ->title('Title Preview')
                                            ->body($preview)
                                            ->info()
                                            ->send();
                                    })
                            ),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Default Meta Description')
                            ->required()
                            ->placeholder('Enter a compelling description of your site (150-160 characters recommended)')
                            ->helperText(fn($state) => 'Character count: ' . strlen($state ?? ''))
                            ->rows(2)
                            ->maxLength(200),
                        Forms\Components\TagsInput::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->helperText('Comma-separated keywords (less important for SEO nowadays, but still used by some search engines)'),
                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Default Canonical URL')
                            ->placeholder('https://example.com')
                            ->url()
                            ->helperText('Leave empty to use the current URL'),
                    ]),

                Section::make('Page Type Title Formats')
                    ->description('Configure title formats for different page types')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('blog_title_format')
                            ->label('Blog Post Title Format')
                            ->placeholder('{post_title} {separator} {post_category} {separator} {site_name}')
                            ->helperText('Available: {post_title}, {post_category}, {author_name}, {publish_date}, {separator}')
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('previewBlogTitleFormat')
                                    ->icon('heroicon-m-eye')
                                    ->tooltip('Preview')
                                    ->action(fn() => Notification::make()->title('Blog Title Preview')->body('Sample Post | Blog | Your Website')->info()->send())
                            )
                            ->maxLength(100),
                        Forms\Components\TextInput::make('product_title_format')
                            ->label('Product Title Format')
                            ->placeholder('{product_name} {separator} {product_category} {separator} {site_name}')
                            ->helperText('Available: {product_name}, {product_category}, {product_brand}, {price}, {separator}')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('category_title_format')
                            ->label('Category Title Format')
                            ->placeholder('{category_name} {separator} {site_name}')
                            ->helperText('Available: {category_name}, {parent_category}, {products_count}, {separator}')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('search_title_format')
                            ->label('Search Results Title Format')
                            ->placeholder('Search results for "{search_term}" {separator} {site_name}')
                            ->helperText('Available: {search_term}, {results_count}, {separator}')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('author_title_format')
                            ->label('Author Page Title Format')
                            ->placeholder('Posts by {author_name} {separator} {site_name}')
                            ->helperText('Available: {author_name}, {post_count}, {separator}')
                            ->maxLength(100),
                    ]),
                Section::make('Indexing Control')
                    ->description('Control how search engines index your site')
                    ->collapsible()
                    ->schema([
                        Grid::make()->schema([
                            Forms\Components\Toggle::make('robots_indexing')
                                ->label('Allow Search Indexing')
                                ->helperText('When disabled, search engines won\'t index your site')
                                ->onColor('success')
                                ->offColor('danger'),
                            Forms\Components\Toggle::make('robots_following')
                                ->label('Allow Link Following')
                                ->helperText('When disabled, search engines won\'t follow links on your site')
                                ->onColor('success')
                                ->offColor('danger'),
                        ])->columns(2),
                    ]),
            ]);
    }

    protected function getOpenGraphTab(): Tab
    {
        return Tab::make('Open Graph')
            ->icon('heroicon-o-share')
            ->schema([
                Section::make('Open Graph Tags')
                    ->description('Settings for Facebook and other social media platforms')
                    ->collapsible()
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
                            ->imageEditor()
                            ->imagePreviewHeight('100')
                            ->helperText('Recommended size: 1200x630 pixels'),
                        Forms\Components\TextInput::make('og_site_name')
                            ->label('OG Site Name')
                            ->helperText('Use {site_name} as placeholder')
                            ->maxLength(100),
                    ]),
            ]);
    }

    protected function getTwitterTab(): Tab
    {
        return Tab::make('Twitter')
            ->icon('heroicon-o-chat-bubble-left-right')
            ->schema([
                Section::make('Twitter Card')
                    ->description('Settings for Twitter sharing')
                    ->collapsible()
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
                            ->imageEditor()
                            ->imagePreviewHeight('100')
                            ->helperText('Recommended size: 800x418 pixels'),
                    ]),
            ]);
    }

    protected function getSchemaTab(): Tab
    {
        return Tab::make('Schema')
            ->icon('heroicon-o-code-bracket')
            ->schema([
                Section::make('Structured Data')
                    ->description('JSON-LD Schema settings for rich results')
                    ->collapsible()
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
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === 'LocalBusiness') {
                                    $set('show_business_fields', true);
                                } else {
                                    $set('show_business_fields', false);
                                }
                            }),
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
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('schema_business_address')
                                    ->label('Business Address'),
                                Forms\Components\TextInput::make('schema_business_phone')
                                    ->label('Business Phone')
                                    ->tel(),
                                Forms\Components\TextInput::make('schema_business_email')
                                    ->label('Business Email')
                                    ->email(),
                                Forms\Components\TextInput::make('schema_business_opening_hours')
                                    ->label('Opening Hours'),
                            ])
                            ->columns(2)
                            ->visible(fn(callable $get): bool => $get('schema_type') === 'LocalBusiness'),
                    ]),
            ]);
    }

    protected function getAdditionalTab(): Tab
    {
        return Tab::make('Additional')
            ->icon('heroicon-o-plus-circle')
            ->schema([
                Section::make('Additional Meta Tags')
                    ->description('Add custom meta tags to the head section')
                    ->collapsible()
                    ->schema([
                        AceEditor::make('head_additional_meta')
                            ->label('Additional Head Meta Tags')
                            ->mode('html')
                            ->theme('monokai')
                            ->height('200px')
                            ->helperText('Enter additional meta tags, scripts, or links to be included in the head section'),
                    ]),
                Section::make('Site Verification')
                    ->description('Add verification codes for search engines')
                    ->collapsible()
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
            ]);
    }

    protected function getRobotsSitemapTab(): Tab
    {
        return Tab::make('Robots & Sitemap')
            ->icon('heroicon-o-cog')
            ->schema([
                Section::make('Robots.txt')
                    ->description('Configure your robots.txt file')
                    ->collapsible()
                    ->schema([
                        AceEditor::make('robots_txt_content')
                            ->label('Robots.txt Content')
                            ->mode('text')
                            ->theme('github')
                            ->height('200px')
                            ->helperText('Use {site_url} as a placeholder for your site URL'),
                    ]),
                Section::make('Sitemap')
                    ->description('Configure XML sitemap settings')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('sitemap_enabled')
                            ->label('Enable XML Sitemap')
                            ->required()
                            ->onColor('success'),
                        Grid::make()->schema([
                            Forms\Components\Toggle::make('sitemap_include_pages')
                                ->label('Include Pages')
                                ->inline(false),
                            Forms\Components\Toggle::make('sitemap_include_posts')
                                ->label('Include Posts')
                                ->inline(false),
                            Forms\Components\Toggle::make('sitemap_include_categories')
                                ->label('Include Categories')
                                ->inline(false),
                            Forms\Components\Toggle::make('sitemap_include_tags')
                                ->label('Include Tags')
                                ->inline(false),
                        ])->columns(2),
                        Forms\Components\Select::make('sitemap_change_frequency')
                            ->label('Default Change Frequency')
                            ->options([
                                'always' => 'Always',
                                'hourly' => 'Hourly',
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                                'monthly' => 'Monthly',
                                'yearly' => 'Yearly',
                                'never' => 'Never',
                            ])
                            ->default('weekly'),
                        Forms\Components\Select::make('sitemap_priority')
                            ->label('Default Priority')
                            ->options([
                                '1.0' => '1.0 - Highest',
                                '0.9' => '0.9',
                                '0.8' => '0.8',
                                '0.7' => '0.7',
                                '0.6' => '0.6',
                                '0.5' => '0.5 - Medium',
                                '0.4' => '0.4',
                                '0.3' => '0.3',
                                '0.2' => '0.2',
                                '0.1' => '0.1 - Lowest',
                            ])
                            ->default('0.5'),
                    ]),
            ]);
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

    public function getSubheading(): string|Htmlable|null
    {
        return 'Manage your website\'s search engine optimization';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.pages.dashboard') => 'Dashboard',
            route('filament.admin.pages.manage-site-seo') => 'Settings',
            null => 'SEO Settings',
        ];
    }
}
