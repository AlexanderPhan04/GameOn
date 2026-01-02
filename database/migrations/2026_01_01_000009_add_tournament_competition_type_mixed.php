<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration 9: Thêm 'mixed' vào tournaments.competition_type
 *
 * Vấn đề: competition_type chỉ có 'individual', 'team' - thiếu 'mixed'
 * Giải pháp: Thêm column mới với VARCHAR, migrate data, xóa column cũ
 */
return new class extends Migration
{
    public function up(): void
    {
        // Kiểm tra nếu column competition_type tồn tại
        if (Schema::hasColumn('tournaments', 'competition_type')) {
            // Step 1: Thêm column mới
            Schema::table('tournaments', function (Blueprint $table) {
                $table->string('competition_type_new', 20)->default('individual')->after('competition_type');
            });

            // Step 2: Copy data (dùng PHP thay vì raw SQL)
            $tournaments = DB::table('tournaments')->get(['id', 'competition_type']);
            foreach ($tournaments as $tournament) {
                DB::table('tournaments')
                    ->where('id', $tournament->id)
                    ->update(['competition_type_new' => $tournament->competition_type]);
            }

            // Step 3: Drop column cũ
            Schema::table('tournaments', function (Blueprint $table) {
                $table->dropColumn('competition_type');
            });

            // Step 4: Rename
            Schema::table('tournaments', function (Blueprint $table) {
                $table->renameColumn('competition_type_new', 'competition_type');
            });
        } else {
            // Thêm column mới nếu chưa có
            Schema::table('tournaments', function (Blueprint $table) {
                $table->string('competition_type', 20)->default('individual')->after('format');
            });
        }
    }

    public function down(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        if (Schema::hasColumn('tournaments', 'competition_type')) {
            // Step 1: Thêm column cũ
            Schema::table('tournaments', function (Blueprint $table) use ($isSqlite) {
                if ($isSqlite) {
                    $table->string('competition_type_old', 20)->default('individual')->after('competition_type');
                } else {
                    $table->enum('competition_type_old', ['individual', 'team'])->default('individual')->after('competition_type');
                }
            });

            // Step 2: Copy data (chuyển 'mixed' về 'team', dùng PHP)
            $tournaments = DB::table('tournaments')->get(['id', 'competition_type']);
            foreach ($tournaments as $tournament) {
                $compType = $tournament->competition_type;
                if ($compType === 'mixed') {
                    $compType = 'team';
                } elseif (! in_array($compType, ['individual', 'team'])) {
                    $compType = 'individual';
                }
                DB::table('tournaments')
                    ->where('id', $tournament->id)
                    ->update(['competition_type_old' => $compType]);
            }

            // Step 3: Drop column VARCHAR
            Schema::table('tournaments', function (Blueprint $table) {
                $table->dropColumn('competition_type');
            });

            // Step 4: Rename
            Schema::table('tournaments', function (Blueprint $table) {
                $table->renameColumn('competition_type_old', 'competition_type');
            });
        }
    }
};
