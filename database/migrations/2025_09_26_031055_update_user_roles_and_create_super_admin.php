<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cập nhật tài khoản quanpn.1140101240014@vtc.edu.vn thành player
        DB::table('users')
            ->where('email', 'quanpn.1140101240014@vtc.edu.vn')
            ->update([
                'user_role' => 'player',
                'updated_at' => now(),
            ]);

        // Note: Tạo các tài khoản admin sẽ được thực hiện sau khi có cột status
        // Tạm thời comment để tránh lỗi khi chưa có cột status
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: đặt lại role cũ cho user quanpn
        DB::table('users')
            ->where('email', 'quanpn.1140101240014@vtc.edu.vn')
            ->update([
                'user_role' => 'admin', // hoặc role cũ
                'updated_at' => now(),
            ]);
    }
};
