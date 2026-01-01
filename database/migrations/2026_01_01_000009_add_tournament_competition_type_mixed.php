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

            // Step 2: Copy data
            DB::statement('UPDATE tournaments SET competition_type_new = competition_type');

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
        if (Schema::hasColumn('tournaments', 'competition_type')) {
            // Step 1: Thêm column ENUM cũ
            Schema::table('tournaments', function (Blueprint $table) {
                $table->enum('competition_type_old', ['individual', 'team'])->default('individual')->after('competition_type');
            });

            // Step 2: Copy data (chuyển 'mixed' về 'team')
            DB::statement("
                UPDATE tournaments 
                SET competition_type_old = CASE 
                    WHEN competition_type = 'mixed' THEN 'team' 
                    WHEN competition_type IN ('individual', 'team') THEN competition_type
                    ELSE 'individual' 
                END
            ");

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
