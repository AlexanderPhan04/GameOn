<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Xóa các bảng trùng lặp không còn sử dụng
     * LƯU Ý: Chạy migration này SAU KHI đã migrate dữ liệu và kiểm tra kỹ
     */
    public function up(): void
    {
        $isSqlite = config('database.default') === 'sqlite' || 
                    (config('database.connections.'.config('database.default').'.driver') === 'sqlite');
        
        // Xóa foreign keys trước khi xóa bảng (chỉ MySQL)
        if (!$isSqlite && Schema::hasTable('tournaments_management')) {
            try {
                DB::statement('ALTER TABLE tournaments_management DROP FOREIGN KEY tournaments_management_game_id_foreign');
            } catch (\Exception $e) {
                // Foreign key có thể đã không tồn tại
            }
        }
        
        // Xóa bảng tournaments_management trước (vì có foreign key đến games_management)
        Schema::dropIfExists('tournaments_management');
        
        // Xóa bảng games_management (đã gộp vào games)
        Schema::dropIfExists('games_management');
        
        // Xóa bảng esports_users (đã gộp vào users)
        Schema::dropIfExists('esports_users');
        
        // Xóa bảng chat_typing_indicators (nên dùng Redis thay vì DB)
        Schema::dropIfExists('chat_typing_indicators');
    }

    /**
     * Reverse the migrations.
     * KHÔNG THỂ ROLLBACK - dữ liệu đã bị xóa
     */
    public function down(): void
    {
        // Không thể rollback vì đã xóa dữ liệu
        // Cần restore từ backup nếu cần
    }
};

