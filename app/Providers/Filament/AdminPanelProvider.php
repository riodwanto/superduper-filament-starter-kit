<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EmailVerification;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Filament\Resources\MenuResource;
use App\Http\Middleware\FilamentRobotsMiddleware;
use App\Livewire\MyProfileExtended;
use App\Settings\GeneralSettings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->passwordReset(RequestPasswordReset::class)
            ->emailVerification(EmailVerification::class)
            ->favicon(fn(GeneralSettings $settings) => Storage::url($settings->site_favicon))
            ->brandName(fn(GeneralSettings $settings) => $settings->brand_name)
            ->brandLogo(fn(GeneralSettings $settings) => Storage::url($settings->brand_logo))
            ->brandLogoHeight(fn(GeneralSettings $settings) => $settings->brand_logoHeight)
            ->colors(fn(GeneralSettings $settings) => $settings->site_theme)
            ->databaseNotifications()->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.content'))
                    ->collapsible(false),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.access'))
                    ->collapsible(false),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.sites'))
                    ->collapsed(),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.systems'))
                    ->collapsed(),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.activities'))
                    ->collapsed(),
            ])
            ->navigationItems([
                Navigation\NavigationItem::make(__('menu.nav_group.item.log_viewer'))
                    ->visible(fn(): bool => auth()->user()->can('access_log_viewer'))
                    ->url(config('app.url') . '/' . config('log-viewer.route_path'), shouldOpenInNewTab: true)
                    ->icon('fluentui-document-bullet-list-multiple-20-o')
                    ->group(__('menu.nav_group.activities'))
                    ->sort(99),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\FilamentInfoWidget::class,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                FilamentRobotsMiddleware::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \TomatoPHP\FilamentMediaManager\FilamentMediaManagerPlugin::make()
                    ->allowSubFolders(),
                \BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                        'sm' => 1
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        shouldRegisterNavigation: false,
                        navigationGroup: 'Settings',
                        hasAvatars: true,
                        slug: 'my-profile'
                    )
                    ->myProfileComponents([
                        'personal_info' => MyProfileExtended::class,
                    ]),
                \Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin::make()
                    ->addLocations([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'footer-2' => 'Footer 2',
                        'footer-3' => 'Footer 3',
                        'footer-4' => 'Footer 4',
                    ])
                    ->usingResource(MenuResource::class)
                    ->addMenuPanels([
                        \Datlechin\FilamentMenuBuilder\MenuPanel\StaticMenuPanel::make()
                            ->addMany([
                                'Home' => url('/'),
                                'Blog' => url('/blog'),
                                'Contact Us' => url('/contact-us'),
                            ])
                            ->description('Default menus')
                            ->collapsed(true)
                            ->collapsible(true)
                            ->paginate(perPage: 5, condition: true)
                    ])
            ]);
    }
}
