<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livestreams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('embed_url'); // URL embed từ Facebook/YouTube
            $table->string('platform'); // facebook, youtube
            $table->string('status')->default('scheduled'); // scheduled, live, ended
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->foreignId('game_id')->nullable()->constrained('games')->onDelete('set null');
            $table->foreignId('tournament_id')->nullable()->constrained('tournaments')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('view_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['status', 'is_active']);
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livestreams');
    }
};
