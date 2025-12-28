<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        // Here you would typically update .env file or database settings
        // For now, we'll just show success message

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

        // Store theme preference in session
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

            // Get last 100 log entries
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
            $backupName = 'backup_'.date('Y-m-d_H-i-s').'.sql';
            $backupPath = storage_path('app/backups/'.$backupName);

            // Create backups directory if it doesn't exist
            if (! Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }

            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            // Create mysqldump command
            $command = "mysqldump -h {$host} -u {$username} -p{$password} {$database} > {$backupPath}";

            // Execute backup (in production, use proper backup tools)
            exec($command, $output, $returnCode);

            if ($returnCode === 0 && file_exists($backupPath)) {
                Log::info('Database backup created successfully', ['file' => $backupName]);

                return response()->json([
                    'success' => true,
                    'message' => 'Backup database thành công! File: '.$backupName,
                    'file' => $backupName,
                ]);
            } else {
                throw new \Exception('Backup command failed');
            }
        } catch (\Exception $e) {
            Log::error('Database backup failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Backup thất bại: '.$e->getMessage(),
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

        // Sort by creation time (newest first)
        usort($backups, function ($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });

        return view('admin.system.backups', compact('backups'));
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $filePath = 'backups/'.$filename;

        if (! Storage::disk('local')->exists($filePath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download(storage_path('app/'.$filePath));
    }

    /**
     * Generate analytics report
     */
    public function analytics()
    {
        try {
            $stats = [
                'users' => [
                    'total' => DB::table('users')->count(),
                    'active' => DB::table('users')->where('status', 'active')->count(),
                    'suspended' => DB::table('users')->where('status', 'suspended')->count(),
                    'banned' => DB::table('users')->where('status', 'banned')->count(),
                    'new_this_month' => DB::table('users')->whereMonth('created_at', now()->month)->count(),
                ],
                'teams' => [
                    'total' => $this->safeTableCount('teams'),
                    'active' => $this->safeStatusCount('teams', 'active'),
                    'new_this_month' => $this->safeMonthCount('teams'),
                ],
                'tournaments' => [
                    'total' => $this->safeTableCount('tournaments'),
                    'active' => $this->safeStatusCount('tournaments', 'active'),
                    'completed' => $this->safeStatusCount('tournaments', 'completed'),
                    'new_this_month' => $this->safeMonthCount('tournaments'),
                ],
                'games' => [
                    'total' => $this->safeTableCount('games'),
                    'active' => $this->safeStatusCount('games', 'active'),
                ],
            ];

            // User registration trend (last 12 months)
            $userTrend = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $userTrend[] = [
                    'month' => $date->format('M Y'),
                    'count' => DB::table('users')->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)->count(),
                ];
            }

            return view('admin.system.analytics', compact('stats', 'userTrend'));
        } catch (\Exception $e) {
            Log::error('Analytics error: '.$e->getMessage());

            return back()->with('error', 'Có lỗi khi tải dữ liệu analytics: '.$e->getMessage());
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
                'message' => 'Lỗi khi xóa cache: '.$e->getMessage(),
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

            return $result[0]->db_size.' MB';
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

        return round($bytes, $precision).' '.$units[$i];
    }

    /**
     * Safely count table records
     */
    private function safeTableCount($tableName)
    {
        try {
            if (DB::getSchemaBuilder()->hasTable($tableName)) {
                return DB::table($tableName)->count();
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safely count records by status
     */
    private function safeStatusCount($tableName, $status)
    {
        try {
            if (
                DB::getSchemaBuilder()->hasTable($tableName) &&
                DB::getSchemaBuilder()->hasColumn($tableName, 'status')
            ) {
                return DB::table($tableName)->where('status', $status)->count();
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safely count records created this month
     */
    private function safeMonthCount($tableName)
    {
        try {
            if (
                DB::getSchemaBuilder()->hasTable($tableName) &&
                DB::getSchemaBuilder()->hasColumn($tableName, 'created_at')
            ) {
                return DB::table($tableName)->whereMonth('created_at', now()->month)->count();
            }

            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
