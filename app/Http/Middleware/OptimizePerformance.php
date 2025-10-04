<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizePerformance
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        $this->addSecurityHeaders($response);

        $this->addSafePerformanceHeaders($response);

        return $response;
    }

    private function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        return str_contains($contentType, 'text/html') || empty($contentType);
    }

    /**
     * Add security headers
     */
    private function addSecurityHeaders(Response $response): void
    {
        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
        ];

        if (app()->environment('production') && request()->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $key => $value) {
            if (!$response->headers->has($key)) {
                $response->headers->set($key, $value);
            }
        }
    }

    /**
     * Add safe performance headers
     */
    private function addSafePerformanceHeaders(Response $response): void
    {
        $headers = [
            // Remove Cache-Control - let Laravel handle it
            'Vary' => 'Accept-Encoding',
            'X-DNS-Prefetch-Control' => 'on',
        ];

        foreach ($headers as $key => $value) {
            if (!$response->headers->has($key)) {
                $response->headers->set($key, $value);
            }
        }
    }
}