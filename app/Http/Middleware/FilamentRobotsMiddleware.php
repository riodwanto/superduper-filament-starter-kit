<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Settings\GeneralSettings;

class FilamentRobotsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->headers->has('Content-Type') && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $settings = app(GeneralSettings::class);

            $content = $response->getContent();
            $robotsTag = $settings->search_engine_indexing
                ? '<meta name="robots" content="index, follow">'
                : '<meta name="robots" content="noindex, nofollow">';

            $content = str_replace(
                '<meta name="viewport"',
                $robotsTag . "\n" . '    <meta name="viewport"',
                $content
            );

            $response->setContent($content);
        }

        return $response;
    }
}
