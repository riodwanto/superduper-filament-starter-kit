@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'url' => null,
    'type' => 'website',
    'author' => null,
    'publishedTime' => null,
    'modifiedTime' => null,
    'section' => null,
    'tags' => [],
    'noindex' => false,
    'canonical' => null
])

@php
    $generalSettings = app(\App\Settings\GeneralSettings::class);
    $siteSettings = app(\App\Settings\SiteSettings::class);

    $siteTitle = $generalSettings->brand_name ?? $siteSettings->name ?? config('app.name', 'SuperDuper');
    $siteDescription = $siteSettings->description ?? 'A comprehensive Laravel Filament starter kit for rapid application development';
    $siteUrl = config('app.url');

    $pageTitle = $title ? $title . ' - ' . $siteTitle : $siteTitle;
    $pageDescription = $description ?? $siteDescription;
    $pageUrl = $url ?? request()->url();
    $pageImage = $image ?? ($siteSettings->og_image ?? null);
    $pageKeywords = is_array($keywords) ? implode(', ', $keywords) : $keywords;

    // Ensure absolute URL for image
    if ($pageImage && !str_starts_with($pageImage, 'http')) {
        $pageImage = $siteUrl . Storage::url($pageImage);
    }
@endphp

<!-- Primary Meta Tags -->
<title>{{ $pageTitle }}</title>
<meta name="title" content="{{ $pageTitle }}">
<meta name="description" content="{{ $pageDescription }}">
@if($pageKeywords)
    <meta name="keywords" content="{{ $pageKeywords }}">
@endif
@if($author)
    <meta name="author" content="{{ $author }}">
@endif

<!-- Canonical URL -->
<link rel="canonical" href="{{ $canonical ?? $pageUrl }}">

<!-- Robots -->
@if($noindex || !($generalSettings->search_engine_indexing ?? false))
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow">
@endif

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:site_name" content="{{ $siteTitle }}">
@if($pageImage)
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $title ?? $siteTitle }}">
@endif
@if($publishedTime)
    <meta property="article:published_time" content="{{ $publishedTime }}">
@endif
@if($modifiedTime)
    <meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif
@if($author)
    <meta property="article:author" content="{{ $author }}">
@endif
@if($section)
    <meta property="article:section" content="{{ $section }}">
@endif
@foreach($tags as $tag)
    <meta property="article:tag" content="{{ $tag }}">
@endforeach

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $pageUrl }}">
<meta property="twitter:title" content="{{ $pageTitle }}">
<meta property="twitter:description" content="{{ $pageDescription }}">
@if($pageImage)
    <meta property="twitter:image" content="{{ $pageImage }}">
@endif
@if($siteSettings->twitter_handle ?? null)
    <meta property="twitter:site" content="@{{ $siteSettings->twitter_handle }}">
@endif

<!-- Additional SEO Meta -->
<meta name="format-detection" content="telephone=no">
<meta name="msapplication-TileColor" content="#2D2B8D">
<meta name="theme-color" content="#2D2B8D">

<!-- Structured Data -->
@if($type === 'article' && $publishedTime)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "{{ $title }}",
    "description": "{{ $pageDescription }}",
    "url": "{{ $pageUrl }}",
    @if($pageImage)
    "image": {
        "@type": "ImageObject",
        "url": "{{ $pageImage }}",
        "width": 1200,
        "height": 630
    },
    @endif
    "datePublished": "{{ $publishedTime }}",
    @if($modifiedTime)
    "dateModified": "{{ $modifiedTime }}",
    @endif
    "author": {
        "@type": "Person",
        "name": "{{ $author ?? $siteTitle }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ $siteTitle }}",
        "url": "{{ $siteUrl }}"
        @if($siteSettings->logo)
        ,"logo": {
            "@type": "ImageObject",
            "url": "{{ $siteUrl . Storage::url($siteSettings->logo) }}"
        }
        @endif
    }
    @if(!empty($tags))
    ,"keywords": [
        @foreach($tags as $index => $tag)
            "{{ $tag }}"@if($index < count($tags) - 1),@endif
        @endforeach
    ]
    @endif
}
</script>
@else
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ $siteTitle }}",
    "url": "{{ $siteUrl }}",
    "description": "{{ $siteDescription }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ $siteUrl }}/blog?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>
@endif
