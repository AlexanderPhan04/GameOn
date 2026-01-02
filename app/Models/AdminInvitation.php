<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AdminInvitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'invited_by',
        'permissions',
        'status',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Tạo token mới cho invitation
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Người mời (Super Admin)
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Kiểm tra invitation còn hiệu lực
     */
    public function isValid(): bool
    {
        return $this->status === 'pending' && $this->expires_at->isFuture();
    }

    /**
     * Kiểm tra đã hết hạn
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Đánh dấu đã chấp nhận
     */
    public function markAsAccepted(): void
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    /**
     * Đánh dấu đã từ chối
     */
    public function markAsRejected(): void
    {
        $this->update([
            'status' => 'rejected',
        ]);
    }

    /**
     * Đánh dấu hết hạn
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => 'expired',
        ]);
    }

    /**
     * Scope: Pending invitations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Valid invitations (pending và chưa hết hạn)
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'pending')
            ->where('expires_at', '>', now());
    }
}
