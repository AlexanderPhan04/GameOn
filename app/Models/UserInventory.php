<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInventory extends Model
{
    protected $table = 'user_inventory';

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'quantity',
        'is_equipped',
        'equipment_slot',
        'custom_data',
        'expires_at',
        'is_gift',
        'gifted_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'is_equipped' => 'boolean',
        'is_gift' => 'boolean',
        'custom_data' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Người sở hữu
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sản phẩm
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(MarketplaceProduct::class, 'product_id');
    }

    /**
     * Đơn hàng
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(MarketplaceOrder::class, 'order_id');
    }

    /**
     * Người tặng (nếu là quà)
     */
    public function gifter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gifted_by');
    }

    /**
     * Kiểm tra item đã hết hạn
     */
    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false;
        }
        return $this->expires_at->isPast();
    }
}
