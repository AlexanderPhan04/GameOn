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
            $table->enum('online_status', ['online', 'away', 'busy', 'offline'])->default('offline');
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_typing')->default(false);
            $table->timestamp('typing_started_at')->nullable();

            $table->index('online_status');
            $table->index('last_seen_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
