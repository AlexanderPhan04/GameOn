<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tách thông tin profile khỏi bảng users để tối ưu hiệu năng
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('full_name')->nullable();
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('gaming_nickname')->nullable();
            $table->text('team_preference')->nullable();
            $table->string('id_app', 50)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('upgraded_to_player_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('id_app');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};

