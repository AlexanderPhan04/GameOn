<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Copy avatar URL vào google_avatar cho các user đã có avatar là URL Google
     */
    public function up(): void
    {
        // Copy avatar URL sang google_avatar nếu avatar là URL (không phải local file)
        DB::table('user_profiles')
            ->whereNotNull('avatar')
            ->where('avatar', 'LIKE', 'http%')
            ->whereNull('google_avatar')
            ->update(['google_avatar' => DB::raw('avatar')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì chỉ là copy data
    }
};
