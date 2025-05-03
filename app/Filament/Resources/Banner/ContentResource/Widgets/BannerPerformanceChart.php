<?php

namespace App\Filament\Resources\Banner\ContentResource\Widgets;

use App\Models\Banner\Content;
use Filament\Widgets\ChartWidget;

class BannerPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Banner Performance';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        // Get data for the last 30 days
        $period = now()->subDays(30)->startOfDay();

        // Get daily impressions and clicks
        $stats = [];
        $labels = [];
        $impressionData = [];
        $clickData = [];

        for ($i = 0; $i < 30; $i++) {
            $date = $period->copy()->addDays($i);
            $nextDate = $date->copy()->addDay();

            // This is a simplified example - in a real app you would have a proper stats table
            // with daily aggregated data. This is just a demonstration.
            $dailyImpressions = Content::where('updated_at', '>=', $date)
                ->where('updated_at', '<', $nextDate)
                ->sum('impression_count');

            $dailyClicks = Content::where('updated_at', '>=', $date)
                ->where('updated_at', '<', $nextDate)
                ->sum('click_count');

            $labels[] = $date->format('M j');
            $impressionData[] = $dailyImpressions / 100; // Scaled for better visualization
            $clickData[] = $dailyClicks;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Impressions (hundreds)',
                    'data' => $impressionData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
                [
                    'label' => 'Clicks',
                    'data' => $clickData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
