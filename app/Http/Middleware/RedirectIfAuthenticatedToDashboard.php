<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedToDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role === 'official') {
            return redirect('/official/dashboard');
        }

        if ($user->role === 'resident') {
            if ($user->isResidentAccountExpired()) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->with('error', 'Your resident account has expired after 1 year. Please contact the barangay office for renewal.');
            }

            return redirect('/resident/dashboard');
        }

        return redirect('/');
    }
}