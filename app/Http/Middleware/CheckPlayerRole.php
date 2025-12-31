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

        // Allow admin, participant (merged player/viewer) roles to access team features
        if (!in_array($user->user_role, ['participant', 'player', 'admin', 'super_admin'])) {
            return redirect()->route('home')->with('error', 'Bạn cần đăng nhập để truy cập tính năng này.');
        }

        return $next($request);
    }
}
