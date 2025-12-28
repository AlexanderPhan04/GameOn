<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm transaction_id vào marketplace_orders để link với bảng transactions
     */
    public function up(): void
    {
        // Kiểm tra bảng tồn tại trước khi thêm cột
        if (!Schema::hasTable('marketplace_orders')) {
            return; // Bảng chưa tồn tại, bỏ qua migration này
        }

        $isSqlite = config('database.default') === 'sqlite' || 
                    (config('database.connections.'.config('database.default').'.driver') === 'sqlite');

        Schema::table('marketplace_orders', function (Blueprint $table) use ($isSqlite) {
            if (!Schema::hasColumn('marketplace_orders', 'transaction_id')) {
                if ($isSqlite) {
                    $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
                } else {
                    $table->foreignId('transaction_id')->nullable()->after('id')->constrained('transactions')->onDelete('set null');
                }
                $table->index('transaction_id');
            }
            // Đổi order_id thành order_code để nhất quán (chỉ cho MySQL, SQLite không hỗ trợ renameColumn dễ dàng)
            if (!$isSqlite && Schema::hasColumn('marketplace_orders', 'order_id') && !Schema::hasColumn('marketplace_orders', 'order_code')) {
                $table->renameColumn('order_id', 'order_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropIndex(['transaction_id']);
            $table->dropColumn('transaction_id');
            if (Schema::hasColumn('marketplace_orders', 'order_code')) {
                $table->renameColumn('order_code', 'order_id');
            }
        });
    }
};

