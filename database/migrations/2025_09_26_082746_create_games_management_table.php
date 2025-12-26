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
        Schema::create('games_management', function (Blueprint $table) {
            $table->id();

            // 1. Thông tin cơ bản
            $table->string('name'); // Tên game (bắt buộc)
            $table->string('genre')->nullable(); // Thể loại (MOBA, FPS, Battle Royale, Sports)
            $table->string('publisher')->nullable(); // Nhà phát hành / Studio
            $table->date('release_date')->nullable(); // Ngày phát hành
            $table->enum('status', ['active', 'discontinued'])->default('active'); // Tình trạng

            // 2. Thông tin quản lý trong Esport
            $table->boolean('esport_support')->default(false); // Có hỗ trợ thi đấu Esport không
            $table->string('team_size')->nullable(); // Số lượng người chơi mỗi đội (5v5, 1v1, 3v3)
            $table->json('competition_formats')->nullable(); // Hình thức thi đấu (Online, LAN, Mobile, Console, PC)
            $table->json('game_modes')->nullable(); // Các chế độ phổ biến (Ranked, Tournament, Custom Match)

            // 3. Metadata phục vụ hiển thị
            $table->string('logo')->nullable(); // Logo / Icon game
            $table->string('banner')->nullable(); // Ảnh bìa (banner)
            $table->text('description')->nullable(); // Mô tả ngắn
            $table->string('official_website')->nullable(); // Link chính thức

            // Metadata
            $table->unsignedBigInteger('created_by')->nullable(); // Admin tạo
            $table->unsignedBigInteger('updated_by')->nullable(); // Admin cập nhật
            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('genre');
            $table->index('status');
            $table->index('esport_support');

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games_management');
    }
};
