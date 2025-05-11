<?php

namespace App\Livewire\SuperDuper;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BlogDetails extends Component
{
    public $post;
    public $slug;
    public $relatedPosts = [];
    public $previousPost;
    public $nextPost;
    public $recentPosts = [];
    public $categories = [];
    public $popularTags = [];

    public function mount($slug)
    {
        $this->slug = $slug;

        // Redirect if slug has trailing slash
        if (substr($this->slug, -1) === '/') {
            return redirect()->to(rtrim(request()->path(), '/'), 301);
        }

        $this->loadPost();
        $this->loadSidebarData();
    }

    protected function loadPost()
    {
        $this->post = Post::with([
            'category',
            'author',
            'tags',
            'media'
        ])
        ->where('slug', $this->slug)
        ->published()
        ->firstOrFail();

        // Track view
        $this->post->trackView();

        // Load related/navigation posts
        $this->relatedPosts = $this->post->getRelatedPosts(3);
        $this->previousPost = $this->post->getPreviousPost();
        $this->nextPost = $this->post->getNextPost();

        // Set SEO metadata
        view()->share('canonical', $this->post->getCanonicalUrl());
        view()->share('metaTitle', $this->post->meta_title ?: $this->post->title);
        view()->share('metaDescription', $this->post->meta_description ?: $this->post->content_overview);

        // Add schema.org metadata for SEO
        view()->share('schemaData', $this->generateSchemaData());
    }

    protected function loadSidebarData()
    {
        // Get recent posts
        $this->recentPosts = Cache::remember('recent_posts', now()->addMinutes(30), function () {
            return Post::published()
                ->where('id', '!=', $this->post->id)
                ->select(['id', 'title', 'slug', 'blog_category_id', 'published_at', 'content_overview'])
                ->with(['category:id,name,slug', 'media' => function($query) {
                    $query->where('collection_name', 'featured');
                }])
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();
        });

        // Get categories with post counts
        $this->categories = Cache::remember('active_categories', now()->addHours(3), function () {
            return Category::active()
                ->withCount(['posts' => function($query) {
                    $query->published();
                }])
                ->having('posts_count', '>', 0)
                ->orderBy('name')
                ->get();
        });

        // Get popular tags
        $locale = app()->getLocale();
        $this->popularTags = Cache::remember('popular_tags_' . $locale, now()->addHours(6), function () use ($locale) {
            // Use a more efficient query with proper indexing
            $rawTags = DB::table('taggables')
                ->join('tags', 'taggables.tag_id', '=', 'tags.id')
                ->join('blog_posts', function($join) {
                    $join->on('taggables.taggable_id', '=', 'blog_posts.id')
                        ->where('taggables.taggable_type', Post::class);
                })
                ->where('blog_posts.status', 'published')
                ->where('blog_posts.published_at', '<=', now())
                ->select(['tags.id', 'tags.name', DB::raw('COUNT(*) as count')])
                ->groupBy('tags.id', 'tags.name')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            return $rawTags->map(function ($tag) use ($locale) {
                $name = $tag->name;

                if (isset($name[0]) && $name[0] === '{') {
                    try {
                        $decoded = json_decode($name, true, 512, JSON_THROW_ON_ERROR);
                        $name = $decoded[$locale] ?? reset($decoded) ?? $name;
                    } catch (\JsonException $e) {
                        // Fallback to original name if JSON parsing fails
                    }
                }

                return [
                    'name' => $name,
                    'count' => $tag->count
                ];
            })->toArray();
        });
    }

    // Generate Schema.org structured data
    protected function generateSchemaData()
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $this->post->title,
            'description' => $this->post->meta_description ?: $this->post->content_overview,
            'image' => $this->post->hasFeaturedImage() ? $this->post->getFeaturedImageUrl('large') : null,
            'datePublished' => $this->post->published_at->toIso8601String(),
            'dateModified' => $this->post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->post->author->name ?? 'Anonymous',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('path/to/your/logo.png'),
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->post->getCanonicalUrl(),
            ],
        ];

        return json_encode($schema);
    }

    // Share post to social media
    public function sharePost($platform)
    {
        $url = urlencode($this->post->getCanonicalUrl());
        $title = urlencode($this->post->title);

        switch ($platform) {
            case 'twitter':
                return redirect()->away("https://twitter.com/intent/tweet?url={$url}&text={$title}");
            case 'facebook':
                return redirect()->away("https://www.facebook.com/sharer/sharer.php?u={$url}");
            case 'linkedin':
                return redirect()->away("https://www.linkedin.com/sharing/share-offsite/?url={$url}");
            case 'whatsapp':
                return redirect()->away("https://api.whatsapp.com/send?text={$title}%20{$url}");
        }
    }

    public function render()
    {
        return view('livewire.superduper.blog-details')->layout('components.superduper.main');
    }
}
