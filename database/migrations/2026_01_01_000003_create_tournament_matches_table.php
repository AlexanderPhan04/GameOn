<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 3: Tạo bảng tournament_matches
 * 
 * Vấn đề: Thiếu bảng track từng trận đấu trong giải
 * Giải pháp: Tạo bảng để quản lý bracket, kết quả, lịch thi đấu
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->string('round_name', 50)->comment('Round of 16, Quarterfinal, Semi-final, Final');
            $table->integer('round_number')->default(1);
            $table->integer('match_number')->default(1);
            $table->enum('bracket_type', ['winners', 'losers', 'grand_final'])->default('winners');

            // Participants (polymorphic - có thể là user hoặc team)
            $table->unsignedBigInteger('participant_1_id')->nullable();
            $table->string('participant_1_type', 20)->nullable()->comment('user hoặc team');
            $table->unsignedBigInteger('participant_2_id')->nullable();
            $table->string('participant_2_type', 20)->nullable()->comment('user hoặc team');

            // Results
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->string('winner_type', 20)->nullable();
            $table->integer('score_1')->nullable();
            $table->integer('score_2')->nullable();
            $table->json('game_scores')->nullable()->comment('Scores per game/map');

            // Status & Schedule
            $table->enum('status', ['pending', 'scheduled', 'live', 'completed', 'cancelled', 'walkover'])
                ->default('pending');
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();

            // Stream & VOD
            $table->string('stream_url')->nullable();
            $table->string('vod_url')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['tournament_id', 'round_number', 'match_number'], 'idx_tournament_round_match');
            $table->index(['tournament_id', 'status']);
            $table->index(['scheduled_at']);
            $table->index(['participant_1_id', 'participant_1_type'], 'idx_participant_1');
            $table->index(['participant_2_id', 'participant_2_type'], 'idx_participant_2');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
    }
};
