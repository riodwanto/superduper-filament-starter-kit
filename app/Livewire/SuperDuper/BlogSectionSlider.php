<?php

namespace App\Livewire\SuperDuper;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use Livewire\Component;
use Illuminate\Support\Facades\App;

class BlogSectionSlider extends Component
{
    public $articles = [];
    public $activeCategory = null;
    public $categories = [];
    public $featuredOnly = false;
    public $limit = 5;

    public function mount($limit = 5, $featuredOnly = false, $categorySlug = null)
    {
        $this->limit = $limit;
        $this->featuredOnly = $featuredOnly;

        // Get all active categories
        $this->categories = Category::active()
            ->withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        // Set active category if provided
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $this->activeCategory = $category->id;
            }
        }

        $this->loadArticles();
    }

    public function filterByCategory($categoryId = null)
    {
        $this->activeCategory = $categoryId;
        $this->loadArticles();
    }

    public function toggleFeatured()
    {
        $this->featuredOnly = !$this->featuredOnly;
        $this->loadArticles();
    }

    public function loadArticles()
    {
        $query = Post::with(['category', 'author', 'media'])
            ->published()
            ->locale(App::getLocale());

        if ($this->featuredOnly) {
            $query->featured();
        }

        if ($this->activeCategory) {
            $query->where('blog_category_id', $this->activeCategory);
        }

        $this->articles = $query->orderBy('published_at', 'desc')
            ->limit($this->limit)
            ->get();
    }

    public function trackView($postId)
    {
        $post = Post::find($postId);
        if ($post) {
            $post->trackView();
        }
    }

    public function render()
    {
        return view('livewire.superduper.blog-section-slider');
    }
}
