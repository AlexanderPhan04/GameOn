<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 2: Tạo bảng tournament_registrations
 * 
 * Vấn đề: Thiếu bảng track đăng ký giải đấu
 * Giải pháp: Tạo bảng mới cho individual và team registrations
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('participant_type', ['individual', 'team'])->default('individual');
            $table->enum('status', ['pending', 'approved', 'rejected', 'withdrawn', 'checked_in'])
                ->default('pending');
            $table->integer('seed')->nullable()->comment('Seeding/ranking for bracket');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Constraints - mỗi user/team chỉ đăng ký 1 lần cho mỗi giải
            $table->unique(['tournament_id', 'user_id'], 'unique_user_registration');
            $table->unique(['tournament_id', 'team_id'], 'unique_team_registration');
            $table->index(['tournament_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['team_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_registrations');
    }
};
