<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration 8: Mở rộng team_members.role để support các vai trò esports
 * 
 * Vấn đề: ENUM chỉ có 'member', 'captain' - thiếu 'coach', 'manager', 'sub', 'analyst'
 * Giải pháp: Thêm column mới với VARCHAR, migrate data, xóa column cũ
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Thêm column mới
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('role_new', 50)->default('member')->after('role');
        });

        // Step 2: Copy data từ column cũ sang mới
        DB::statement('UPDATE team_members SET role_new = role');

        // Step 3: Drop column cũ
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Step 4: Rename column mới
        Schema::table('team_members', function (Blueprint $table) {
            $table->renameColumn('role_new', 'role');
        });
    }

    public function down(): void
    {
        // Step 1: Thêm column ENUM cũ
        Schema::table('team_members', function (Blueprint $table) {
            $table->enum('role_old', ['member', 'captain'])->default('member')->after('role');
        });

        // Step 2: Copy data (chỉ giữ member và captain)
        DB::statement("
            UPDATE team_members 
            SET role_old = CASE 
                WHEN role IN ('member', 'captain') THEN role 
                ELSE 'member' 
            END
        ");

        // Step 3: Drop column VARCHAR
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Step 4: Rename
        Schema::table('team_members', function (Blueprint $table) {
            $table->renameColumn('role_old', 'role');
        });
    }
};
