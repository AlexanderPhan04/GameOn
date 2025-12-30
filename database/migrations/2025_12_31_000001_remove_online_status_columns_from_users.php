<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check and drop columns if they exist
            $columns = Schema::getColumnListing('users');
            
            $columnsToDrop = [];
            
            if (in_array('online_status', $columns)) {
                $columnsToDrop[] = 'online_status';
            }
            if (in_array('last_seen_at', $columns)) {
                $columnsToDrop[] = 'last_seen_at';
            }
            if (in_array('is_typing', $columns)) {
                $columnsToDrop[] = 'is_typing';
            }
            if (in_array('typing_started_at', $columns)) {
                $columnsToDrop[] = 'typing_started_at';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'online_status')) {
                $table->enum('online_status', ['online', 'away', 'busy', 'offline'])->default('offline');
            }
            if (!Schema::hasColumn('users', 'last_seen_at')) {
                $table->timestamp('last_seen_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_typing')) {
                $table->boolean('is_typing')->default(false);
            }
            if (!Schema::hasColumn('users', 'typing_started_at')) {
                $table->timestamp('typing_started_at')->nullable();
            }
        });
    }
};
