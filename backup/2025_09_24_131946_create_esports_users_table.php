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
        Schema::create('esports_users', function (Blueprint $table) {
            $table->id('user_id'); // Primary key tương ứng UserID trong C#
            $table->string('username', 50)->unique(); // Tên đăng nhập unique
            $table->string('email', 100)->unique(); // Email unique
            $table->string('password_hash'); // Mật khẩu đã hash
            $table->string('full_name', 100)->nullable(); // Họ tên
            $table->enum('role', ['Admin', 'Player', 'Viewer'])->default('Viewer'); // Vai trò
            $table->enum('status', ['Active', 'Suspended', 'Inactive', 'Pending', 'Deleted'])->default('Pending'); // Trạng thái
            $table->boolean('is_email_verified')->default(false); // Email đã xác minh
            $table->string('email_verification_token')->nullable(); // Token xác minh email
            $table->string('password_reset_token')->nullable(); // Token reset password
            $table->datetime('password_reset_expiry')->nullable(); // Hết hạn token reset
            $table->string('security_question', 200)->nullable(); // Câu hỏi bảo mật
            $table->string('security_answer')->nullable(); // Câu trả lời bảo mật (hash)
            $table->datetime('last_login_at')->nullable(); // Lần đăng nhập cuối
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esports_users');
    }
};
