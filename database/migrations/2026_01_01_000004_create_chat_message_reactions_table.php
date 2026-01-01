<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 4: Tạo bảng chat_message_reactions
 * 
 * Vấn đề: chat_messages.reactions là JSON, không thể query/index hiệu quả
 * Giải pháp: Tách thành bảng riêng để có thể query, count, aggregate
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')
                ->constrained('chat_messages')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('type', 50)->comment('emoji code hoặc reaction type: like, love, haha, wow, sad, angry');
            $table->timestamps();

            // Mỗi user chỉ có 1 reaction trên mỗi message
            $table->unique(['message_id', 'user_id']);
            $table->index(['message_id', 'type']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_message_reactions');
    }
};
