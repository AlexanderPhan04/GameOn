<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Đổi tên cột upgraded_to_player_at thành upgraded_to_participant_at
     */
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->renameColumn('upgraded_to_player_at', 'upgraded_to_participant_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->renameColumn('upgraded_to_participant_at', 'upgraded_to_player_at');
        });
    }
};
