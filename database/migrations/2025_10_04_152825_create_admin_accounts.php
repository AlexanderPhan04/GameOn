<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tạo tài khoản Super Admin
        DB::table('users')->insert([
            'name' => 'superadmin',
            'email' => 'superadmin@esportmanager.com',
            'password' => Hash::make('SuperAdmin@2024'),
            'user_role' => 'super_admin',
            'status' => 'active',
            'full_name' => 'Super Administrator',
            'id_app' => 'SA001',
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo tài khoản admin thông thường
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@esportmanager.com',
            'password' => Hash::make('Admin@2024'),
            'user_role' => 'admin',
            'status' => 'active',
            'full_name' => 'System Administrator',
            'id_app' => 'ADM001',
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo tài khoản viewer cho demo
        DB::table('users')->insert([
            'name' => 'viewer',
            'email' => 'viewer@esportmanager.com',
            'password' => Hash::make('Viewer@2024'),
            'user_role' => 'viewer',
            'status' => 'active',
            'full_name' => 'System Viewer',
            'id_app' => 'VW001',
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo tài khoản player cho demo
        DB::table('users')->insert([
            'name' => 'player',
            'email' => 'player@esportmanager.com',
            'password' => Hash::make('Player@2024'),
            'user_role' => 'player',
            'status' => 'active',
            'full_name' => 'Demo Player',
            'id_app' => 'PL001',
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo tài khoản manager cho demo
        DB::table('users')->insert([
            'name' => 'manager',
            'email' => 'manager@esportmanager.com',
            'password' => Hash::make('Manager@2024'),
            'user_role' => 'manager',
            'status' => 'active',
            'full_name' => 'Team Manager',
            'id_app' => 'MG001',
            'google_id' => null,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa các tài khoản đã tạo
        DB::table('users')->whereIn('email', [
            'superadmin@esportmanager.com',
            'admin@esportmanager.com',
            'viewer@esportmanager.com',
            'player@esportmanager.com',
            'manager@esportmanager.com',
        ])->delete();
    }
};
