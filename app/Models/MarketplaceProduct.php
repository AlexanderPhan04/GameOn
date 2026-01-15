<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MarketplaceProduct extends Model
{
    protected $table = 'marketplace_products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'category',
        'category_id',
        'price',
        'discount_price',
        'is_active',
        'is_featured',
        'stock',
        'sold_count',
        'thumbnail',
        'images',
        'metadata',
        'tournament_id',
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
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });

        static::updating(function ($product) {
            // Regenerate slug if name changed and slug not manually set
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    /**
     * Generate unique slug
     */
    public static function generateUniqueSlug(string $name, ?int $exceptId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter++;
            $query = static::where('slug', $slug);
            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }
        }

        return $slug;
    }

    /**
     * Get route key name for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Người tạo sản phẩm
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Category của sản phẩm (new normalized approach)
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Các đơn hàng chứa sản phẩm này
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(MarketplaceOrderItem::class, 'product_id');
    }

    /**
     * Tournament liên quan (nếu là vé giải đấu)
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Stickers trong pack (nếu là sticker pack)
     */
    public function stickers(): HasMany
    {
        return $this->hasMany(Sticker::class, 'pack_id')->orderBy('sort_order');
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
