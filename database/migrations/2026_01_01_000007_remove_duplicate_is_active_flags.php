<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration 7: Bỏ các cột is_active trùng lặp với status
 * 
 * Vấn đề: games, teams, tournaments có cả is_active và status
 *         Logic trùng lặp, có thể inconsistent
 * Giải pháp: Chỉ dùng status, bỏ is_active
 */
return new class extends Migration
{
    public function up(): void
    {
        // ========== GAMES TABLE ==========
        // Đảm bảo status đã có giá trị phù hợp
        // Nếu chưa có column status, thêm vào
        if (!Schema::hasColumn('games', 'status')) {
            Schema::table('games', function (Blueprint $table) {
                $table->enum('status', ['active', 'maintenance', 'discontinued'])
                    ->default('active')
                    ->after('is_active');
            });
        }

        // Sync is_active → status
        DB::statement("
            UPDATE games 
            SET status = CASE 
                WHEN is_active = 1 THEN 'active' 
                ELSE 'maintenance' 
            END
            WHERE status IS NULL OR status = ''
        ");

        // Drop is_active từ games
        if (Schema::hasColumn('games', 'is_active')) {
            Schema::table('games', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        // ========== TEAMS TABLE ==========
        // Sync is_active → status
        DB::statement("
            UPDATE teams 
            SET status = CASE 
                WHEN is_active = 1 THEN 'active' 
                WHEN is_active = 0 THEN 'inactive'
                ELSE status
            END
        ");

        // Drop is_active từ teams
        if (Schema::hasColumn('teams', 'is_active')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        // ========== TOURNAMENTS TABLE ==========
        // Drop is_active từ tournaments (status đã đủ: registration, ongoing, completed, cancelled)
        if (Schema::hasColumn('tournaments', 'is_active')) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }

    public function down(): void
    {
        // Thêm lại is_active cho games
        if (!Schema::hasColumn('games', 'is_active')) {
            Schema::table('games', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('format');
            });

            DB::statement("
                UPDATE games 
                SET is_active = CASE 
                    WHEN status = 'active' THEN 1 
                    ELSE 0 
                END
            ");
        }

        // Thêm lại is_active cho teams
        if (!Schema::hasColumn('teams', 'is_active')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('max_members');
            });

            DB::statement("
                UPDATE teams 
                SET is_active = CASE 
                    WHEN status = 'active' THEN 1 
                    ELSE 0 
                END
            ");
        }

        // Thêm lại is_active cho tournaments
        if (!Schema::hasColumn('tournaments', 'is_active')) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('rules');
            });

            DB::statement("
                UPDATE tournaments 
                SET is_active = CASE 
                    WHEN status IN ('registration', 'ongoing') THEN 1 
                    ELSE 0 
                END
            ");
        }
    }
};
