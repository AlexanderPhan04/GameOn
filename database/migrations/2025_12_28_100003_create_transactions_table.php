<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng transactions để quản lý tập trung mọi giao dịch thanh toán
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'purchase', 'donation', 'refund'])->notNull();
            $table->decimal('amount', 15, 2)->notNull();
            $table->string('currency', 10)->default('VND');
            $table->enum('payment_method', ['vnpay', 'momo', 'credit_card', 'wallet'])->notNull();
            $table->string('payment_gateway_ref')->nullable()->comment('Mã giao dịch VNPAY/Momo');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('payment_gateway_ref');
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

