{{-- 
    SuperDuper Filament Starter Kit - Main Layout
    
    This is the main layout wrapper for all pages. It handles:
    - SEO meta tags generation
    - Dynamic title formatting based on page types
    - Schema.org structured data
    - Asset loading (CSS/JS)
    - Cookie consent
    - Maintenance mode
--}}

@props([
    // === Core Page Props ===
    'pageType' => 'standard',          // Page type: standard|blog_post|product|category|search|author
    'pageTitle' => null,                // Main page title
    'pageDescription' => null,          // Page meta description
    'metaKeywords' => null,             // SEO keywords

    // === Blog Post Props ===
    'postTitle' => null,                // Blog post title
    'postCategory' => null,             // Blog post category
    'authorName' => null,               // Author name (used in blog_post and author types)
    'publishDate' => null,              // Publication date

    // === Product Props ===
    'productName' => null,              // Product name
    'productCategory' => null,          // Product category
    'productBrand' => null,             // Product brand
    'productPrice' => null,             // Product price

    // === Category Props ===
    'categoryName' => null,             // Category name
    'parentCategory' => null,           // Parent category
    'productsCount' => null,            // Number of products in category

    // === Search Props ===
    'searchTerm' => null,               // Search query term
    'resultsCount' => null,             // Number of search results

    // === Author Props ===
    'postCount' => null,                // Number of posts by author

    // === SEO Override Props ===
    'canonicalUrl' => null,             // Custom canonical URL
    'ogImage' => null,                  // Custom Open Graph image
    'twitterImage' => null,             // Custom Twitter Card image
    'noIndex' => false,                 // Set to true to prevent indexing
])

