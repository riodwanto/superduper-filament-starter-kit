<?php

namespace App\Http\Controllers;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = $this->generateSitemap();

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600', // Cache for 1 hour
        ]);
    }

    private function generateSitemap(): string
    {
        $urls = collect();

        // Add static pages
        $staticPages = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => route('about-us'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => route('blog'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => route('contact-us'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $urls->push([
                'loc' => $page['url'],
                'lastmod' => now()->toISOString(),
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ]);
        }

        // Add blog posts
        Post::published()
            ->select(['slug', 'updated_at', 'published_at'])
            ->chunk(100, function ($posts) use ($urls) {
                foreach ($posts as $post) {
                    $urls->push([
                        'loc' => route('blog.show', $post->slug),
                        'lastmod' => $post->updated_at->toISOString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ]);
                }
            });

        // Add blog categories
        Category::whereHas('posts', function ($query) {
                $query->published();
            })
            ->select(['slug', 'updated_at'])
            ->chunk(50, function ($categories) use ($urls) {
                foreach ($categories as $category) {
                    $urls->push([
                        'loc' => url('/blog?category=' . $category->slug),
                        'lastmod' => $category->updated_at->toISOString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ]);
                }
            });

        return $this->buildXml($urls);
    }

    private function buildXml($urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    public function robots(): Response
    {
        $generalSettings = app(\App\Settings\GeneralSettings::class);
        $allowIndexing = $generalSettings->search_engine_indexing ?? false;

        $robots = '';

        if ($allowIndexing) {
            $robots .= "User-agent: *\n";
            $robots .= "Allow: /\n";
            $robots .= "Disallow: /admin/\n";
            $robots .= "Disallow: /livewire/\n";
            $robots .= "Disallow: /storage/livewire-tmp/\n";
            $robots .= "\n";
            $robots .= "Sitemap: " . route('sitemap') . "\n";
        } else {
            $robots .= "User-agent: *\n";
            $robots .= "Disallow: /\n";
        }

        return response($robots, 200, [
            'Content-Type' => 'text/plain',
            'Cache-Control' => 'public, max-age=86400', // Cache for 24 hours
        ]);
    }
}
