<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First drop foreign keys that might be using the index
        Schema::table('team_invitations', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        // Now drop the unique constraint
        Schema::table('team_invitations', function (Blueprint $table) {
            $table->dropUnique('team_invitations_team_id_user_id_status_unique');
        });

        // Re-add the foreign key
        Schema::table('team_invitations', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('team_invitations', function (Blueprint $table) {
            $table->unique(['team_id', 'user_id', 'status'], 'team_invitations_team_id_user_id_status_unique');
        });
    }
};
