<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- SEO Meta Tags -->
        @if(isset($seoData))
            <x-seo.meta
                :title="$seoData['title'] ?? null"
                :description="$seoData['description'] ?? null"
                :keywords="$seoData['keywords'] ?? null"
                :image="$seoData['image'] ?? null"
                :url="$seoData['url'] ?? null"
                :type="$seoData['type'] ?? 'website'"
                :author="$seoData['author'] ?? null"
                :published-time="$seoData['publishedTime'] ?? null"
                :modified-time="$seoData['modifiedTime'] ?? null"
                :section="$seoData['section'] ?? null"
                :tags="$seoData['tags'] ?? []"
                :noindex="$seoData['noindex'] ?? false"
                :canonical="$seoData['canonical'] ?? null"
            />
        @else
            <x-seo.meta />
        @endif

        <!-- Accessibility and Performance -->
        <meta name="color-scheme" content="light dark">

        <style>
            [x-cloak] {
                display: none !important;
            }

            /* Skip to main content link */
            .skip-link {
                position: absolute;
                top: -40px;
                left: 6px;
                background: #000;
                color: #fff;
                padding: 8px;
                text-decoration: none;
                z-index: 1000;
                border-radius: 4px;
            }

            .skip-link:focus {
                top: 6px;
            }

            /* Focus indicators */
            .focus-visible:focus-visible {
                outline: 2px solid #2D2B8D;
                outline-offset: 2px;
            }

            /* Reduced motion support */
            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }

                .scroll-smooth {
                    scroll-behavior: auto !important;
                }
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')

        @stack('styles')
    </head>

    <body class="antialiased">
        <!-- Skip to main content link for screen readers -->
        <a href="#main-content" class="skip-link">Skip to main content</a>

        <div id="app">
            {{ $slot }}
        </div>

        <!-- Screen reader announcements -->
        <div aria-live="polite" aria-atomic="true" class="sr-only" id="announcements"></div>

        @livewire('notifications')

        @filamentScripts
        @vite('resources/js/app.js')

        @stack('scripts')
    </body>
</html>
