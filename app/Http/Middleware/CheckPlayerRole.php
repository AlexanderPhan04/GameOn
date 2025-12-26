<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPlayerRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('auth.login');
        }

        $user = Auth::user();

        // Allow admin and player roles to access team features
        if ($user->user_role !== 'player' && $user->user_role !== 'admin') {
            return redirect()->route('home')->with('error', 'Bạn cần có vai trò Player hoặc Admin để truy cập tính năng này.');
        }

        return $next($request);
    }
}
