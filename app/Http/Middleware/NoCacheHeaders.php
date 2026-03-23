<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $existingCacheControl = strtolower((string) $response->headers->get('Cache-Control', ''));

        // Respect explicitly cacheable responses (e.g., static/media endpoints).
        if (str_contains($existingCacheControl, 'public')) {
            return $response;
        }

        // Force revalidation so auth pages are not shown from cache after login/logout.
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        $response->headers->set('Surrogate-Control', 'no-store');

        return $response;
    }
}