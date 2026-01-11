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
        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->id();
            
            // Thông tin đơn hàng
            $table->string('order_id')->unique(); // Mã đơn hàng
            $table->string('order_code')->nullable(); // Mã PayOS
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Tổng tiền
            $table->decimal('total_amount', 15, 2); // Tổng tiền
            $table->decimal('discount_amount', 15, 2)->default(0); // Tiền giảm
            $table->decimal('final_amount', 15, 2); // Tiền cuối cùng
            
            // Trạng thái
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled, refunded
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_method')->nullable(); // payos, wallet, other
            
            // Thông tin thanh toán PayOS
            $table->string('payos_transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Thông tin giao hàng (nếu cần)
            $table->text('notes')->nullable(); // Ghi chú
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['status', 'payment_status']);
            $table->index('order_id');
            $table->index('created_at');
        });
        
        // Bảng chi tiết đơn hàng
        Schema::create('marketplace_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('marketplace_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('marketplace_products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2); // Giá tại thời điểm mua
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2); // Tổng tiền = (price - discount) * quantity
            
            $table->timestamps();
            
            $table->index(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_orders');
    }
};
