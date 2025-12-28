<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sửa foreign key của user_inventory để ON DELETE SET NULL thay vì CASCADE
     * Tránh mất đồ của user khi xóa sản phẩm
     */
    public function up(): void
    {
        // Kiểm tra và xóa foreign key cũ nếu tồn tại
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'user_inventory' 
            AND COLUMN_NAME = 'product_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (!empty($foreignKeys)) {
            Schema::table('user_inventory', function (Blueprint $table) {
                // Xóa foreign key cũ
                $table->dropForeign(['product_id']);
            });
        }

        Schema::table('user_inventory', function (Blueprint $table) {
            // Đổi cột product_id thành nullable trước
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });

        Schema::table('user_inventory', function (Blueprint $table) {
            // Tạo lại foreign key với ON DELETE SET NULL
            $table->foreign('product_id')
                  ->references('id')
                  ->on('marketplace_products')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_inventory', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('user_inventory', function (Blueprint $table) {
            // Đổi lại thành NOT NULL nếu cần
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });

        Schema::table('user_inventory', function (Blueprint $table) {
            $table->foreign('product_id')
                  ->references('id')
                  ->on('marketplace_products')
                  ->onDelete('cascade');
        });
    }
};

