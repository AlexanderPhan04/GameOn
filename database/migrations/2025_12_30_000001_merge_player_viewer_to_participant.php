<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm trường is_verified_gamer cho tích xanh
     * Migrate data cũ (nếu có) từ player/viewer sang participant
     */
    public function up(): void
    {
        // Thêm cột is_verified_gamer vào bảng users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_verified_gamer')->default(false)->after('user_role')
                ->comment('Tích xanh cho gamer nổi tiếng');
        });

        // Migrate data cũ (nếu có): chuyển player/viewer thành participant
        DB::table('users')
            ->whereIn('user_role', ['player', 'viewer'])
            ->update(['user_role' => 'participant']);

        // Cấp tích xanh cho user cũ có gaming_nickname (nếu có data)
        if (Schema::hasTable('user_profiles')) {
            DB::table('users')
                ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->whereNotNull('user_profiles.gaming_nickname')
                ->where('user_profiles.gaming_nickname', '!=', '')
                ->update(['users.is_verified_gamer' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_verified_gamer');
        });
    }
};
