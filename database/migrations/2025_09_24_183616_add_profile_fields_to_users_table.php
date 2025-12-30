<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->string('full_name')->nullable()->after('name');
            $table->string('id_app', 50)->unique()->nullable()->after('full_name');
            $table->enum('user_role', ['admin', 'player', 'viewer'])->default('player')->after('role');
            $table->text('bio')->nullable()->after('id_app');
            $table->date('date_of_birth')->nullable()->after('bio');
            $table->string('phone')->nullable()->after('date_of_birth');
            $table->string('country')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'full_name', 'id_app', 'user_role', 'bio', 'date_of_birth', 'phone', 'country']);
        });
    }
};
