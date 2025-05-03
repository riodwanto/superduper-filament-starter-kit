<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('view_on_site')
                ->label('View on Website')
                ->url(fn () => $this->record->getUrl())
                ->icon('heroicon-o-globe-alt')
                ->openUrlInNewTab(),
            Actions\Action::make('duplicate')
                ->icon('heroicon-o-document-duplicate')
                ->action(function () {
                    $record = $this->record;
                    $duplicate = $record->replicate();
                    $duplicate->title = "Copy of " . $record->title;
                    $duplicate->slug = \Illuminate\Support\Str::slug($duplicate->title);
                    $duplicate->status = 'draft';
                    $duplicate->published_at = null;
                    $duplicate->view_count = 0;
                    $duplicate->comments_count = 0;
                    $duplicate->save();

                    // Copy tags
                    $duplicate->syncTags($record->tags);

                    // Copy media
                    foreach ($record->getMedia('featured') as $media) {
                        $media->copy($duplicate, 'featured');
                    }

                    foreach ($record->getMedia('gallery') as $media) {
                        $media->copy($duplicate, 'gallery');
                    }

                    return redirect()->route('filament.admin.resources.blog.posts.edit', $duplicate->id);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
