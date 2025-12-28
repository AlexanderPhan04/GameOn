<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm transaction_id vào donations để link với bảng transactions
     */
    public function up(): void
    {
        // Kiểm tra bảng tồn tại trước khi thêm cột
        if (!Schema::hasTable('donations')) {
            return; // Bảng chưa tồn tại, bỏ qua migration này
        }

        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'transaction_id')) {
                $isSqlite = config('database.default') === 'sqlite' || 
                            (config('database.connections.'.config('database.default').'.driver') === 'sqlite');
                
                if ($isSqlite) {
                    $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
                } else {
                    $table->foreignId('transaction_id')->nullable()->after('id')->constrained('transactions')->onDelete('set null');
                }
                $table->index('transaction_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropIndex(['transaction_id']);
            $table->dropColumn('transaction_id');
        });
    }
};

