<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Loại bỏ các cột đã tách sang user_profiles và user_activities khỏi bảng users
     * CHỈ CHẠY SAU KHI đã migrate dữ liệu sang user_profiles và user_activities
     */
    public function up(): void
    {
        // Loại bỏ các cột profile (đã chuyển sang user_profiles)
        $columnsToRemove = [
            'full_name',
            'avatar',
            'bio',
            'date_of_birth',
            'phone',
            'country',
            'gaming_nickname',
            'team_preference',
            'id_app',
            'description',
            'upgraded_to_player_at',
            'is_verified',
            'verified_at',
            // Activity columns
            'last_login',
            'last_seen_at',
            'online_status',
            'is_typing',
            'typing_started_at',
            'last_activity_at',
        ];

        // SQLite cần drop từng column riêng biệt
        foreach ($columnsToRemove as $column) {
            if (Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm lại các cột profile
            $table->string('full_name')->nullable()->after('name');
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('gaming_nickname')->nullable()->after('name');
            $table->text('team_preference')->nullable();
            $table->string('id_app', 50)->nullable()->unique();
            $table->text('description')->nullable();
            $table->timestamp('upgraded_to_player_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            // Thêm lại các cột activity
            $table->timestamp('last_login')->nullable()->after('status');
            $table->timestamp('last_seen_at')->nullable();
            $table->enum('online_status', ['online', 'away', 'busy', 'offline'])->default('offline');
            $table->boolean('is_typing')->default(false);
            $table->timestamp('typing_started_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
        });
    }
};

