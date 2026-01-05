<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cập nhật dữ liệu cũ từ vnpay sang payos trước
        DB::table('marketplace_orders')->where('payment_method', 'vnpay')->update(['payment_method' => 'other']);
        DB::table('marketplace_orders')->where('payment_method', 'zalopay')->update(['payment_method' => 'other']);
        
        // Cập nhật marketplace_orders
        Schema::table('marketplace_orders', function (Blueprint $table) {
            // Đổi tên cột vnpay thành payos
            if (Schema::hasColumn('marketplace_orders', 'vnpay_transaction_no')) {
                $table->renameColumn('vnpay_transaction_no', 'payos_transaction_id');
            }
            if (Schema::hasColumn('marketplace_orders', 'vnpay_bank_code')) {
                $table->dropColumn('vnpay_bank_code');
            }
            
            // Xóa cột zalopay nếu có
            if (Schema::hasColumn('marketplace_orders', 'zalopay_trans_id')) {
                $table->dropColumn('zalopay_trans_id');
            }
            if (Schema::hasColumn('marketplace_orders', 'zalopay_zp_trans_id')) {
                $table->dropColumn('zalopay_zp_trans_id');
            }
        });

        // Cập nhật payment_method enum
        DB::statement("ALTER TABLE marketplace_orders MODIFY COLUMN payment_method ENUM('payos', 'wallet', 'other') NULL");
        
        // Cập nhật lại giá trị other thành payos
        DB::table('marketplace_orders')->where('payment_method', 'other')->update(['payment_method' => 'payos']);

        // Cập nhật donations
        if (Schema::hasTable('donations')) {
            DB::table('donations')->where('payment_method', 'vnpay')->update(['payment_method' => 'other']);
            
            Schema::table('donations', function (Blueprint $table) {
                if (Schema::hasColumn('donations', 'vnpay_transaction_no')) {
                    $table->renameColumn('vnpay_transaction_no', 'payos_transaction_id');
                }
                if (Schema::hasColumn('donations', 'vnpay_bank_code')) {
                    $table->dropColumn('vnpay_bank_code');
                }
                
                // Thêm cột order_code nếu chưa có
                if (!Schema::hasColumn('donations', 'order_code')) {
                    $table->string('order_code')->nullable()->after('donation_id');
                }
            });

            // Cập nhật payment_method enum cho donations
            DB::statement("ALTER TABLE donations MODIFY COLUMN payment_method ENUM('payos', 'wallet', 'other') NULL");
            DB::table('donations')->where('payment_method', 'other')->update(['payment_method' => 'payos']);
        }
    }

    public function down(): void
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            if (Schema::hasColumn('marketplace_orders', 'payos_transaction_id')) {
                $table->renameColumn('payos_transaction_id', 'vnpay_transaction_no');
            }
            $table->string('vnpay_bank_code')->nullable();
        });

        DB::statement("ALTER TABLE marketplace_orders MODIFY COLUMN payment_method ENUM('vnpay', 'wallet', 'other') NULL");

        if (Schema::hasTable('donations')) {
            Schema::table('donations', function (Blueprint $table) {
                if (Schema::hasColumn('donations', 'payos_transaction_id')) {
                    $table->renameColumn('payos_transaction_id', 'vnpay_transaction_no');
                }
                $table->string('vnpay_bank_code')->nullable();
            });

            DB::statement("ALTER TABLE donations MODIFY COLUMN payment_method ENUM('vnpay', 'wallet', 'other') NULL");
        }
    }
};
