<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm 'draft' vào ENUM status
        DB::statement("ALTER TABLE tournaments MODIFY COLUMN status ENUM('draft', 'registration', 'ongoing', 'completed', 'cancelled') DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Chuyển các draft về registration trước khi xóa
        DB::table('tournaments')->where('status', 'draft')->update(['status' => 'registration']);
        
        DB::statement("ALTER TABLE tournaments MODIFY COLUMN status ENUM('registration', 'ongoing', 'completed', 'cancelled') DEFAULT 'registration'");
    }
};
