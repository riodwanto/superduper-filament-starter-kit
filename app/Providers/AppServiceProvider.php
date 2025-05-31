<?php

namespace App\Providers;

use Filament\Tables\Table;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use App\Models\Blog\Post;
use App\Observers\PostObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class);

        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->extremePaginationLinks()
                ->defaultSort('created_at', 'desc');
        });

        // # \Opcodes\LogViewer
        LogViewer::auth(function ($request) {
            $user = auth()->user();
            $role = $user?->roles?->first()?->name;
            return $role == config('filament-shield.super_admin.name');
        });

        // # Filament Hooks
        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn(): View => view('filament.components.panel-footer'),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn(): View => view('filament.components.button-website'),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn() => view('filament.components.impersonate-banner')
        );
    }
}
