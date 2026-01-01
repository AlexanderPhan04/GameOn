<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration 10: Dọn dẹp các flag trùng lặp trong chat_messages
 * 
 * Vấn đề: 
 *   - is_deleted + deleted_at (trùng)
 *   - is_edited + edited_at (trùng)
 * Giải pháp: Dùng *_at fields, bỏ boolean flags
 */
return new class extends Migration
{
    public function up(): void
    {
        // Sync data: nếu is_deleted = true mà deleted_at = null → set deleted_at = now
        DB::statement("
            UPDATE chat_messages 
            SET deleted_at = NOW() 
            WHERE is_deleted = 1 AND deleted_at IS NULL
        ");

        // Sync data: nếu is_edited = true mà edited_at = null → set edited_at = updated_at
        DB::statement("
            UPDATE chat_messages 
            SET edited_at = updated_at 
            WHERE is_edited = 1 AND edited_at IS NULL
        ");

        // Drop boolean columns
        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'is_deleted')) {
                $table->dropColumn('is_deleted');
            }
            if (Schema::hasColumn('chat_messages', 'is_edited')) {
                $table->dropColumn('is_edited');
            }
        });

        // Thêm index cho deleted_at và edited_at nếu chưa có
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->index('deleted_at');
            $table->index('edited_at');
        });
    }

    public function down(): void
    {
        // Thêm lại boolean columns
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['edited_at']);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false)->after('edited_at');
            $table->boolean('is_edited')->default(false)->after('reactions');
        });

        // Sync data ngược lại
        DB::statement("
            UPDATE chat_messages 
            SET is_deleted = 1 
            WHERE deleted_at IS NOT NULL
        ");

        DB::statement("
            UPDATE chat_messages 
            SET is_edited = 1 
            WHERE edited_at IS NOT NULL
        ");
    }
};
