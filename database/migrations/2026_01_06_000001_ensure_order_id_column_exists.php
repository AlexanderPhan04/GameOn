<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Chỉ thêm cột nếu chưa tồn tại
        if (!Schema::hasColumn('marketplace_orders', 'order_id')) {
            Schema::table('marketplace_orders', function (Blueprint $table) {
                $table->string('order_id')->nullable()->after('id');
            });
            
            // Thêm unique index riêng
            Schema::table('marketplace_orders', function (Blueprint $table) {
                $table->unique('order_id');
            });
            
            // Copy data từ order_code nếu có
            if (Schema::hasColumn('marketplace_orders', 'order_code')) {
                DB::statement('UPDATE marketplace_orders SET order_id = order_code WHERE order_id IS NULL AND order_code IS NOT NULL');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('marketplace_orders', 'order_id')) {
            Schema::table('marketplace_orders', function (Blueprint $table) {
                $table->dropColumn('order_id');
            });
        }
    }
};
