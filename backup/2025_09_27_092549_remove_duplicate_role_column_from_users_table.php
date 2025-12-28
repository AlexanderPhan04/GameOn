<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Sync data from role to user_role if user_role is null or empty
            DB::statement("UPDATE users SET user_role = role WHERE user_role IS NULL OR user_role = ''");

            // Drop the duplicate role column
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add role column if needed
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('viewer')->after('email');

                // Sync data back from user_role to role
                DB::statement('UPDATE users SET role = user_role WHERE user_role IS NOT NULL');
            }
        });
    }
};
