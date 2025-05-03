<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // General SEO settings
        $this->migrator->add('sites_seo.meta_title_format', '{page_title} {separator} {site_name}');
        $this->migrator->add('sites_seo.meta_description', 'Default meta description for the website');
        $this->migrator->add('sites_seo.meta_keywords', 'keyword1, keyword2, keyword3');
        $this->migrator->add('sites_seo.canonical_url', '');
        $this->migrator->add('sites_seo.robots_indexing', true);
        $this->migrator->add('sites_seo.robots_following', true);

        // Page type specific title formats
        $this->migrator->add('sites_seo.title_separator', '|');
        $this->migrator->add('sites_seo.blog_title_format', '{post_title} {separator} {post_category} {separator} {site_name}');
        $this->migrator->add('sites_seo.product_title_format', '{product_name} {separator} {product_category} {separator} {site_name}');
        $this->migrator->add('sites_seo.category_title_format', '{category_name} {separator} {site_name}');
        $this->migrator->add('sites_seo.search_title_format', 'Search results for "{search_term}" {separator} {site_name}');
        $this->migrator->add('sites_seo.author_title_format', 'Posts by {author_name} {separator} {site_name}');

        // Open Graph settings
        $this->migrator->add('sites_seo.og_type', 'website');
        $this->migrator->add('sites_seo.og_title', '{page_title}');
        $this->migrator->add('sites_seo.og_description', '{meta_description}');
        $this->migrator->add('sites_seo.og_image', 'sites/og-image.png');
        $this->migrator->add('sites_seo.og_site_name', '{site_name}');

        // Twitter Card settings
        $this->migrator->add('sites_seo.twitter_card_type', 'summary_large_image');
        $this->migrator->add('sites_seo.twitter_site', '@yourtwitterhandle');
        $this->migrator->add('sites_seo.twitter_creator', '@yourtwitterhandle');
        $this->migrator->add('sites_seo.twitter_title', '{page_title}');
        $this->migrator->add('sites_seo.twitter_description', '{meta_description}');
        $this->migrator->add('sites_seo.twitter_image', 'sites/twitter-image.png');

        // Schema.org settings
        $this->migrator->add('sites_seo.schema_type', 'Organization');
        $this->migrator->add('sites_seo.schema_name', '{site_name}');
        $this->migrator->add('sites_seo.schema_description', '{meta_description}');
        $this->migrator->add('sites_seo.schema_logo', 'sites/logo.png');

        // Additional settings
        $this->migrator->add('sites_seo.head_additional_meta', '');
        $this->migrator->add('sites_seo.verification_codes', [
            'google' => '',
            'bing' => '',
            'yandex' => '',
            'baidu' => '',
        ]);
        $this->migrator->add('sites_seo.robots_txt_content', "User-agent: *\nAllow: /\n\nSitemap: {site_url}/sitemap.xml");
        $this->migrator->add('sites_seo.sitemap_enabled', true);
        $this->migrator->add('sites_seo.sitemap_include_pages', true);
        $this->migrator->add('sites_seo.sitemap_include_posts', true);
        $this->migrator->add('sites_seo.sitemap_include_categories', true);
        $this->migrator->add('sites_seo.sitemap_include_tags', true);
    }
};
