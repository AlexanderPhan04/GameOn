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
        Schema::create('user_inventory', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('marketplace_products')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('marketplace_orders')->onDelete('set null');
            
            // Thông tin sở hữu
            $table->integer('quantity')->default(1); // Số lượng (cho sticker pack, currency)
            $table->boolean('is_equipped')->default(false); // Đang sử dụng (cho theme, avatar frame)
            $table->enum('equipment_slot', ['theme', 'avatar_frame', 'sticker', 'emote'])->nullable(); // Vị trí trang bị
            
            // Metadata
            $table->json('custom_data')->nullable(); // Dữ liệu tùy chỉnh
            $table->timestamp('expires_at')->nullable(); // Hết hạn (nếu có)
            $table->boolean('is_gift')->default(false); // Là quà tặng
            $table->foreignId('gifted_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['user_id', 'product_id', 'order_id']); // Mỗi user chỉ có 1 item từ 1 order
            $table->index(['user_id', 'is_equipped']);
            $table->index(['user_id', 'equipment_slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_inventory');
    }
};
