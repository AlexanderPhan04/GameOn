<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update category enum to include tournament_entry
        // Chỉ chạy với MySQL, SQLite không hỗ trợ ENUM
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE marketplace_products MODIFY COLUMN category ENUM('ui_theme', 'avatar_frame', 'sticker_pack', 'emote', 'weapon_skin', 'character_skin', 'currency', 'tournament_entry', 'other') DEFAULT 'other'");
        }
        // SQLite: category đã là string nên không cần thay đổi
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE marketplace_products MODIFY COLUMN category ENUM('ui_theme', 'avatar_frame', 'sticker_pack', 'emote', 'weapon_skin', 'character_skin', 'currency', 'other') DEFAULT 'other'");
        }
    }
};