@php
    //==========================================================================
    // INITIALIZATION SECTION
    //==========================================================================
    $page_type = $pageType;
    $favicon = $generalSettings->site_favicon ?? null;
    $brandLogo = $generalSettings->brand_logo ?? null;
    $siteName = $generalSettings->brand_name 
        ?? $siteSettings->name 
        ?? config('app.name', 'SuperDuper Starter Kit');
    
    //==========================================================================
    // TITLE GENERATION SECTION
    //==========================================================================
    $separator = $seoSettings->title_separator ?? '|';
    
    // Base variables available to all title formats
    $_main_variables = [
        '{site_name}' => $siteName,
        '{separator}' => $separator,
    ];
    
    // Define title formats and variables based on page type
    $titleConfig = match($page_type) {
        'blog_post' => [
            'format' => $seoSettings->blog_title_format ?? '{post_title} {separator} {site_name}',
            'variables' => [
                '{post_title}' => $postTitle ?? '',
                '{post_category}' => $postCategory ?? '',
                '{author_name}' => $authorName ?? '',
                '{publish_date}' => $publishDate ? $publishDate->format('Y') : '',
            ]
        ],
        'product' => [
            'format' => $seoSettings->product_title_format ?? '{product_name} {separator} {product_category} {separator} {site_name}',
            'variables' => [
                '{product_name}' => $productName ?? '',
                '{product_category}' => $productCategory ?? '',
                '{product_brand}' => $productBrand ?? '',
                '{price}' => $productPrice ?? '',
            ]
        ],
        'category' => [
            'format' => $seoSettings->category_title_format ?? '{category_name} {separator} {site_name}',
            'variables' => [
                '{category_name}' => $categoryName ?? '',
                '{parent_category}' => $parentCategory ?? '',
                '{products_count}' => $productsCount ?? '',
            ]
        ],
        'search' => [
            'format' => $seoSettings->search_title_format ?? 'Search results for "{search_term}" {separator} {site_name}',
            'variables' => [
                '{search_term}' => $searchTerm ?? '',
                '{results_count}' => $resultsCount ?? '',
            ]
        ],
        'author' => [
            'format' => $seoSettings->author_title_format ?? 'Posts by {author_name} {separator} {site_name}',
            'variables' => [
                '{author_name}' => $authorName ?? '',
                '{post_count}' => $postCount ?? '',
            ]
        ],
        default => [
            'format' => $seoSettings->meta_title_format ?? '{page_title} {separator} {site_name}',
            'variables' => [
                '{page_title}' => $pageTitle ?? '',
            ]
        ]
    };
    
    // Merge variables and generate title
    $titleFormat = $titleConfig['format'];
    $variables = array_merge($_main_variables, $titleConfig['variables']);
    
    // Process the title
    $title = str_replace(
        array_keys($variables),
        array_values($variables),
        $titleFormat
    );
    
    // Clean up title (remove duplicate separators and trim)
    $separatorPattern = '/\s*' . preg_quote($separator, '/') . '\s*' . preg_quote($separator, '/') . '\s*/';
    $title = preg_replace($separatorPattern, " $separator ", $title);
    $title = trim($title, " $separator");
    
    // Fallback to site name if title is empty
    $title = !empty(trim($title)) ? $title : $siteName;
    
    //==========================================================================
    // META CONTENT PREPARATION
    //==========================================================================
    $metaDescription = $pageDescription 
        ?? $seoSettings->meta_description 
        ?? $siteSettings->description 
        ?? '';
    
    $metaKeywordsContent = $metaKeywords 
        ?? $seoSettings->meta_keywords 
        ?? '';
    
    $canonicalUrlFinal = $canonicalUrl 
        ?? $seoSettings->canonical_url 
        ?? url()->current();
    
    //==========================================================================
    // IMAGE URLS PREPARATION
    //==========================================================================
    $defaultImage = $brandLogo 
        ? Storage::url($brandLogo) 
        : asset('storage/images/logo.png');
    
    $ogImageUrl = $ogImage 
        ?? $seoSettings->og_image 
        ?? $defaultImage;
    
    $twitterImageUrl = $twitterImage 
        ?? $seoSettings->twitter_image 
        ?? $defaultImage;
    
    $faviconUrl = $favicon 
        ? Storage::url($favicon) 
        : asset('superduper/img/favicon.png');
    
    //==========================================================================
    // SCHEMA.ORG DATA PREPARATION
    //==========================================================================
    $schemaLogo = $seoSettings->schema_logo ?? $defaultImage;
    $schemaType = $seoSettings->schema_type ?? 'Organization';
    $schemaName = $seoSettings->schema_name ?? $siteName;
    $schemaDescription = $seoSettings->schema_description 
        ?? $siteSettings->description 
        ?? 'SuperDuper Starter Kit provides everything you need to jumpstart your web project with pre-built components, layouts, and tools that enhance development efficiency and productivity.';
    
    // Parse company address for schema
    $addressParts = $siteSettings->company_address 
        ? explode(',', $siteSettings->company_address) 
        : ['', '', 'ID'];
    
    $schemaAddress = [
        'locality' => trim($addressParts[0] ?? ''),
        'region' => trim($addressParts[1] ?? ''),
        'country' => trim($addressParts[2] ?? 'ID'),
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ===== ROBOTS & INDEXING ===== --}}
    @php
        $indexing = ($noIndex || !($seoSettings->robots_indexing ?? true)) ? 'noindex' : 'index';
        $following = ($seoSettings->robots_following ?? true) ? 'follow' : 'nofollow';
    @endphp
    <meta name="robots" content="{{ $indexing }}, {{ $following }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="{{ $siteName }}">

    {{-- ===== SEO META TAGS ===== --}}
    <link rel="canonical" href="{{ $canonicalUrlFinal }}" />
    <meta name="keywords" content="{{ $metaKeywordsContent }}" />
    <meta name="description" content="{{ $metaDescription }}">

    {{-- ===== MOBILE OPTIMIZATION ===== --}}
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#512B0F">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    {{-- ===== SCHEMA.ORG MARKUP (Google) ===== --}}
    <meta itemprop="name" content="{{ $title }}" />
    <meta itemprop="url" content="{{ url()->current() }}">
    <meta itemprop="description" content="{{ $metaDescription }}">
    <meta itemprop="thumbnailUrl" content="{{ $defaultImage }}">
    <meta itemprop="image" content="{{ $schemaLogo }}">

    {{-- ===== TWITTER CARD ===== --}}
    <meta name="twitter:card" content="{{ $seoSettings->twitter_card_type ?? 'summary' }}">
    <meta name="twitter:site" content="{{ $seoSettings->twitter_site ?? '@superduperkit' }}" />
    <meta name="twitter:creator" content="{{ $seoSettings->twitter_creator ?? '@superduperkit' }}" />
    <meta name="twitter:title" content="{{ $seoSettings->twitter_title ?? $title }}">
    <meta name="twitter:description" content="{{ $seoSettings->twitter_description ?? $metaDescription }}" />
    <meta name="twitter:image" content="{{ $twitterImageUrl }}">
    <meta name="twitter:url" content="{{ url()->current() }}">

    {{-- ===== OPEN GRAPH (Facebook, LinkedIn) ===== --}}
    <meta property="og:site_name" content="{{ $seoSettings->og_site_name ?? $siteName }}" />
    <meta property="og:title" content="{{ $seoSettings->og_title ?? $title }}" />
    <meta property="og:type" content="{{ $seoSettings->og_type ?? 'website' }}" />
    <meta property="og:description" content="{{ $seoSettings->og_description ?? $metaDescription }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ $ogImageUrl }}" />
    <meta property="og:image:width" content="1500">
    <meta property="og:image:height" content="1500">
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:alt" content="{{ $siteName }}" />

    {{-- ===== VERIFICATION CODES ===== --}}
    @if(!empty($seoSettings->verification_codes))
        @foreach($seoSettings->verification_codes as $verificationCode)
            {!! $verificationCode !!}
        @endforeach
    @endif

    {{-- ===== ADDITIONAL META TAGS ===== --}}
    @if($seoSettings->head_additional_meta)
        {!! $seoSettings->head_additional_meta !!}
    @endif

    {{-- ===== YIELD FOR ADDITIONAL META ===== --}}
    @yield('meta')

    {{-- ===== PAGE TITLE ===== --}}
    <title>{{ $title }}</title>

    {{-- ===== FAVICON ===== --}}
    <link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">

    {{-- ===== STYLES SECTION ===== --}}
    {{-- Vite Compiled CSS --}}
    @vite(['resources/css/app.css'])

    {{-- Icon Font (Async Loading) --}}
    <link rel="preload" 
          href="{{ asset('superduper/fonts/iconfonts/font-awesome/stylesheet.css') }}" 
          as="style" 
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('superduper/fonts/iconfonts/font-awesome/stylesheet.css') }}">
    </noscript>

    {{-- Site Font --}}
    <link rel="stylesheet" href="{{ asset('superduper/fonts/webfonts/public-sans/stylesheet.css') }}" />

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="{{ asset('superduper/css/vendors/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('superduper/css/vendors/jos.css') }}" />

    {{-- Main Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('superduper/css/style.min.css') }}" />

    {{-- Alpine.js Cloak --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- Stack for Additional CSS --}}
    @stack('css')

    {{-- Custom CSS from Settings --}}
    @if(isset($scriptSettings->custom_css))
        <style>
            {!! $scriptSettings->custom_css !!}
        </style>
    @endif

    {{-- Livewire Styles --}}
    @livewireStyles

    {{-- Header Scripts from Settings --}}
    @if(isset($scriptSettings->header_scripts))
        {!! $scriptSettings->header_scripts !!}
    @endif

    {{-- ===== STRUCTURED DATA (JSON-LD) ===== --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => $schemaType,
        'name' => $schemaName,
        'url' => url('/'),
        'logo' => $schemaLogo,
        'description' => $schemaDescription,
    
        // only add address if available
        'address' => $siteSettings->company_address ? [
            '@type' => 'PostalAddress',
            'addressLocality' => $schemaAddress['locality'] ?? '',
            'addressRegion' => $schemaAddress['region'] ?? '',
            'addressCountry' => $schemaAddress['country'] ?? '',
        ] : null,
    
        // only add contactPoint if available
        'contactPoint' => ($siteSettings->company_phone || $siteSettings->company_email) ? [
            '@type' => 'ContactPoint',
            'telephone' => $siteSettings->company_phone ?? '',
            'email' => $siteSettings->company_email ?? '',
            'contactType' => 'customer service',
        ] : null,
    ], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) !!}
    </script>
        
