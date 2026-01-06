<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Generate slugs for existing products
        $products = \App\Models\MarketplaceProduct::all();
        foreach ($products as $product) {
            $baseSlug = Str::slug($product->name);
            $slug = $baseSlug;
            $counter = 1;
            
            // Make sure slug is unique
            while (\App\Models\MarketplaceProduct::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            
            $product->slug = $slug;
            $product->save();
        }

        // Now make slug required
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketplace_products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
