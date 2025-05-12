<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SecurityLogger;

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

        if (app()->environment('local') && env('CSP_ENABLED', true) === false) {
            return $this->addBasicSecurityHeaders($response);
        }

        if (app()->environment('production')) {
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
                   "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data:; " .
                   "font-src 'self' data: https://cdnjs.cloudflare.com; " .
                   "connect-src 'self'; " .
                   "media-src 'self'; " .
                   "object-src 'none'; " .
                   "frame-src 'self';";
        } else {
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* http://[::1]:* https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
                   "style-src 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:* http://[::1]:* https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
                   "img-src 'self' data:; " .
                   "font-src 'self' data: https://cdnjs.cloudflare.com; " .
                   "connect-src 'self' ws://localhost:* ws://127.0.0.1:* ws://[::1]:* http://localhost:* http://127.0.0.1:* http://[::1]:*; " .
                   "media-src 'self'; " .
                   "object-src 'none'; " .
                   "frame-src 'self';";
        }

        // Check for CSP Report-Only header in request (for debugging)
        if ($request->header('X-Enable-CSP-Report-Only') === 'true') {
            $response->headers->set('Content-Security-Policy-Report-Only', $csp);

            SecurityLogger::logSuspiciousActivity('CSP Report-Only mode enabled', [
                'enabled_by' => 'request header',
                'csp' => $csp
            ]);
        } else {
            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $this->addBasicSecurityHeaders($response);
    }

    /**
     * Add basic security headers that are always included, even when CSP is disabled
     *
     * @param Response $response
     * @return Response
     */
    protected function addBasicSecurityHeaders(Response $response): Response
    {
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

    /**
     * Log any CSP violations reported by the browser
     *
     * @param Request $request
     * @return void
     */
    protected function logCspViolations(Request $request): void
    {
        // Check if this is a CSP violation report
        if ($request->is('csp-report') && $request->isMethod('POST')) {
            $report = json_decode($request->getContent(), true);

            if (isset($report['csp-report'])) {
                SecurityLogger::logSecurityHeaderViolation('CSP', json_encode($report['csp-report']));
            }
        }
    }
}
