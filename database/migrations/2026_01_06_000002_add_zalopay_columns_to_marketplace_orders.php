<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('marketplace_orders', 'zalopay_trans_id')) {
                $table->string('zalopay_trans_id')->nullable()->after('vnpay_bank_code');
            }
            if (!Schema::hasColumn('marketplace_orders', 'zalopay_zp_trans_id')) {
                $table->string('zalopay_zp_trans_id')->nullable()->after('zalopay_trans_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            $table->dropColumn(['zalopay_trans_id', 'zalopay_zp_trans_id']);
        });
    }
};
