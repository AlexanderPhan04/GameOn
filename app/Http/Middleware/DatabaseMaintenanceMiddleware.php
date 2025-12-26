<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DatabaseMaintenanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Thử kết nối database
            DB::connection()->getPdo();

            return $next($request);
        } catch (\Exception $e) {
            // Log lỗi để debug
            Log::error('Database connection failed: '.$e->getMessage());

            // Kiểm tra nếu là lỗi kết nối MySQL
            if ($this->isDatabaseConnectionError($e)) {
                return app(\App\Http\Controllers\MaintenanceController::class)->show($request);
            }

            // Nếu không phải lỗi database, tiếp tục xử lý bình thường
            return $next($request);
        }
    }

    /**
     * Kiểm tra xem có phải lỗi kết nối database không
     */
    private function isDatabaseConnectionError(\Exception $e): bool
    {
        $message = $e->getMessage();

        // Các mã lỗi MySQL phổ biến
        $mysqlErrorCodes = [
            '2002', // Connection refused
            '2003', // Can't connect to MySQL server
            '1045', // Access denied
            '1049', // Unknown database
            'HY000', // General error
        ];

        foreach ($mysqlErrorCodes as $code) {
            if (strpos($message, $code) !== false) {
                return true;
            }
        }

        return false;
    }
}
