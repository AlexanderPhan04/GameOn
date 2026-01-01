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
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // Step 1: Thêm column mới
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('role_new', 50)->default('member')->after('role');
        });

        // Step 2: Copy data từ column cũ sang mới (dùng PHP)
        $members = DB::table('team_members')->get(['id', 'role']);
        foreach ($members as $member) {
            DB::table('team_members')
                ->where('id', $member->id)
                ->update(['role_new' => $member->role]);
        }

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
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // Step 1: Thêm column cũ
        Schema::table('team_members', function (Blueprint $table) use ($isSqlite) {
            if ($isSqlite) {
                $table->string('role_old', 20)->default('member')->after('role');
            } else {
                $table->enum('role_old', ['member', 'captain'])->default('member')->after('role');
            }
        });

        // Step 2: Copy data (chỉ giữ member và captain, dùng PHP)
        $members = DB::table('team_members')->get(['id', 'role']);
        foreach ($members as $member) {
            $roleOld = in_array($member->role, ['member', 'captain']) ? $member->role : 'member';
            DB::table('team_members')
                ->where('id', $member->id)
                ->update(['role_old' => $roleOld]);
        }

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
