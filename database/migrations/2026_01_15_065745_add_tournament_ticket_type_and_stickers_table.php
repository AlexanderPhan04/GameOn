<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Thêm type 'tournament_ticket' vào marketplace_products
        DB::statement("ALTER TABLE marketplace_products MODIFY COLUMN type ENUM('theme', 'sticker', 'game_item', 'donation', 'tournament_ticket') NOT NULL");
        
        // 2. Thêm cột tournament_id cho vé giải đấu
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->foreignId('tournament_id')->nullable()->after('game_id')->constrained('tournaments')->onDelete('cascade');
        });

        // 3. Tạo bảng stickers (từng sticker trong pack)
        Schema::create('stickers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pack_id')->constrained('marketplace_products')->onDelete('cascade');
            $table->string('name');
            $table->string('image_path'); // Path to sticker image
            $table->string('emoji_code')->nullable(); // Optional emoji shortcode like :gg:
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['pack_id', 'is_active']);
        });

        // 4. Tạo bảng user_stickers (sticker user đã mua/sở hữu)
        Schema::create('user_stickers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pack_id')->constrained('marketplace_products')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('marketplace_orders')->onDelete('set null');
            $table->timestamp('purchased_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['user_id', 'pack_id']); // User chỉ mua 1 lần mỗi pack
            $table->index('user_id');
        });

        // 5. Tạo bảng tournament_tickets (vé đã mua)
        Schema::create('tournament_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained('marketplace_products')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('marketplace_orders')->onDelete('set null');
            $table->string('ticket_code')->unique(); // Mã vé unique
            $table->enum('status', ['valid', 'used', 'expired', 'refunded'])->default('valid');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('purchased_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['tournament_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('ticket_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_tickets');
        Schema::dropIfExists('user_stickers');
        Schema::dropIfExists('stickers');
        
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropColumn('tournament_id');
        });
        
        DB::statement("ALTER TABLE marketplace_products MODIFY COLUMN type ENUM('theme', 'sticker', 'game_item', 'donation') NOT NULL");
    }
};
