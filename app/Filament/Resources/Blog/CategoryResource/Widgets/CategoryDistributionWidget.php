<?php

namespace App\Filament\Resources\Blog\CategoryResource\Widgets;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Filament\Widgets\ChartWidget;

class CategoryDistributionWidget extends ChartWidget
{
    protected static ?string $heading = 'Posts by Category';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $categories = Category::withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->limit(8)
            ->get();

        // If more than the limit, add an "Other" category
        $totalPostsInCategories = $categories->sum('posts_count');
        $totalPosts = Post::count();
        $otherPosts = $totalPosts - $totalPostsInCategories;

        $labels = $categories->pluck('name')->toArray();
        $data = $categories->pluck('posts_count')->toArray();

        if ($otherPosts > 0) {
            $labels[] = 'Other';
            $data[] = $otherPosts;
        }

        // Generate colors
        $backgroundColor = [
            'rgba(59, 130, 246, 0.8)', // Blue
            'rgba(16, 185, 129, 0.8)', // Green
            'rgba(245, 158, 11, 0.8)', // Yellow
            'rgba(239, 68, 68, 0.8)',  // Red
            'rgba(139, 92, 246, 0.8)', // Purple
            'rgba(236, 72, 153, 0.8)', // Pink
            'rgba(20, 184, 166, 0.8)', // Teal
            'rgba(249, 115, 22, 0.8)', // Orange
            'rgba(156, 163, 175, 0.8)', // Gray (for Other)
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Posts',
                    'data' => $data,
                    'backgroundColor' => $backgroundColor,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let sum = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / sum) * 100);
                            return label + ': ' + value + ' posts (' + percentage + '%)';
                        }",
                    ],
                ],
            ],
        ];
    }
}
