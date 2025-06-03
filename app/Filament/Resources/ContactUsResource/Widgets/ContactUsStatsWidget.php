<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactUsResource\Widgets;

use App\Models\ContactUs;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ContactUsStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $query = ContactUs::query();

        $total = (clone $query)->count();
        $new = (clone $query)->where('status', 'new')->count();
        $responded = (clone $query)->where('status', 'responded')->count();

        $thisMonth = (clone $query)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('created_at', '<=', Carbon::now()->endOfMonth())
            ->count();
        $lastMonth = (clone $query)
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->where('created_at', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->count();
        $percentageChange = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100)
            : 0;
        $trend = $percentageChange >= 0 ? 'up' : 'down';

        return [
            Stat::make('Total Messages', $total)
                ->description('All contact messages')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('gray'),
            Stat::make('New', $new)
                ->description('New messages')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('danger'),
            Stat::make('Responded', $responded)
                ->description('Responded messages')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('This Month', $thisMonth)
                ->description($percentageChange . '% ' . $trend . ' from last month')
                ->descriptionIcon($trend === 'up' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend === 'up' ? 'success' : 'danger'),
        ];
    }
}
