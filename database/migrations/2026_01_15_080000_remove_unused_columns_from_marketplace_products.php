<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Xóa các cột không cần thiết cho marketplace (chỉ bán Sticker Pack và Vé giải đấu)
 * - game_id: không cần vì không bán vật phẩm game
 * - preview_url: không cần
 * - rarity: không cần độ hiếm cho sticker/vé
 */
return new class extends Migration
{
    public function up(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // Drop FK constraint trước (nếu có)
        if (!$isSqlite) {
            Schema::table('marketplace_products', function (Blueprint $table) {
                // Kiểm tra và drop FK nếu tồn tại
                $table->dropForeign(['game_id']);
            });
        }

        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropIndex(['game_id']);
        });

        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropColumn(['game_id', 'preview_url', 'rarity']);
        });
    }

    public function down(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id')->nullable()->after('metadata');
            $table->string('preview_url')->nullable()->after('images');
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->nullable()->after('game_id');
        });

        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->index('game_id');
        });

        if (!$isSqlite) {
            Schema::table('marketplace_products', function (Blueprint $table) {
                $table->foreign('game_id')
                    ->references('id')
                    ->on('games')
                    ->onDelete('set null');
            });
        }
    }
};
