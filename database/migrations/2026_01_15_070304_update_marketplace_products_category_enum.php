<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update category enum to include tournament_entry
        DB::statement("ALTER TABLE marketplace_products MODIFY COLUMN category ENUM('ui_theme', 'avatar_frame', 'sticker_pack', 'emote', 'weapon_skin', 'character_skin', 'currency', 'tournament_entry', 'other') DEFAULT 'other'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE marketplace_products MODIFY COLUMN category ENUM('ui_theme', 'avatar_frame', 'sticker_pack', 'emote', 'weapon_skin', 'character_skin', 'currency', 'other') DEFAULT 'other'");
    }
};
