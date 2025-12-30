<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing users to have active status
        DB::table('users')
            ->whereNull('status')
            ->orWhere('status', '')
            ->update([
                'status' => 'active',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to rollback as this is just setting default values
    }
};
