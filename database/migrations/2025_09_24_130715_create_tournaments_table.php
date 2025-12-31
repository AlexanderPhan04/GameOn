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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('created_by');
            $table->decimal('prize_pool', 10, 2)->default(0);
            $table->integer('max_teams')->default(32);
            $table->enum('format', ['single_elimination', 'double_elimination', 'round_robin', 'swiss_system'])->default('single_elimination');
            $table->enum('status', ['registration', 'ongoing', 'completed', 'cancelled'])->default('registration');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->dateTime('registration_deadline');
            $table->string('image_url')->nullable();
            $table->json('rules')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->index(['name', 'status']);
            $table->index(['game_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
