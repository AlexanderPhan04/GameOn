<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckParticipantRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thực hiện hành động này.',
                ], 401);
            }
            return redirect()->route('auth.login');
        }

        $user = Auth::user();

        // Allow admin, participant roles to access team features
        if (!in_array($user->user_role, ['participant', 'admin', 'super_admin'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập tính năng này.',
                ], 403);
            }
            return redirect()->route('home')->with('error', 'Bạn cần đăng nhập để truy cập tính năng này.');
        }

        return $next($request);
    }
}
