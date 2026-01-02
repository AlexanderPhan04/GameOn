<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Gộp player_weight và viewer_weight thành participant_weight
     * Gộp allow_player_vote và allow_viewer_vote thành allow_participant_vote
     * Đổi target_type 'player' thành 'user'
     * Đổi vote_type 'player' thành 'user'  
     * Đổi voter_role 'player'/'viewer' thành 'participant'
     */
    public function up(): void
    {
        // 1. Update honor_events table
        Schema::table('honor_events', function (Blueprint $table) {
            // Thêm cột mới
            $table->boolean('allow_participant_vote')->default(true)->after('allow_admin_vote');
            $table->decimal('participant_weight', 3, 1)->default(1.0)->after('admin_weight');
        });

        // Migrate data - lấy giá trị cao nhất giữa player và viewer
        DB::table('honor_events')->update([
            'allow_participant_vote' => DB::raw('GREATEST(allow_player_vote, allow_viewer_vote)'),
            'participant_weight' => DB::raw('GREATEST(player_weight, viewer_weight)'),
        ]);

        // Update target_type từ 'player' thành 'user'
        DB::table('honor_events')
            ->where('target_type', 'player')
            ->update(['target_type' => 'user']);

        // Xóa cột cũ
        Schema::table('honor_events', function (Blueprint $table) {
            $table->dropColumn(['allow_player_vote', 'allow_viewer_vote', 'player_weight', 'viewer_weight']);
        });

        // 2. Update honor_votes table
        // Update vote_type từ 'player' thành 'user'
        DB::table('honor_votes')
            ->where('vote_type', 'player')
            ->update(['vote_type' => 'user']);

        // Update voter_role từ 'player'/'viewer' thành 'participant'
        DB::table('honor_votes')
            ->whereIn('voter_role', ['player', 'viewer'])
            ->update(['voter_role' => 'participant']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Restore honor_events columns
        Schema::table('honor_events', function (Blueprint $table) {
            $table->boolean('allow_viewer_vote')->default(true)->after('allow_anonymous');
            $table->boolean('allow_player_vote')->default(true)->after('allow_viewer_vote');
            $table->decimal('viewer_weight', 3, 1)->default(1.0)->after('admin_weight');
            $table->decimal('player_weight', 3, 1)->default(1.5)->after('viewer_weight');
        });

        // Restore data
        DB::table('honor_events')->update([
            'allow_player_vote' => DB::raw('allow_participant_vote'),
            'allow_viewer_vote' => DB::raw('allow_participant_vote'),
            'player_weight' => DB::raw('participant_weight'),
            'viewer_weight' => DB::raw('participant_weight'),
        ]);

        // Restore target_type
        DB::table('honor_events')
            ->where('target_type', 'user')
            ->update(['target_type' => 'player']);

        // Drop new columns
        Schema::table('honor_events', function (Blueprint $table) {
            $table->dropColumn(['allow_participant_vote', 'participant_weight']);
        });

        // 2. Restore honor_votes
        DB::table('honor_votes')
            ->where('vote_type', 'user')
            ->update(['vote_type' => 'player']);

        DB::table('honor_votes')
            ->where('voter_role', 'participant')
            ->update(['voter_role' => 'player']);
    }
};
