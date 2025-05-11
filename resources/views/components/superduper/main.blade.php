<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="scroll-smooth">

<head>
    @php
        $favicon = $generalSettings->site_favicon;
        $brandLogo = $generalSettings->brand_logo;
        $siteName = $generalSettings->brand_name ?? $siteSettings->name ?? config('app.name', 'SuperDuper Starter Kit');

        $separator = $seoSettings->title_separator ?? '|';
        $page_type = $page_type ?? 'standard';

        $_main_variables = [
            '{site_name}' => $siteName,
            '{separator}' => $separator,
        ];

        switch ($page_type) {
            case 'blog_post':
                $titleFormat = $seoSettings->blog_title_format ?? '{post_title} {separator} {site_name}';
                $variables = array_merge($_main_variables, [
                    '{post_title}' => $postTitle ?? '',
                    '{post_category}' => $postCategory ?? '',
                    '{author_name}' => $authorName ?? '',
                    '{publish_date}' => isset($publishDate) ? $publishDate->format('Y') : '',
                ]);
                break;

            case 'product':
                $titleFormat = $seoSettings->product_title_format ?? '{product_name} {separator} {product_category} {separator} {site_name}';
                $variables = array_merge($_main_variables, [
                    '{product_name}' => $productName ?? '',
                    '{product_category}' => $productCategory ?? '',
                    '{product_brand}' => $productBrand ?? '',
                    '{price}' => $productPrice ?? '',
                ]);
                break;

            case 'category':
                $titleFormat = $seoSettings->category_title_format ?? '{category_name} {separator} {site_name}';
                $variables = array_merge($_main_variables, [
                    '{category_name}' => $categoryName ?? '',
                    '{parent_category}' => $parentCategory ?? '',
                    '{products_count}' => $productsCount ?? '',
                ]);
                break;

            case 'search':
                $titleFormat = $seoSettings->search_title_format ?? 'Search results for "{search_term}" {separator} {site_name}';
                $variables = array_merge($_main_variables, [
                    '{search_term}' => $searchTerm ?? '',
                    '{results_count}' => $resultsCount ?? '',
                ]);
                break;

            case 'author':
                $titleFormat = $seoSettings->author_title_format ?? 'Posts by {author_name} {separator} {site_name}';
                $variables = array_merge($_main_variables, [
                    '{author_name}' => $authorName ?? '',
                    '{post_count}' => $postCount ?? '',
                ]);
                break;

            default:
                $titleFormat = $seoSettings->meta_title_format ?? '{page_title} {separator} {site_name}';
                $variables = array_merge($_main_variables, [
                    '{page_title}' => $pageTitle ?? '',
                ]);
        }

        // Process the format by replacing placeholders
        $title = str_replace(
            array_keys($variables),
            array_values($variables),
            $titleFormat
        );

        // Clean up the title (remove double separators, eliminate leading/trailing separators)
        $title = preg_replace('/\s*' . preg_quote($separator) . '\s*' . preg_quote($separator) . '\s*/', " $separator ", $title);
        $title = trim($title);
        $title = trim($title, " $separator");

        // Fallback if empty
        if (empty(trim($title))) {
            $title = $siteName;
        }
    @endphp

    @if (!$generalSettings->search_engine_indexing)
        <meta name="robots" content="noindex">
    @endif

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="{{ $siteName }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $seoSettings->canonical_url ?? url()->current() }}" />

    <!-- SEO Meta Tags -->
    <meta name="keywords"
        content="{{ $metaKeywords ?? $seoSettings->meta_keywords ?? 'starter kit, development, templates, components, web solutions, digital transformation' }}" />
    <meta name="description"
        content="{{ $pageDescription ?? $seoSettings->meta_description ?? $siteSettings->description ?? 'SuperDuper Starter Kit provides everything you need to jumpstart your web project with pre-built components, layouts, and tools that enhance development efficiency and productivity.' }}">

    <!-- Mobile Optimization Meta Tags -->
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#512B0F">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Schema.org markup (Google) -->
    <meta itemprop="name" content="{{ $title }}" />
    <meta itemprop="url" content="{{ url()->current() }}">
    <meta itemprop="description"
        content="{{ $pageDescription ?? $seoSettings->meta_description ?? $siteSettings->description }}">
    <meta itemprop="thumbnailUrl"
        content="{{ $brandLogo ? Storage::url($brandLogo) : asset('storage/images/logo.png') }}">
    <meta itemprop="image"
        content="{{ $seoSettings->schema_logo ?? ($brandLogo ? Storage::url($brandLogo) : asset('storage/images/logo.png')) }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $seoSettings->twitter_card_type ?? 'summary' }}">
    <meta name="twitter:site" content="{{ $seoSettings->twitter_site ?? '@superduperkit' }}" />
    <meta name="twitter:creator" content="{{ $seoSettings->twitter_creator ?? '@superduperkit' }}" />
    <meta name="twitter:title" content="{{ $seoSettings->twitter_title ?? $title }}">
    <meta name="twitter:description"
        content="{{ $seoSettings->twitter_description ?? $pageDescription ?? $seoSettings->meta_description }}" />
    <meta name="twitter:image"
        content="{{ $seoSettings->twitter_image ?? ($brandLogo ? Storage::url($brandLogo) : asset('storage/images/logo.png')) }}">
    <meta name="twitter:url" content="{{ url()->current() }}">

    <!-- Open Graph (Facebook, LinkedIn) -->
    <meta property="og:site_name" content="{{ $seoSettings->og_site_name ?? $siteName }}" />
    <meta property="og:title" content="{{ $seoSettings->og_title ?? $title }}" />
    <meta property="og:type" content="{{ $seoSettings->og_type ?? 'website' }}" />
    <meta property="og:description"
        content="{{ $seoSettings->og_description ?? $pageDescription ?? $seoSettings->meta_description }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image"
        content="{{ $seoSettings->og_image ?? ($brandLogo ? Storage::url($brandLogo) : asset('storage/images/logo.png')) }}" />
    <meta property="og:image:width" content="1500">
    <meta property="og:image:height" content="1500">
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:alt" content="{{ $siteName }}" />

    <!-- Verification codes -->
    @if(!empty($seoSettings->verification_codes))
        @foreach($seoSettings->verification_codes as $verificationCode)
            {!! $verificationCode !!}
        @endforeach
    @endif

    <!-- Additional meta tags -->
    @if($seoSettings->head_additional_meta)
        {!! $seoSettings->head_additional_meta !!}
    @endif

    @yield('meta')

    <title>{{ $title }}</title>

    <!-- Favicon from settings -->
    <link rel="shortcut icon" href="{{ $favicon ? Storage::url($favicon) : asset('superduper/img/favicon.png') }}"
        type="image/x-icon">

    <!-- Theme CSS via Vite -->
    @vite([
        'resources/css/app.css',
    ])

    <!-- Icon Font -->
    <link rel="preload" href="{{ asset('superduper/fonts/iconfonts/font-awesome/stylesheet.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('superduper/fonts/iconfonts/font-awesome/stylesheet.css') }}">
    </noscript>
    <!-- Site font -->
    <link rel="stylesheet" href="{{ asset('superduper/fonts/webfonts/public-sans/stylesheet.css') }}" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('superduper/css/vendors/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('superduper/css/vendors/jos.css') }}" />

    <link rel="stylesheet" href="{{ asset('superduper/css/style.min.css') }}" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('css')

    <!-- Custom CSS -->
    @if(isset($scriptSettings->custom_css))
        <style>
            {!! $scriptSettings->custom_css !!}
        </style>
    @endif

    @livewireStyles

    <!-- Header scripts -->
    @if(isset($scriptSettings->header_scripts))
        {!! $scriptSettings->header_scripts !!}
    @endif

    <!--  structured data (JSON-LD) -->
    <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "{{ $seoSettings->schema_type ?? '' }}",
        "name": "{{ $seoSettings->schema_name ?? $siteName }}",
        "url": "{{ url('/') }}",
        "logo": "{{ $seoSettings->schema_logo ?? ($brandLogo ? Storage::url($brandLogo) : asset('superduper/img/favicon.png')) }}",
        "description": "{{ $seoSettings->schema_description ?? $siteSettings->description ?? 'SuperDuper Starter Kit provides everything you need to jumpstart your web project with pre-built components, layouts, and tools that enhance development efficiency and productivity.' }}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "{{ explode(',', $siteSettings->company_address)[0] ?? '' }}",
            "addressRegion": "{{ explode(',', $siteSettings->company_address)[1] ?? '' }}",
            "addressCountry": "{{ explode(',', $siteSettings->company_address)[2] ?? 'ID' }}"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "{{ $siteSettings->company_phone ?? '' }}",
            "contactType": "customer service",
            "email": "{{ $siteSettings->company_email ?? '' }}"
        }
        }
    </script>
