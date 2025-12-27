<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketplaceProduct extends Model
{
    protected $table = 'marketplace_products';

    protected $fillable = [
        'name',
        'description',
        'type',
        'category',
        'price',
        'discount_price',
        'is_active',
        'is_featured',
        'stock',
        'sold_count',
        'thumbnail',
        'images',
        'preview_url',
        'metadata',
        'game_id',
        'rarity',
        'created_by',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'stock' => 'integer',
        'sold_count' => 'integer',
        'images' => 'array',
        'metadata' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Người tạo sản phẩm
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Các đơn hàng chứa sản phẩm này
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(MarketplaceOrderItem::class, 'product_id');
    }

    /**
     * Kiểm tra sản phẩm còn hàng
     */
    public function isInStock(): bool
    {
        if ($this->stock == -1) {
            return true; // Unlimited
        }
        return $this->stock > 0;
    }

    /**
     * Lấy giá hiện tại (sau khi giảm giá)
     */
    public function getCurrentPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Kiểm tra có giảm giá không
     */
    public function hasDiscount(): bool
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }
}
