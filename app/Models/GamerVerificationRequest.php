<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GamerVerificationRequest extends Model
{
    protected $fillable = [
        'user_id',
        'game_name',
        'in_game_name',
        'in_game_id',
        'rank_tier',
        'achievements',
        'proof_links',
        'proof_image',
        'additional_info',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_REVOKED = 'revoked';

    /**
     * User đã gửi yêu cầu
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin đã xét duyệt
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Kiểm tra trạng thái
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isRevoked(): bool
    {
        return $this->status === self::STATUS_REVOKED;
    }

    /**
     * Duyệt yêu cầu
     */
    public function approve(int $adminId, ?string $note = null): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
            'admin_note' => $note,
        ]);

        // Cập nhật user thành verified gamer
        $this->user->update(['is_verified_gamer' => true]);
    }

    /**
     * Từ chối yêu cầu
     */
    public function reject(int $adminId, ?string $note = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
            'admin_note' => $note,
        ]);
    }

    /**
     * Scope: Lọc theo trạng thái
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Lấy badge class theo status
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_REVOKED => 'bg-dark',
            default => 'bg-secondary',
        };
    }

    /**
     * Lấy label theo status
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Đang chờ duyệt',
            self::STATUS_APPROVED => 'Đã duyệt',
            self::STATUS_REJECTED => 'Đã từ chối',
            self::STATUS_REVOKED => 'Đã thu hồi',
            default => 'Không xác định',
        };
    }
}
