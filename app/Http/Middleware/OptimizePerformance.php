<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizePerformance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply optimizations to HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        // Add performance headers
        $this->addPerformanceHeaders($response);

        // Add security headers
        $this->addSecurityHeaders($response);

        // Optimize HTML content
        if (config('app.env') === 'production') {
            $this->optimizeHtmlContent($response);
        }

        return $response;
    }

    /**
     * Check if response is HTML
     */
    private function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        return str_contains($contentType, 'text/html') || empty($contentType);
    }

    /**
     * Add performance-related headers
     */
    private function addPerformanceHeaders(Response $response): void
    {
        $headers = [
            // DNS prefetch for external resources
            'Link' => '</css>; rel=preload; as=style, </js>; rel=preload; as=script',

            // Cache control for static assets
            'Cache-Control' => 'public, max-age=31536000, immutable',

            // Compression
            'Vary' => 'Accept-Encoding',

            // Performance hints
            'X-DNS-Prefetch-Control' => 'on',
        ];

        foreach ($headers as $key => $value) {
            if (!$response->headers->has($key)) {
                $response->headers->set($key, $value);
            }
        }
    }

    /**
     * Add security headers for performance
     */
    private function addSecurityHeaders(Response $response): void
    {
        $headers = [
            // Security headers that also improve performance
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',

            // HSTS for HTTPS performance
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
        ];

        foreach ($headers as $key => $value) {
            if (!$response->headers->has($key)) {
                $response->headers->set($key, $value);
            }
        }
    }

    /**
     * Optimize HTML content for production
     */
    private function optimizeHtmlContent(Response $response): void
    {
        $content = $response->getContent();

        if (!is_string($content)) {
            return;
        }

        // Minify HTML by removing unnecessary whitespace
        $content = preg_replace('/\s+/', ' ', $content);
        $content = preg_replace('/>\s+</', '><', $content);

        // Remove HTML comments (but keep conditional comments)
        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);

        // Optimize inline CSS and JS
        $content = $this->optimizeInlineAssets($content);

        $response->setContent($content);
    }

    /**
     * Optimize inline CSS and JavaScript
     */
    private function optimizeInlineAssets(string $content): string
    {
        // Minify inline CSS
        $content = preg_replace_callback(
            '/<style[^>]*>(.*?)<\/style>/is',
            function ($matches) {
                $css = $matches[1];
                // Remove comments
                $css = preg_replace('/\/\*.*?\*\//s', '', $css);
                // Remove unnecessary whitespace
                $css = preg_replace('/\s+/', ' ', $css);
                $css = str_replace(['; ', ' {', '{ ', ' }', '} '], [';', '{', '{', '}', '}'], $css);
                return '<style' . substr($matches[0], 6, strpos($matches[0], '>') - 6) . '>' . trim($css) . '</style>';
            },
            $content
        );

        // Minify inline JavaScript
        $content = preg_replace_callback(
            '/<script[^>]*>(.*?)<\/script>/is',
            function ($matches) {
                $js = $matches[1];
                // Basic JS minification (remove comments and extra whitespace)
                $js = preg_replace('/\/\*.*?\*\//s', '', $js);
                $js = preg_replace('/\/\/.*$/m', '', $js);
                $js = preg_replace('/\s+/', ' ', $js);
                return '<script' . substr($matches[0], 7, strpos($matches[0], '>') - 7) . '>' . trim($js) . '</script>';
            },
            $content
        );

        return $content;
    }
}
