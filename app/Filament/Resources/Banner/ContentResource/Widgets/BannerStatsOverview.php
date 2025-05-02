<?php

namespace App\Filament\Resources\Banner\ContentResource\Widgets;

use App\Models\Banner\Category;
use App\Models\Banner\Content;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BannerStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '15s';

    public function getStats(): array
    {
        // Count active banners
        $activeBanners = Content::active()->count();
        $totalBanners = Content::count();
        $activePercentage = $totalBanners > 0 ? round(($activeBanners / $totalBanners) * 100) : 0;

        // Count active categories
        $activeCategories = Category::active()->count();
        $totalCategories = Category::count();

        // Get total impressions and clicks
        $totalImpressions = Content::sum('impression_count');
        $totalClicks = Content::sum('click_count');
        $ctr = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0;

        // Get scheduled banners
        $scheduledBanners = Content::whereNotNull('start_date')
            ->where('start_date', '>', now())
            ->count();

        // Get expiring banners (ending in next 7 days)
        $expiringBanners = Content::whereNotNull('end_date')
            ->where('end_date', '>', now())
            ->where('end_date', '<', now()->addDays(7))
            ->count();

        return [
            Stat::make('Active Banners', $activeBanners)
                ->description($activePercentage . '% of total banners')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 4, 6, 8, 7, $activePercentage])
                ->color('success'),

            Stat::make('Total Categories', $totalCategories)
                ->description($activeCategories . ' active categories')
                ->descriptionIcon('heroicon-m-folder')
                ->color('primary'),

            Stat::make('Total Impressions', number_format($totalImpressions))
                ->description('CTR: ' . $ctr . '%')
                ->descriptionIcon('heroicon-m-cursor-arrow-rays')
                ->color($ctr > 2 ? 'success' : 'warning'),

            Stat::make('Scheduled', $scheduledBanners)
                ->description($expiringBanners . ' expiring soon')
                ->descriptionIcon('heroicon-m-calendar')
                ->color($expiringBanners > 0 ? 'warning' : 'success'),
        ];
    }
}
