<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MarketplaceOrder extends Model
{
    protected $table = 'marketplace_orders';

    protected $fillable = [
        'transaction_id',
        'order_id',
        'order_code',
        'user_id',
        'total_amount',
        'discount_amount',
        'final_amount',
        'status',
        'payment_status',
        'payment_method',
        'payos_transaction_id',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $code = 'ORD' . strtoupper(Str::random(8)) . time();
            if (empty($order->order_id)) {
                $order->order_id = $code;
            }
            if (empty($order->order_code)) {
                $order->order_code = $code;
            }
        });
    }

    /**
     * Người đặt hàng
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Chi tiết đơn hàng
     */
    public function items(): HasMany
    {
        return $this->hasMany(MarketplaceOrderItem::class, 'order_id');
    }

    /**
     * Transaction associated with this order
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Kiểm tra đơn hàng đã thanh toán
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Kiểm tra đơn hàng đã hoàn thành
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
