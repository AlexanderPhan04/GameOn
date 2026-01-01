<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration 1: Fix marketplace_products.game_id từ VARCHAR → BIGINT với FK
 * 
 * Vấn đề: game_id đang là string, không có FK constraint
 * Giải pháp: Chuyển sang BIGINT UNSIGNED + FK đến games.id
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Thêm column mới với đúng kiểu dữ liệu
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id_new')->nullable()->after('game_id');
        });

        // Step 2: Migrate data - convert string to int nếu valid
        DB::statement('
            UPDATE marketplace_products 
            SET game_id_new = CAST(game_id AS UNSIGNED)
            WHERE game_id IS NOT NULL 
            AND game_id REGEXP "^[0-9]+$"
            AND EXISTS (SELECT 1 FROM games WHERE id = CAST(marketplace_products.game_id AS UNSIGNED))
        ');

        // Step 3: Drop column cũ
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropColumn('game_id');
        });

        // Step 4: Rename column mới
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->renameColumn('game_id_new', 'game_id');
        });

        // Step 5: Thêm FK constraint
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('set null');
            $table->index('game_id');
        });
    }

    public function down(): void
    {
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropForeign(['game_id']);
            $table->dropIndex(['game_id']);
        });

        // Chuyển lại về VARCHAR
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->string('game_id', 255)->nullable()->change();
        });
    }
};
