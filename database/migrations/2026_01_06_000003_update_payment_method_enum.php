<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify enum to include zalopay
        DB::statement("ALTER TABLE marketplace_orders MODIFY COLUMN payment_method ENUM('vnpay', 'zalopay', 'wallet', 'other') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE marketplace_orders MODIFY COLUMN payment_method ENUM('vnpay', 'wallet', 'other') NULL");
    }
};
