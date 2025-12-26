<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * SessionAuthMiddleware - Kiểm tra authentication qua session
 * Tương ứng với logic authentication trong EsportsManager C#
 */
class SessionAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra có user_id trong session không
        $userId = Session::get('user_id');

        if (! $userId) {
            // Nếu là AJAX request, trả về JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để tiếp tục',
                    'redirect_url' => route('auth.login'),
                ], 401);
            }

            // Nếu là request thông thường, redirect đến login
            return redirect()->route('auth.login')->with('message', 'Vui lòng đăng nhập để tiếp tục');
        }

        // Lưu thông tin user vào request để sử dụng trong controller
        $request->merge([
            'authenticated_user_id' => $userId,
            'authenticated_username' => Session::get('username'),
            'authenticated_role' => Session::get('role'),
        ]);

        return $next($request);
    }
}
