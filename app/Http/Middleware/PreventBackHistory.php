<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Attach strict no-cache headers so auth-related pages are never reused from browser history.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $existingCacheControl = strtolower((string) $response->headers->get('Cache-Control', ''));

        // Respect explicitly cacheable responses (for endpoints that intentionally opt in).
        if (str_contains($existingCacheControl, 'public')) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        $response->headers->set('Surrogate-Control', 'no-store');

        return $response;
    }
}
