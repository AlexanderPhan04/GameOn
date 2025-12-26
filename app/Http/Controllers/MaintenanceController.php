<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
    /**
     * Hiển thị trang bảo trì
     */
    public function show(Request $request)
    {
        $error = $request->get('error', 'Database connection failed');
        $timestamp = now()->format('d/m/Y H:i:s');

        return response()->view('maintenance', [
            'error' => $error,
            'timestamp' => $timestamp,
            'retry_url' => $request->url(),
        ], 503);
    }

    /**
     * Kiểm tra trạng thái hệ thống
     */
    public function status()
    {
        try {
            // Thử kết nối database
            DB::connection()->getPdo();

            return response()->json([
                'status' => 'online',
                'message' => 'Hệ thống hoạt động bình thường',
                'timestamp' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            Log::error('System status check failed: '.$e->getMessage());

            return response()->json([
                'status' => 'maintenance',
                'message' => 'Hệ thống đang bảo trì',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString(),
            ], 503);
        }
    }

    /**
     * API endpoint để kiểm tra trạng thái từ frontend
     */
    public function checkStatus()
    {
        try {
            DB::connection()->getPdo();

            return response()->json([
                'online' => true,
                'message' => 'Hệ thống đã hoạt động trở lại',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'online' => false,
                'message' => 'Hệ thống vẫn đang bảo trì',
                'error' => $e->getMessage(),
            ], 503);
        }
    }
}
