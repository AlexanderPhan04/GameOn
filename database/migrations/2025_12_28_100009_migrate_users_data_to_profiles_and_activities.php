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
        // Kiểm tra xem cột is_verified có tồn tại trong users không
        $hasIsVerified = Schema::hasColumn('users', 'is_verified');
        
        if ($hasIsVerified) {
            DB::statement("
                INSERT INTO user_profiles (user_id, full_name, avatar, bio, date_of_birth, phone, country, gaming_nickname, team_preference, id_app, description, upgraded_to_player_at, is_verified, verified_at, created_at, updated_at)
                SELECT 
                    id as user_id,
                    full_name,
                    avatar,
                    bio,
                    date_of_birth,
                    phone,
                    country,
                    gaming_nickname,
                    team_preference,
                    id_app,
                    description,
                    upgraded_to_player_at,
                    COALESCE(is_verified, false) as is_verified,
                    verified_at,
                    created_at,
                    updated_at
                FROM users
                WHERE NOT EXISTS (
                    SELECT 1 FROM user_profiles up WHERE up.user_id = users.id
                )
            ");
        } else {
            DB::statement("
                INSERT INTO user_profiles (user_id, full_name, avatar, bio, date_of_birth, phone, country, gaming_nickname, team_preference, id_app, description, upgraded_to_player_at, is_verified, verified_at, created_at, updated_at)
                SELECT 
                    id as user_id,
                    full_name,
                    avatar,
                    bio,
                    date_of_birth,
                    phone,
                    country,
                    gaming_nickname,
                    team_preference,
                    id_app,
                    description,
                    upgraded_to_player_at,
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
        DB::statement("
            INSERT INTO user_activities (user_id, last_login_at, last_seen_at, online_status, is_typing, typing_started_at, last_activity_at, created_at, updated_at)
            SELECT 
                id as user_id,
                last_login as last_login_at,
                last_seen_at,
                COALESCE(online_status, 'offline') as online_status,
                COALESCE(is_typing, false) as is_typing,
                typing_started_at,
                last_activity_at,
                created_at,
                updated_at
            FROM users
            WHERE NOT EXISTS (
                SELECT 1 FROM user_activities ua WHERE ua.user_id = users.id
            )
        ");
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

