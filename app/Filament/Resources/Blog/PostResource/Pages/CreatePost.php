<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->status === \App\Enums\Blog\PostStatus::PENDING) {
            $users = \App\Models\User::permission('approve_blog::post')->get();
            foreach ($users as $user) {
                \Filament\Notifications\Notification::make()
                    ->title('Post submitted for approval')
                    ->body('The post "' . $this->record->title . '" has been submitted for approval.')
                    ->info()
                    ->sendToDatabase($user);
            }
        }
    }
}
