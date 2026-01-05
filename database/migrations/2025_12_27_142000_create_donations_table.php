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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            
            // Thông tin quyên góp
            $table->string('donation_id')->unique(); // Mã quyên góp
            $table->foreignId('donor_id')->constrained('users')->onDelete('cascade'); // Người quyên góp
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade'); // Người nhận (player)
            
            // Số tiền
            $table->decimal('amount', 15, 2); // Số tiền quyên góp
            $table->text('message')->nullable(); // Lời nhắn kèm theo
            
            // Trạng thái
            $table->string('status')->default('pending'); // pending, completed, cancelled, refunded
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_method')->nullable(); // payos, wallet, other
            
            // Thông tin thanh toán PayOS
            $table->string('order_code')->nullable();
            $table->string('payos_transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Ẩn danh
            $table->boolean('is_anonymous')->default(false); // Quyên góp ẩn danh
            
            $table->timestamps();
            
            // Indexes
            $table->index(['donor_id', 'status']);
            $table->index(['recipient_id', 'status']);
            $table->index('donation_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
