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
        Schema::create('marketplace_products', function (Blueprint $table) {
            $table->id();
            
            // Thông tin cơ bản
            $table->string('name'); // Tên sản phẩm
            $table->text('description')->nullable(); // Mô tả
            $table->enum('type', ['theme', 'sticker', 'game_item', 'donation']); // Loại sản phẩm
            $table->enum('category', ['ui_theme', 'avatar_frame', 'sticker_pack', 'emote', 'weapon_skin', 'character_skin', 'currency', 'other'])->default('other');
            
            // Giá và bán hàng
            $table->decimal('price', 15, 2); // Giá (VNĐ)
            $table->decimal('discount_price', 15, 2)->nullable(); // Giá giảm
            $table->boolean('is_active')->default(true); // Trạng thái
            $table->boolean('is_featured')->default(false); // Sản phẩm nổi bật
            $table->integer('stock')->default(-1); // Số lượng (-1 = unlimited)
            $table->integer('sold_count')->default(0); // Số lượng đã bán
            
            // Hình ảnh và media
            $table->string('thumbnail')->nullable(); // Ảnh thumbnail
            $table->json('images')->nullable(); // Mảng ảnh
            $table->string('preview_url')->nullable(); // URL preview (cho theme)
            
            // Metadata
            $table->json('metadata')->nullable(); // Dữ liệu bổ sung (màu sắc, kích thước, etc.)
            $table->string('game_id')->nullable(); // ID game nếu là vật phẩm game
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary'])->nullable(); // Độ hiếm
            
            // Quản lý
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            
            $table->timestamps();
            
            // Indexes
            $table->index(['type', 'is_active']);
            $table->index(['category', 'is_active']);
            $table->index('is_featured');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_products');
    }
};
