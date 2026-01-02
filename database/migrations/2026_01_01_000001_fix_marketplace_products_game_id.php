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
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // Step 1: Thêm column mới với đúng kiểu dữ liệu
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id_new')->nullable()->after('game_id');
        });

        // Step 2: Migrate data - convert string to int nếu valid
        // Sử dụng PHP để migrate data thay vì raw SQL (tương thích cả MySQL và SQLite)
        $products = DB::table('marketplace_products')
            ->whereNotNull('game_id')
            ->get(['id', 'game_id']);

        foreach ($products as $product) {
            // Kiểm tra game_id có phải số không
            if (is_numeric($product->game_id) && (int) $product->game_id > 0) {
                $gameId = (int) $product->game_id;
                // Kiểm tra game có tồn tại không
                $gameExists = DB::table('games')->where('id', $gameId)->exists();
                if ($gameExists) {
                    DB::table('marketplace_products')
                        ->where('id', $product->id)
                        ->update(['game_id_new' => $gameId]);
                }
            }
        }

        // Step 3: Drop column cũ
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropColumn('game_id');
        });

        // Step 4: Rename column mới
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->renameColumn('game_id_new', 'game_id');
        });

        // Step 5: Thêm FK constraint (chỉ cho MySQL, SQLite không hỗ trợ ADD FK sau khi tạo bảng)
        if (! $isSqlite) {
            Schema::table('marketplace_products', function (Blueprint $table) {
                $table->foreign('game_id')
                    ->references('id')
                    ->on('games')
                    ->onDelete('set null');
            });
        }

        // Thêm index
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->index('game_id');
        });
    }

    public function down(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        if (! $isSqlite) {
            Schema::table('marketplace_products', function (Blueprint $table) {
                $table->dropForeign(['game_id']);
            });
        }

        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropIndex(['game_id']);
        });

        // Chuyển lại về VARCHAR
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->string('game_id', 255)->nullable()->change();
        });
    }
};
