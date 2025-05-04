<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSeoSettings extends Settings
{
    // General SEO settings
    public string $meta_title_format;
    public string $meta_description;
    public string $meta_keywords;
    public ?string $canonical_url;
    public bool $robots_indexing;
    public bool $robots_following;

    // Page type specific title formats
    public ?string $title_separator;
    public ?string $blog_title_format;
    public ?string $product_title_format;
    public ?string $category_title_format;
    public ?string $search_title_format;
    public ?string $author_title_format;

    // Open Graph settings
    public ?string $og_type;
    public ?string $og_title;
    public ?string $og_description;
    public ?string $og_image;
    public ?string $og_site_name;

    // Twitter Card settings
    public ?string $twitter_card_type;
    public ?string $twitter_site;
    public ?string $twitter_creator;
    public ?string $twitter_title;
    public ?string $twitter_description;
    public ?string $twitter_image;

    // Schema.org settings
    public ?string $schema_type;
    public ?string $schema_name;
    public ?string $schema_description;
    public ?string $schema_logo;

    // Additional settings
    public ?string $head_additional_meta;
    public ?array $verification_codes;
    public ?string $robots_txt_content;
    public bool $sitemap_enabled;
    public bool $sitemap_include_pages;
    public bool $sitemap_include_posts;
    public bool $sitemap_include_categories;
    public bool $sitemap_include_tags;

    public static function group(): string
    {
        return 'sites_seo';
    }
}
