<?php

namespace App\Filament\Resources\Blog\PostResource\Widgets;

use App\Models\Blog\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PopularPostsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Post::published()
            ->orderBy('view_count', 'desc')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->limit(40)
                ->sortable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Category'),

            Tables\Columns\TextColumn::make('author.firstname')
                ->label('Author')
                ->formatStateUsing(fn($record) => "{$record->author->firstname} {$record->author->lastname}"),

            Tables\Columns\TextColumn::make('view_count')
                ->label('Views')
                ->sortable(),

            Tables\Columns\TextColumn::make('reading_time')
                ->label('Reading Time')
                ->suffix(' min'),

            Tables\Columns\TextColumn::make('published_at')
                ->label('Published')
                ->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->url(fn(Post $record): string => $record->getUrl())
                ->icon('heroicon-o-eye')
                ->openUrlInNewTab(),

            Tables\Actions\Action::make('edit')
                ->url(fn(Post $record): string => route('filament.admin.resources.blog.posts.edit', $record))
                ->icon('heroicon-o-pencil-square'),
        ];
    }

    protected function getTableHeading(): string
    {
        return 'Popular Posts';
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-document-text';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No popular posts yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Posts will appear here once they start getting views.';
    }
}
