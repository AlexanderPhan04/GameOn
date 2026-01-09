<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 5: Tạo bảng post_visibility_users
 * 
 * Vấn đề: posts.visibility_include_ids và visibility_exclude_ids là TEXT
 *         chứa IDs dạng string, không có FK, không query được
 * Giải pháp: Tách thành bảng pivot với proper FK
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_visibility_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('type', ['include', 'exclude'])->default('include');
            $table->timestamps();

            // Mỗi user chỉ xuất hiện 1 lần trong visibility list của mỗi post
            $table->unique(['post_id', 'user_id']);
            $table->index(['user_id', 'type']);
            $table->index(['post_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_visibility_users');
    }
};
