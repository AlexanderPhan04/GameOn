<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update honor_votes table to match current system roles
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        
        // Update vote_type enum to use 'user' instead of 'player'
        if (Schema::hasColumn('honor_votes', 'vote_type')) {
            DB::table('honor_votes')->where('vote_type', 'player')->update(['vote_type' => 'user']);
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE honor_votes MODIFY COLUMN vote_type ENUM('user', 'team', 'tournament', 'game') NOT NULL");
            }
        }

        // Update voter_role enum to match system roles
        if (Schema::hasColumn('honor_votes', 'voter_role')) {
            // Map old roles to new roles
            DB::table('honor_votes')->where('voter_role', 'viewer')->update(['voter_role' => 'participant']);
            DB::table('honor_votes')->where('voter_role', 'player')->update(['voter_role' => 'participant']);
            
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE honor_votes MODIFY COLUMN voter_role ENUM('participant', 'admin', 'super_admin') NOT NULL");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        
        // Revert vote_type enum
        if (Schema::hasColumn('honor_votes', 'vote_type')) {
            DB::table('honor_votes')->where('vote_type', 'user')->update(['vote_type' => 'player']);
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE honor_votes MODIFY COLUMN vote_type ENUM('player', 'team', 'tournament', 'game') NOT NULL");
            }
        }

        // Revert voter_role enum
        if (Schema::hasColumn('honor_votes', 'voter_role')) {
            DB::table('honor_votes')->where('voter_role', 'participant')->update(['voter_role' => 'viewer']);
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE honor_votes MODIFY COLUMN voter_role ENUM('viewer', 'player', 'admin', 'super_admin') NOT NULL");
            }
        }
    }
};
