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
        Schema::table('marketplace_orders', function (Blueprint $table) {
            if (Schema::hasColumn('marketplace_orders', 'zalopay_trans_id')) {
                $table->dropColumn('zalopay_trans_id');
            }
            if (Schema::hasColumn('marketplace_orders', 'zalopay_zp_trans_id')) {
                $table->dropColumn('zalopay_zp_trans_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            $table->string('zalopay_trans_id')->nullable();
            $table->string('zalopay_zp_trans_id')->nullable();
        });
    }
};
