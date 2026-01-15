<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions  Các quyền cần kiểm tra (OR logic - chỉ cần 1 quyền)
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        // Kiểm tra đã đăng nhập chưa
        if (!$user) {
            return redirect()->route('auth.login');
        }

        // Kiểm tra có phải admin không
        if (!$user->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Super admin có tất cả quyền
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Nếu không có permission nào được chỉ định, cho phép tất cả admin
        if (empty($permissions)) {
            return $next($request);
        }

        // Kiểm tra admin có ít nhất 1 trong các quyền được yêu cầu
        if ($user->hasAnyAdminPermission($permissions)) {
            return $next($request);
        }

        // Không có quyền
        abort(403, 'Bạn không có quyền thực hiện hành động này.');
    }
}
