<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackLastLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Only update if last_login is null or was more than 5 minutes ago
            if (! $user->last_login || $user->last_login->diffInMinutes(now()) > 5) {
                $user->update(['last_login' => now()]);
            }
        }

        return $next($request);
    }
}
