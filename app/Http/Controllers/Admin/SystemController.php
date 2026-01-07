<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * SystemController - Admin system management
 * Refactored to use Eloquent models where possible
 */
class SystemController extends Controller
{
    /**
     * Display system settings page
     */
    public function settings()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'cache_enabled' => config('cache.default') !== 'array',
            'debug_mode' => config('app.debug'),
            'environment' => config('app.env'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ];

        return view('admin.system.settings', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'maintenance_mode' => 'boolean',
            'cache_enabled' => 'boolean',
        ]);

        return redirect()->back()->with('success', 'Cài đặt hệ thống đã được cập nhật thành công!');
    }

    /**
     * Update theme preference
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);

        session(['theme' => $request->theme]);

        return response()->json([
            'success' => true,
            'message' => 'Cài đặt giao diện đã được cập nhật thành công!',
        ]);
    }

    /**
     * Display system logs
     */
    public function logs()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logPath)) {
            $logContent = file_get_contents($logPath);
            $logLines = array_reverse(explode("\n", $logContent));
            $logs = array_slice(array_filter($logLines), 0, 100);
        }

        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'disk_space' => $this->getDiskSpace(),
            'database_size' => $this->getDatabaseSize(),
        ];

        return view('admin.system.logs', compact('logs', 'systemInfo'));
    }

    /**
     * Create database backup
     */
    public function createBackup()
    {
        try {
            $backupName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/backups/' . $backupName);

            if (!Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }

            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            $command = "mysqldump -h {$host} -u {$username} -p{$password} {$database} > {$backupPath}";
            exec($command, $output, $returnCode);

            if ($returnCode === 0 && file_exists($backupPath)) {
                Log::info('Database backup created successfully', ['file' => $backupName]);

                return response()->json([
                    'success' => true,
                    'message' => 'Backup database thành công! File: ' . $backupName,
                    'file' => $backupName,
                ]);
            } else {
                throw new \Exception('Backup command failed');
            }
        } catch (\Exception $e) {
            Log::error('Database backup failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Backup thất bại: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List available backups
     */
    public function listBackups()
    {
        $backups = [];
        $backupFiles = Storage::disk('local')->files('backups');

        foreach ($backupFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $backups[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => Storage::disk('local')->size($file),
                    'created_at' => Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file)),
                ];
            }
        }

        usort($backups, fn($a, $b) => $b['created_at']->timestamp - $a['created_at']->timestamp);

        return view('admin.system.backups', compact('backups'));
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $filePath = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download(storage_path('app/' . $filePath));
    }

    /**
     * Generate analytics report - Using Eloquent models
     */
    public function analytics()
    {
        try {
            $stats = [
                'users' => [
                    'total' => User::count(),
                    'active' => User::where('status', 'active')->count(),
                    'suspended' => User::where('status', 'suspended')->count(),
                    'banned' => User::where('status', 'banned')->count(),
                    'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
                ],
                'teams' => [
                    'total' => $this->safeModelCount(Team::class),
                    'active' => $this->safeModelStatusCount(Team::class, 'active'),
                    'new_this_month' => $this->safeModelMonthCount(Team::class),
                ],
                'tournaments' => [
                    'total' => $this->safeModelCount(Tournament::class),
                    'active' => $this->safeModelStatusCount(Tournament::class, 'active'),
                    'completed' => $this->safeModelStatusCount(Tournament::class, 'completed'),
                    'new_this_month' => $this->safeModelMonthCount(Tournament::class),
                ],
                'games' => [
                    'total' => $this->safeModelCount(Game::class),
                    'active' => $this->safeModelStatusCount(Game::class, 'active'),
                ],
            ];

            // User registration trend (last 12 months) - Using Eloquent
            $userTrend = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $userTrend[] = [
                    'month' => $date->format('M Y'),
                    'count' => User::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count(),
                ];
            }

            return view('admin.system.analytics', compact('stats', 'userTrend'));
        } catch (\Exception $e) {
            Log::error('Analytics error: ' . $e->getMessage());

            return back()->with('error', 'Có lỗi khi tải dữ liệu analytics: ' . $e->getMessage());
        }
    }

    /**
     * Clear system cache
     */
    public function clearCache()
    {
        try {
            Cache::flush();
            Log::info('System cache cleared by admin');

            return response()->json([
                'success' => true,
                'message' => 'Cache hệ thống đã được xóa thành công!',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to clear cache', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa cache: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get disk space information
     */
    private function getDiskSpace()
    {
        $totalSpace = disk_total_space('/');
        $freeSpace = disk_free_space('/');
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'total' => $this->formatBytes($totalSpace),
            'used' => $this->formatBytes($usedSpace),
            'free' => $this->formatBytes($freeSpace),
            'used_percentage' => round(($usedSpace / $totalSpace) * 100, 2),
        ];
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS 'db_size' FROM information_schema.tables WHERE table_schema='{$database}'");

            return $result[0]->db_size . ' MB';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Safely count model records using Eloquent
     */
    private function safeModelCount(string $modelClass): int
    {
        try {
            if (class_exists($modelClass)) {
                return $modelClass::count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safely count model records by status using Eloquent
     */
    private function safeModelStatusCount(string $modelClass, string $status): int
    {
        try {
            if (class_exists($modelClass)) {
                return $modelClass::where('status', $status)->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safely count model records created this month using Eloquent
     */
    private function safeModelMonthCount(string $modelClass): int
    {
        try {
            if (class_exists($modelClass)) {
                return $modelClass::whereMonth('created_at', now()->month)->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
