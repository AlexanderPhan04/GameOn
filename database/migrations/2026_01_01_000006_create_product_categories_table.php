<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration 6: Tạo bảng product_categories
 *
 * Vấn đề: marketplace_products.category là ENUM cứng với 8 giá trị
 * Giải pháp: Tách thành bảng riêng để dễ mở rộng
 */
return new class extends Migration
{
    public function up(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // Step 1: Tạo bảng categories
        Schema::create('product_categories', function (Blueprint $table) use ($isSqlite) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            if (! $isSqlite) {
                $table->foreign('parent_id')
                    ->references('id')
                    ->on('product_categories')
                    ->onDelete('set null');
            }
            $table->index(['is_active', 'sort_order']);
        });

        // Step 2: Insert các category từ ENUM hiện tại
        $now = now();
        DB::table('product_categories')->insert([
            ['name' => 'UI Theme', 'slug' => 'ui_theme', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Avatar Frame', 'slug' => 'avatar_frame', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sticker Pack', 'slug' => 'sticker_pack', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Emote', 'slug' => 'emote', 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Weapon Skin', 'slug' => 'weapon_skin', 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Character Skin', 'slug' => 'character_skin', 'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Currency', 'slug' => 'currency', 'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Other', 'slug' => 'other', 'sort_order' => 99, 'created_at' => $now, 'updated_at' => $now],
            // Thêm các category mới
            ['name' => 'Subscription', 'slug' => 'subscription', 'sort_order' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Badge', 'slug' => 'badge', 'sort_order' => 9, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Step 3: Thêm column category_id vào marketplace_products
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
        });

        // Step 4: Migrate data từ ENUM sang FK (dùng PHP thay vì raw SQL)
        $categories = DB::table('product_categories')->get()->keyBy('slug');
        $products = DB::table('marketplace_products')->whereNotNull('category')->get(['id', 'category']);

        foreach ($products as $product) {
            if (isset($categories[$product->category])) {
                DB::table('marketplace_products')
                    ->where('id', $product->id)
                    ->update(['category_id' => $categories[$product->category]->id]);
            }
        }

        // Step 5: Thêm FK constraint (chỉ cho MySQL)
        if (! $isSqlite) {
            Schema::table('marketplace_products', function (Blueprint $table) {
                $table->foreign('category_id')
                    ->references('id')
                    ->on('product_categories')
                    ->onDelete('set null');
            });
        }

        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->index('category_id');
        });

        // Note: Giữ lại column category cũ để backward compatible
        // Có thể drop sau khi update code
    }

    public function down(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        if (! $isSqlite) {
            Schema::table('marketplace_products', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
            });
        }

        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('product_categories');
    }
};
