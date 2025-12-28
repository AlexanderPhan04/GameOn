<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate dữ liệu từ bảng users sang user_profiles và user_activities
     */
    public function up(): void
    {
        if (!Schema::hasTable('user_profiles') || !Schema::hasTable('user_activities')) {
            return;
        }

        // Migrate dữ liệu profile
        // Kiểm tra các cột có tồn tại trong users không
        $columns = [
            'full_name' => Schema::hasColumn('users', 'full_name'),
            'avatar' => Schema::hasColumn('users', 'avatar'),
            'bio' => Schema::hasColumn('users', 'bio'),
            'date_of_birth' => Schema::hasColumn('users', 'date_of_birth'),
            'phone' => Schema::hasColumn('users', 'phone'),
            'country' => Schema::hasColumn('users', 'country'),
            'gaming_nickname' => Schema::hasColumn('users', 'gaming_nickname'),
            'team_preference' => Schema::hasColumn('users', 'team_preference'),
            'id_app' => Schema::hasColumn('users', 'id_app'),
            'description' => Schema::hasColumn('users', 'description'),
            'upgraded_to_player_at' => Schema::hasColumn('users', 'upgraded_to_player_at'),
            'is_verified' => Schema::hasColumn('users', 'is_verified'),
            'verified_at' => Schema::hasColumn('users', 'verified_at'),
        ];

        // Chỉ migrate nếu có ít nhất một cột profile tồn tại
        $hasProfileColumns = in_array(true, $columns, true);
        
        if ($hasProfileColumns) {
            // Build SELECT statement với các cột có sẵn
            $selectFields = ['id as user_id'];
            $insertFields = ['user_id'];
            
            foreach ($columns as $column => $exists) {
                if ($exists) {
                    $selectFields[] = $column;
                    $insertFields[] = $column;
                } else {
                    // Nếu cột không tồn tại, dùng NULL hoặc default
                    if ($column === 'is_verified') {
                        $selectFields[] = 'false as is_verified';
                    } else {
                        $selectFields[] = 'NULL as ' . $column;
                    }
                    $insertFields[] = $column;
                }
            }
            
            $selectFields[] = 'created_at';
            $selectFields[] = 'updated_at';
            $insertFields[] = 'created_at';
            $insertFields[] = 'updated_at';
            
            // Nếu không có is_verified, thêm default
            if (!$columns['is_verified']) {
                $selectFields = array_map(function($field) {
                    return str_replace('NULL as is_verified', 'false as is_verified', $field);
                }, $selectFields);
            }
            
            // Nếu không có verified_at, thêm NULL
            if (!$columns['verified_at']) {
                $selectFields = array_map(function($field) {
                    return str_replace('NULL as verified_at', 'NULL as verified_at', $field);
                }, $selectFields);
            }
            
            $sql = "
                INSERT INTO user_profiles (" . implode(', ', $insertFields) . ")
                SELECT 
                    " . implode(",\n                    ", $selectFields) . "
                FROM users
                WHERE NOT EXISTS (
                    SELECT 1 FROM user_profiles up WHERE up.user_id = users.id
                )
            ";
            
            DB::statement($sql);
        } else {
            // Nếu không có cột profile nào, chỉ tạo profile rỗng với user_id
            DB::statement("
                INSERT INTO user_profiles (user_id, is_verified, verified_at, created_at, updated_at)
                SELECT 
                    id as user_id,
                    false as is_verified,
                    NULL as verified_at,
                    created_at,
                    updated_at
                FROM users
                WHERE NOT EXISTS (
                    SELECT 1 FROM user_profiles up WHERE up.user_id = users.id
                )
            ");
        }

        // Migrate dữ liệu activities
        // Kiểm tra các cột có tồn tại trong users không
        $activityColumns = [
            'last_login' => Schema::hasColumn('users', 'last_login'),
            'last_seen_at' => Schema::hasColumn('users', 'last_seen_at'),
            'online_status' => Schema::hasColumn('users', 'online_status'),
            'is_typing' => Schema::hasColumn('users', 'is_typing'),
            'typing_started_at' => Schema::hasColumn('users', 'typing_started_at'),
            'last_activity_at' => Schema::hasColumn('users', 'last_activity_at'),
        ];
        
        $hasActivityColumns = in_array(true, $activityColumns, true);
        
        if ($hasActivityColumns) {
            $selectFields = ['id as user_id'];
            $insertFields = ['user_id'];
            
            // last_login_at
            if ($activityColumns['last_login']) {
                $selectFields[] = 'last_login as last_login_at';
            } else {
                $selectFields[] = 'NULL as last_login_at';
            }
            $insertFields[] = 'last_login_at';
            
            // last_seen_at
            if ($activityColumns['last_seen_at']) {
                $selectFields[] = 'last_seen_at';
            } else {
                $selectFields[] = 'NULL as last_seen_at';
            }
            $insertFields[] = 'last_seen_at';
            
            // online_status
            if ($activityColumns['online_status']) {
                $selectFields[] = "COALESCE(online_status, 'offline') as online_status";
            } else {
                $selectFields[] = "'offline' as online_status";
            }
            $insertFields[] = 'online_status';
            
            // is_typing
            if ($activityColumns['is_typing']) {
                $selectFields[] = 'COALESCE(is_typing, false) as is_typing';
            } else {
                $selectFields[] = 'false as is_typing';
            }
            $insertFields[] = 'is_typing';
            
            // typing_started_at
            if ($activityColumns['typing_started_at']) {
                $selectFields[] = 'typing_started_at';
            } else {
                $selectFields[] = 'NULL as typing_started_at';
            }
            $insertFields[] = 'typing_started_at';
            
            // last_activity_at
            if ($activityColumns['last_activity_at']) {
                $selectFields[] = 'last_activity_at';
            } else {
                $selectFields[] = 'NULL as last_activity_at';
            }
            $insertFields[] = 'last_activity_at';
            
            $selectFields[] = 'created_at';
            $selectFields[] = 'updated_at';
            $insertFields[] = 'created_at';
            $insertFields[] = 'updated_at';
            
            $sql = "
                INSERT INTO user_activities (" . implode(', ', $insertFields) . ")
                SELECT 
                    " . implode(",\n                    ", $selectFields) . "
                FROM users
                WHERE NOT EXISTS (
                    SELECT 1 FROM user_activities ua WHERE ua.user_id = users.id
                )
            ";
            
            DB::statement($sql);
        } else {
            // Nếu không có cột activity nào, chỉ tạo activity rỗng với user_id
            DB::statement("
                INSERT INTO user_activities (user_id, online_status, is_typing, created_at, updated_at)
                SELECT 
                    id as user_id,
                    'offline' as online_status,
                    false as is_typing,
                    created_at,
                    updated_at
                FROM users
                WHERE NOT EXISTS (
                    SELECT 1 FROM user_activities ua WHERE ua.user_id = users.id
                )
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không thể rollback dễ dàng vì đã tách dữ liệu
        // Cần merge lại thủ công nếu cần
    }
};

