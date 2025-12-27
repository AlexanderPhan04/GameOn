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
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('payment_method', ['vnpay', 'wallet', 'other'])->nullable();
            
            // Thông tin thanh toán VNPay
            $table->string('vnpay_transaction_no')->nullable();
            $table->string('vnpay_bank_code')->nullable();
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
