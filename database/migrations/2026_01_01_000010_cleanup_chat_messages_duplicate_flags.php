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
 * 
 * Note: SQLite không hỗ trợ DROP COLUMN khi có index,
 *       nên trên SQLite chỉ đồng bộ data, không drop column.
 */
return new class extends Migration
{
    public function up(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // Sync data dùng PHP thay vì raw SQL (tương thích SQLite)
        if (Schema::hasColumn('chat_messages', 'is_deleted')) {
            $messages = DB::table('chat_messages')
                ->where('is_deleted', 1)
                ->whereNull('deleted_at')
                ->get(['id']);

            foreach ($messages as $message) {
                DB::table('chat_messages')
                    ->where('id', $message->id)
                    ->update(['deleted_at' => now()]);
            }
        }

        if (Schema::hasColumn('chat_messages', 'is_edited')) {
            $messages = DB::table('chat_messages')
                ->where('is_edited', 1)
                ->whereNull('edited_at')
                ->get(['id', 'updated_at']);

            foreach ($messages as $message) {
                DB::table('chat_messages')
                    ->where('id', $message->id)
                    ->update(['edited_at' => $message->updated_at]);
            }
        }

        // Drop boolean columns - CHỈ trên MySQL/MariaDB
        // SQLite không hỗ trợ DROP COLUMN với composite index
        if (! $isSqlite) {
            // Drop index trước
            try {
                Schema::table('chat_messages', function (Blueprint $table) {
                    $table->dropIndex(['type', 'is_deleted']);
                });
            } catch (\Exception $e) {
                // Index có thể không tồn tại
            }

            Schema::table('chat_messages', function (Blueprint $table) {
                if (Schema::hasColumn('chat_messages', 'is_deleted')) {
                    $table->dropColumn('is_deleted');
                }
                if (Schema::hasColumn('chat_messages', 'is_edited')) {
                    $table->dropColumn('is_edited');
                }
            });
        }

        // Thêm index cho deleted_at và edited_at nếu chưa có
        if (! $isSqlite) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->index('deleted_at');
                $table->index('edited_at');
            });
        }
    }

    public function down(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // SQLite không drop column trong up(), nên không cần restore
        if ($isSqlite) {
            return;
        }

        // Thêm lại boolean columns
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['edited_at']);
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false)->after('edited_at');
            $table->boolean('is_edited')->default(false)->after('reactions');
        });

        // Tạo lại composite index
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->index(['type', 'is_deleted']);
        });

        // Sync data ngược lại dùng PHP
        $deletedMessages = DB::table('chat_messages')
            ->whereNotNull('deleted_at')
            ->get(['id']);

        foreach ($deletedMessages as $message) {
            DB::table('chat_messages')
                ->where('id', $message->id)
                ->update(['is_deleted' => 1]);
        }

        $editedMessages = DB::table('chat_messages')
            ->whereNotNull('edited_at')
            ->get(['id']);

        foreach ($editedMessages as $message) {
            DB::table('chat_messages')
                ->where('id', $message->id)
                ->update(['is_edited' => 1]);
        }
    }
};
