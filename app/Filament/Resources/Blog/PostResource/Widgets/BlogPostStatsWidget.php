<?php

namespace App\Filament\Resources\Blog\PostResource\Widgets;

use App\Enums\Blog\PostStatus;
use App\Models\Blog\Post;
use App\Models\Blog\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BlogPostStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $user = Auth::user();
        $query = Post::query();
        if ($user && $user->hasRole('author')) {
            $query->where(function ($q) use ($user) {
                $q->where('blog_author_id', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }
        // Get post counts by status
        $totalPosts = (clone $query)->count();
        $publishedPosts = (clone $query)->byStatus(PostStatus::PUBLISHED)->count();
        $draftPosts = (clone $query)->byStatus(PostStatus::DRAFT)->count();
        $pendingPosts = (clone $query)->byStatus(PostStatus::PENDING)->count();
        // Get posts published this month
        $postsThisMonth = (clone $query)->where('published_at', '>=', Carbon::now()->startOfMonth())
            ->where('published_at', '<=', Carbon::now()->endOfMonth())
            ->count();
        // Get percentage change from last month
        $postsLastMonth = (clone $query)->where('published_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->where('published_at', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->count();
        $percentageChange = $postsLastMonth > 0
            ? round((($postsThisMonth - $postsLastMonth) / $postsLastMonth) * 100)
            : 0;
        $trend = $percentageChange >= 0 ? 'up' : 'down';
        return [
            Stat::make('Total Posts', $totalPosts)
                ->description('All blog posts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),
            Stat::make('Published', $publishedPosts)
                ->description('Live on the site')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Drafts', $draftPosts)
                ->description('Posts in progress')
                ->descriptionIcon('heroicon-m-document')
                ->color('warning'),
            Stat::make('Pending', $pendingPosts)
                ->description('Scheduled for publication')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            Stat::make('This Month', $postsThisMonth)
                ->description($percentageChange . '% ' . $trend . ' from last month')
                ->descriptionIcon($trend === 'up' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend === 'up' ? 'success' : 'danger'),
            Stat::make('Categories', Category::active()->count())
                ->description('Active categories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary'),
        ];
    }
}
