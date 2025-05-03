<?php

namespace App\Filament\Resources\Blog\PostResource\Widgets;

use App\Models\Blog\Post;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ContentOverviewWidget extends ChartWidget
{
    protected static ?string $heading = 'Content Activity';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Generate data for the last 30 days
        $data = Trend::model(Post::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        // Generate view count data
        $viewData = Post::selectRaw('DATE(updated_at) as date, SUM(view_count) as total')
            ->where('updated_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get()
            ->mapWithKeys(fn($item) => [
                Carbon::parse($item->date)->format('Y-m-d') => $item->total
            ]);

        // Combine the data sets
        $labels = $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('M d'));

        $postCounts = $data->map(fn(TrendValue $value) => $value->aggregate);

        $viewCounts = $data->map(function (TrendValue $value) use ($viewData) {
            $date = Carbon::parse($value->date)->format('Y-m-d');
            return $viewData[$date] ?? 0;
        });

        return [
            'datasets' => [
                [
                    'label' => 'New Posts',
                    'data' => $postCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
                [
                    'label' => 'Views (÷10)',
                    'data' => $viewCounts->map(fn($count) => round($count / 10)), // Scale down for better visualization
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'All Time',
            'week' => 'Last Week',
            'month' => 'Last Month',
            'year' => 'This Year',
        ];
    }

    public function filterAll(): array
    {
        return $this->getData();
    }

    public function filterWeek(): array
    {
        $data = Trend::model(Post::class)
            ->between(
                start: now()->subDays(7),
                end: now(),
            )
            ->perDay()
            ->count();

        $labels = $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('M d'));
        $postCounts = $data->map(fn(TrendValue $value) => $value->aggregate);

        return [
            'datasets' => [
                [
                    'label' => 'New Posts',
                    'data' => $postCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
