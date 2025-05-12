<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Test the Security Headers
     * - SecurityHeaders.com: Visit https://securityheaders.com and enter your website URL
     * - Mozilla Observatory: Visit https://observatory.mozilla.org for a comprehensive security assessment
     *
     * Manual Steps:
     * 1. Open your browser's developer tools (F12)
     * 2. Go to the Network tab
     * 3. Reload your page
     * 4. Click on any HTML document response
     * 5. Look at the Response Headers section to verify your security headers are present
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Content Security Policy - Controls which resources the browser is allowed to load
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "img-src 'self' data:; " .
            "font-src 'self' data: https://cdnjs.cloudflare.com; " .
            "connect-src 'self'; " .
            "media-src 'self'; " .
            "object-src 'none'; " .
            "frame-src 'self';"
        );

        // XSS Protection - Enables XSS filtering built into browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Frame Options - Prevents your site from being framed by other sites (clickjacking protection)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Content Type Options - Prevents browsers from MIME-sniffing a response from declared content-type
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer Policy - Controls how much referrer information should be included with requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy - Controls which browser features can be used (formerly Feature Policy)
        $response->headers->set('Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=()'
        );

        // HSTS - Forces browsers to use HTTPS for a specified time period
        // Only enable in production and if you have HTTPS configured
        if (app()->environment('production') && request()->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
