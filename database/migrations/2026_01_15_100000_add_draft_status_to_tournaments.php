<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm 'draft' vào ENUM status
        // Chỉ chạy với MySQL, SQLite không hỗ trợ ENUM
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE tournaments MODIFY COLUMN status ENUM('draft', 'registration', 'ongoing', 'completed', 'cancelled') DEFAULT 'draft'");
        }
        // SQLite: status đã là string nên không cần thay đổi
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        
        // Chuyển các draft về registration trước khi xóa
        DB::table('tournaments')->where('status', 'draft')->update(['status' => 'registration']);
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE tournaments MODIFY COLUMN status ENUM('registration', 'ongoing', 'completed', 'cancelled') DEFAULT 'registration'");
        }
    }
};
