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
        Schema::create('honor_events', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên đợt vote (VD: "MVP Mùa Thu 2025")
            $table->text('description')->nullable(); // Mô tả đợt vote
            $table->enum('mode', ['free', 'event'])->default('free'); // Chế độ vote
            $table->enum('target_type', ['player', 'team', 'tournament', 'game']); // Đối tượng vote
            $table->datetime('start_time')->nullable(); // Thời gian bắt đầu (null = ngay lập tức)
            $table->datetime('end_time')->nullable(); // Thời gian kết thúc (null = không giới hạn)
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->boolean('allow_viewer_vote')->default(true); // Cho phép Viewer vote
            $table->boolean('allow_player_vote')->default(true); // Cho phép Player vote
            $table->boolean('allow_admin_vote')->default(true); // Cho phép Admin vote
            $table->boolean('allow_anonymous')->default(true); // Cho phép vote ẩn danh
            $table->decimal('viewer_weight', 3, 1)->default(1.0); // Trọng số Viewer
            $table->decimal('player_weight', 3, 1)->default(1.5); // Trọng số Player
            $table->decimal('admin_weight', 3, 1)->default(2.0); // Trọng số Admin
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Super Admin tạo
            $table->timestamps();

            // Index
            $table->index(['is_active', 'mode']);
            $table->index(['start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('honor_events');
    }
};
