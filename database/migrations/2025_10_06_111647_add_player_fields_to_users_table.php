<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gaming_nickname')->nullable()->after('name');
            $table->text('team_preference')->nullable()->after('gaming_nickname');
            $table->text('description')->nullable()->after('team_preference');
            $table->timestamp('upgraded_to_player_at')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gaming_nickname', 'team_preference', 'description', 'upgraded_to_player_at']);
        });
    }
};