</head>

<body>
    {{-- ===== BODY START SCRIPTS ===== --}}
    @if(isset($scriptSettings->body_start_scripts))
        {!! $scriptSettings->body_start_scripts !!}
    @endif

    {{-- ===== MAINTENANCE MODE CHECK ===== --}}
    @if(isset($siteSettings->is_maintenance) && $siteSettings->is_maintenance)
        <div class="maintenance-mode">
            <div class="container">
                <h1>Site Under Maintenance</h1>
                <p>We're currently performing maintenance. Please check back soon.</p>
            </div>
        </div>
    @else
        {{-- ===== HEADER COMPONENT ===== --}}
        <x-superduper.header />

        {{-- ===== MAIN CONTENT AREA ===== --}}
        <main id="main-content">
            {{ $slot }}
        </main>

        {{-- ===== FOOTER COMPONENT ===== --}}
        <x-superduper.footer />

        {{-- ===== COOKIE CONSENT ===== --}}
        <x-superduper.components.cookie-consent :siteSettings="$siteSettings" />
    @endif

    {{-- ===== SCRIPTS SECTION ===== --}}
    {{-- Vite Compiled JS --}}
    @vite(['resources/js/app.js'])

    {{-- Vendor JavaScript --}}
    <script src="{{ asset('superduper/js/vendors/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('superduper/js/vendors/fslightbox.js') }}"></script>
    <script src="{{ asset('superduper/js/vendors/jos.min.js') }}"></script>

    {{-- Main Theme JavaScript --}}
    <script src="{{ asset('superduper/js/main.js') }}"></script>

    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Custom JavaScript from Settings --}}
    @if(isset($scriptSettings->custom_js))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                {!! $scriptSettings->custom_js !!}
            });
        </script>
    @endif

    {{-- Footer Scripts from Settings --}}
    @if(isset($scriptSettings->footer_scripts))
        {!! $scriptSettings->footer_scripts !!}
    @endif

    {{-- Body End Scripts from Settings --}}
    @if(isset($scriptSettings->body_end_scripts))
        {!! $scriptSettings->body_end_scripts !!}
    @endif

    {{-- Stack for Additional JavaScript --}}
    @stack('js')
</body>

</html>