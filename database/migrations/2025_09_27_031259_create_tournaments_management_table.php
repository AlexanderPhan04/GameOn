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
        Schema::create('tournaments_management', function (Blueprint $table) {
            $table->id();

            // 1. Thông tin cơ bản
            $table->string('name');
            $table->unsignedBigInteger('game_id');
            $table->enum('competition_type', ['individual', 'team'])->default('team'); // cá nhân / đội tuyển
            $table->string('format')->nullable(); // 1v1, 5v5, etc.
            $table->text('description')->nullable();
            $table->string('banner')->nullable();
            $table->string('logo')->nullable();

            // 2. Thời gian & địa điểm
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('location_type', ['online', 'lan', 'physical'])->default('online');
            $table->string('location_address')->nullable(); // địa chỉ cụ thể nếu LAN/physical
            $table->time('scheduled_time')->nullable(); // giờ thi đấu dự kiến

            // 3. Cấu trúc & luật chơi
            $table->enum('tournament_format', [
                'single_elimination',
                'double_elimination',
                'round_robin',
                'swiss_system',
            ])->default('single_elimination');
            $table->integer('max_participants')->default(16); // số lượng đội/người tối đa
            $table->integer('substitute_players')->default(0); // số người dự bị
            $table->json('rules_details')->nullable(); // luật chi tiết (map pool, BO format, etc.)

            // 4. Quản lý & phần thưởng
            $table->string('organizer_name')->nullable(); // ban tổ chức
            $table->string('organizer_contact')->nullable(); // contact info
            $table->json('referees')->nullable(); // danh sách trọng tài
            $table->json('prize_structure')->nullable(); // cơ cấu giải thưởng
            $table->json('sponsors')->nullable(); // nhà tài trợ

            // 5. Hệ thống & hiển thị
            $table->enum('status', [
                'draft', // chưa mở đăng ký
                'registration_open', // đang đăng ký
                'ongoing', // đang diễn ra
                'completed', // đã kết thúc
                'cancelled', // bị hủy
            ])->default('draft');
            $table->enum('participation_type', ['public', 'invite_only'])->default('public');
            $table->string('stream_link')->nullable(); // link phát sóng
            $table->json('hashtags')->nullable(); // hashtag / keywords

            // Metadata
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('game_id')->references('id')->on('games_management')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['status', 'start_date']);
            $table->index('game_id');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments_management');
    }
};
