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
 * 
 * Note: SQLite không hỗ trợ DROP COLUMN khi có index phức tạp,
 *       nên trên SQLite chỉ đồng bộ data, không drop column.
 *       Đây là migration dọn dẹp, không ảnh hưởng logic app.
 */
return new class extends Migration
{
    public function up(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // ========== GAMES TABLE ==========
        // Đảm bảo status đã có giá trị phù hợp
        // Nếu chưa có column status, thêm vào
        if (! Schema::hasColumn('games', 'status')) {
            Schema::table('games', function (Blueprint $table) use ($isSqlite) {
                if ($isSqlite) {
                    $table->string('status')->default('active');
                } else {
                    $table->enum('status', ['active', 'maintenance', 'discontinued'])
                        ->default('active')
                        ->after('is_active');
                }
            });
        }

        // Sync is_active → status (dùng PHP thay vì raw SQL)
        if (Schema::hasColumn('games', 'is_active')) {
            $games = DB::table('games')->get(['id', 'is_active', 'status']);
            foreach ($games as $game) {
                if (empty($game->status)) {
                    DB::table('games')
                        ->where('id', $game->id)
                        ->update(['status' => $game->is_active ? 'active' : 'maintenance']);
                }
            }

            // Drop is_active từ games - CHỈ trên MySQL/MariaDB
            // SQLite không hỗ trợ DROP COLUMN với composite index
            if (! $isSqlite) {
                // Drop index trước khi drop column
                Schema::table('games', function (Blueprint $table) {
                    $table->dropIndex(['name', 'is_active']);
                });

                Schema::table('games', function (Blueprint $table) {
                    $table->dropColumn('is_active');
                });

                // Tạo lại index chỉ với name
                Schema::table('games', function (Blueprint $table) {
                    $table->index(['name']);
                });
            }
        }

        // ========== TEAMS TABLE ==========
        // Sync is_active → status
        if (Schema::hasColumn('teams', 'is_active')) {
            $teams = DB::table('teams')->get(['id', 'is_active', 'status']);
            foreach ($teams as $team) {
                $newStatus = $team->is_active ? 'active' : 'inactive';
                if ($team->status !== $newStatus && $team->status !== 'disbanded') {
                    DB::table('teams')
                        ->where('id', $team->id)
                        ->update(['status' => $newStatus]);
                }
            }

            // Drop is_active từ teams - CHỈ trên MySQL/MariaDB
            if (! $isSqlite) {
                Schema::table('teams', function (Blueprint $table) {
                    $table->dropColumn('is_active');
                });
            }
        }

        // ========== TOURNAMENTS TABLE ==========
        // Drop is_active từ tournaments (status đã đủ: registration, ongoing, completed, cancelled)
        if (Schema::hasColumn('tournaments', 'is_active') && ! $isSqlite) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }

    public function down(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // SQLite không drop column trong up(), nên không cần restore
        if ($isSqlite) {
            return;
        }

        // Thêm lại is_active cho games
        if (! Schema::hasColumn('games', 'is_active')) {
            // Drop index name trước khi thêm composite index
            Schema::table('games', function (Blueprint $table) {
                $table->dropIndex(['name']);
            });

            Schema::table('games', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('format');
            });

            $games = DB::table('games')->get(['id', 'status']);
            foreach ($games as $game) {
                DB::table('games')
                    ->where('id', $game->id)
                    ->update(['is_active' => $game->status === 'active' ? 1 : 0]);
            }

            // Tạo lại composite index
            Schema::table('games', function (Blueprint $table) {
                $table->index(['name', 'is_active']);
            });
        }

        // Thêm lại is_active cho teams
        if (! Schema::hasColumn('teams', 'is_active')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('max_members');
            });

            $teams = DB::table('teams')->get(['id', 'status']);
            foreach ($teams as $team) {
                DB::table('teams')
                    ->where('id', $team->id)
                    ->update(['is_active' => $team->status === 'active' ? 1 : 0]);
            }
        }

        // Thêm lại is_active cho tournaments
        if (! Schema::hasColumn('tournaments', 'is_active')) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('rules');
            });

            $tournaments = DB::table('tournaments')->get(['id', 'status']);
            foreach ($tournaments as $tournament) {
                $isActive = in_array($tournament->status, ['registration', 'ongoing']) ? 1 : 0;
                DB::table('tournaments')
                    ->where('id', $tournament->id)
                    ->update(['is_active' => $isActive]);
            }
        }
    }
};
