<?php

namespace App\Observers;

use App\Models\Blog\Post;
use App\Models\User;

class PostObserver
{
    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        // Set created_by field
        if (empty($post->created_by)) {
            $post->created_by = $user->id;
        }

        // Set updated_by field
        $post->updated_by = $user->id;

        // Auto-assign author for authors creating posts
        if ($user->hasRole('author') && empty($post->blog_author_id)) {
            $post->blog_author_id = $user->id;
        }

        // If no author is set and user is not an author, set the first available author
        if (empty($post->blog_author_id)) {
            $firstAuthor = User::whereHas('roles', function ($query) {
                $query->where('name', 'author');
            })->first();

            if ($firstAuthor) {
                $post->blog_author_id = $firstAuthor->id;
            }
        }

        // Authors cannot set posts as featured
        if ($user->hasRole('author') && !$user->can('feature', $post)) {
            $post->is_featured = false;
        }

        // Authors cannot set posts to published status directly
        if ($user->hasRole('author') && !$user->can('publish', $post)) {
            if ($post->status === 'published') {
                $post->status = 'draft';
            }
        }
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        // Always update the updated_by field
        $post->updated_by = $user->id;

        // Prevent authors from changing certain fields
        if ($user->hasRole('author')) {
            $original = $post->getOriginal();

            // Authors cannot change the author if they don't have permission
            if (!$user->can('changeAuthor', $post)) {
                if ($post->isDirty('blog_author_id') && $original['blog_author_id'] !== $user->id) {
                    $post->blog_author_id = $original['blog_author_id'];
                }
            }

            // Authors cannot feature posts
            if (!$user->can('feature', $post)) {
                if ($post->isDirty('is_featured')) {
                    $post->is_featured = $original['is_featured'] ?? false;
                }
            }

            // Authors cannot publish posts directly
            if (!$user->can('publish', $post)) {
                if ($post->isDirty('status') && $post->status === 'published') {
                    $post->status = $original['status'] ?? 'draft';
                }
            }

            // Authors cannot change creation info
            if ($post->isDirty('created_by') && $original['created_by'] !== $user->id) {
                $post->created_by = $original['created_by'];
            }
        }
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // You can add any post-creation logic here
        // For example, sending notifications to editors when authors submit for review

        if ($post->status === 'pending') {
            // Send notification to editors/admins
            $this->notifyEditorsOfPendingPost($post);
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Handle status change notifications
        if ($post->wasChanged('status')) {
            $this->handleStatusChangeNotifications($post);
        }
    }

    /**
     * Notify editors when a post is submitted for review
     */
    private function notifyEditorsOfPendingPost(Post $post): void
    {
        // You can implement notification logic here
        // For example, using Laravel's notification system

        $editors = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['editor', 'admin']);
        })->get();

        // Send notifications to editors
        // $editors->each(function ($editor) use ($post) {
        //     $editor->notify(new PostPendingReview($post));
        // });
    }

    /**
     * Handle notifications for status changes
     */
    private function handleStatusChangeNotifications(Post $post): void
    {
        // You can implement status change notification logic here

        switch ($post->status->value ?? $post->status) {
            case 'published':
                // Notify author that their post was published
                if ($post->author) {
                    // $post->author->notify(new PostPublished($post));
                }
                break;

            case 'archived':
                // Notify author that their post was archived
                if ($post->author) {
                    // $post->author->notify(new PostArchived($post));
                }
                break;
        }
    }
}
