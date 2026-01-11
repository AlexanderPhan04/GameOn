<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update honor_events table to match current system roles (participant, admin)
     * instead of old roles (viewer, player)
     */
    public function up(): void
    {
        // Check if old columns exist and rename/update them
        if (Schema::hasColumn('honor_events', 'allow_viewer_vote')) {
            Schema::table('honor_events', function (Blueprint $table) {
                // Rename old columns to new names
                $table->renameColumn('allow_viewer_vote', 'allow_participant_vote');
                $table->renameColumn('viewer_weight', 'participant_weight');
            });
        }

        // Remove player columns if they exist (merge into participant)
        if (Schema::hasColumn('honor_events', 'allow_player_vote')) {
            Schema::table('honor_events', function (Blueprint $table) {
                $table->dropColumn(['allow_player_vote', 'player_weight']);
            });
        }

        // Update target_type enum to use 'user' instead of 'player'
        // Note: MySQL doesn't support direct enum modification, so we need to use raw SQL
        if (Schema::hasColumn('honor_events', 'target_type')) {
            // Update existing 'player' values to 'user'
            DB::table('honor_events')->where('target_type', 'player')->update(['target_type' => 'user']);
            
            // Modify the enum (MySQL specific)
            DB::statement("ALTER TABLE honor_events MODIFY COLUMN target_type ENUM('user', 'team', 'tournament', 'game') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert target_type enum
        if (Schema::hasColumn('honor_events', 'target_type')) {
            DB::table('honor_events')->where('target_type', 'user')->update(['target_type' => 'player']);
            DB::statement("ALTER TABLE honor_events MODIFY COLUMN target_type ENUM('player', 'team', 'tournament', 'game') NOT NULL");
        }

        // Revert column names
        if (Schema::hasColumn('honor_events', 'allow_participant_vote')) {
            Schema::table('honor_events', function (Blueprint $table) {
                $table->renameColumn('allow_participant_vote', 'allow_viewer_vote');
                $table->renameColumn('participant_weight', 'viewer_weight');
            });
        }

        // Re-add player columns
        Schema::table('honor_events', function (Blueprint $table) {
            $table->boolean('allow_player_vote')->default(true)->after('allow_viewer_vote');
            $table->decimal('player_weight', 3, 1)->default(1.5)->after('viewer_weight');
        });
    }
};
