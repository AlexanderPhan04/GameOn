<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Donation extends Model
{
    protected $table = 'donations';

    protected $fillable = [
        'transaction_id',
        'donation_id',
        'donor_id',
        'recipient_id',
        'amount',
        'message',
        'status',
        'payment_status',
        'payment_method',
        'vnpay_transaction_no',
        'vnpay_bank_code',
        'paid_at',
        'is_anonymous',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'paid_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($donation) {
            if (empty($donation->donation_id)) {
                $donation->donation_id = 'DON' . strtoupper(Str::random(8)) . time();
            }
        });
    }

    /**
     * Người quyên góp
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    /**
     * Người nhận (user)
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Transaction associated with this donation
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Kiểm tra đã thanh toán
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Kiểm tra đã hoàn thành
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
