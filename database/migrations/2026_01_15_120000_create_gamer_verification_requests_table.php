<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gamer_verification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('game_name'); // Tên game chính
            $table->string('in_game_name'); // Tên trong game
            $table->string('in_game_id')->nullable(); // ID trong game
            $table->string('rank_tier')->nullable(); // Rank/Tier hiện tại
            $table->text('achievements')->nullable(); // Thành tích (giải đấu, rank cao nhất, etc.)
            $table->text('proof_links')->nullable(); // Links chứng minh (profile game, video, etc.)
            $table->string('proof_image')->nullable(); // Ảnh chứng minh (screenshot rank, etc.)
            $table->text('additional_info')->nullable(); // Thông tin bổ sung
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_note')->nullable(); // Ghi chú của admin khi duyệt/từ chối
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gamer_verification_requests');
    }
};