</head>

<body>
    <!-- Body start scripts -->
    @if(isset($scriptSettings->body_start_scripts))
        {!! $scriptSettings->body_start_scripts !!}
    @endif

    @if(isset($siteSettings->is_maintenance) && $siteSettings->is_maintenance)
        <div class="maintenance-mode">
            <div class="container">
                <h1>Site Under Maintenance</h1>
                <p>We're currently performing maintenance. Please check back soon.</p>
            </div>
        </div>
    @else
        <x-superduper.header />

        <main>
            {{ $slot }}
        </main>

        <x-superduper.footer />

        <!-- Cookie Consent -->
        @if(isset($scriptSettings->cookie_consent_enabled) && $scriptSettings->cookie_consent_enabled)
            <div class="cookie-consent js-cookie-consent" style="display: none;">
                <div class="container">
                    <span class="cookie-consent__message">
                        {!! $scriptSettings->cookie_consent_text ?? 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.' !!}
                        @if(isset($scriptSettings->cookie_consent_policy_url) && $scriptSettings->cookie_consent_policy_url)
                            <a href="{{ $scriptSettings->cookie_consent_policy_url }}">Learn more</a>
                        @endif
                    </span>
                    <button class="cookie-consent__agree">
                        {{ $scriptSettings->cookie_consent_button_text ?? 'Accept' }}
                    </button>
                </div>
            </div>
        @endif
    @endif

    <!-- Vite compiled JS -->
    @vite([
        'resources/js/app.js',
    ])

    <!--Vendor js-->
    <script src="{{ asset('superduper/js/vendors/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('superduper/js/vendors/fslightbox.js') }}"></script>
    <script src="{{ asset('superduper/js/vendors/jos.min.js') }}"></script>

    <script src="{{ asset('superduper/js/main.js') }}"></script>

    @livewireScripts

    <!-- Custom JS -->
    @if(isset($scriptSettings->custom_js))
        <script>
            {!! $scriptSettings->custom_js !!}
        </script>
    @endif

    <!-- Footer scripts -->
    @if(isset($scriptSettings->footer_scripts))
        {!! $scriptSettings->footer_scripts !!}
    @endif

    <!-- Body end scripts -->
    @if(isset($scriptSettings->body_end_scripts))
        {!! $scriptSettings->body_end_scripts !!}
    @endif

    @stack('js')
</body>

</html>
