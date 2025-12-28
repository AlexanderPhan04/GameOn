<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tách trạng thái online/activity khỏi bảng users để tránh lock row
     */
    public function up(): void
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id('user_id');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->enum('online_status', ['online', 'away', 'busy', 'offline'])->default('offline');
            $table->boolean('is_typing')->default(false);
            $table->timestamp('typing_started_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('last_seen_at');
            $table->index('online_status');
            $table->index('last_activity_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};

