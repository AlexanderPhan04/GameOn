<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TournamentSchedule extends Model
{
    use HasFactory;

    protected $table = 'tournament_schedule';

    protected $fillable = [
        'tournament_id',
        'start_date',
        'end_date',
        'registration_deadline',
        'location_type',
        'location_address',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
    ];

    /**
     * Tournament này thuộc về
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Kiểm tra còn trong thời gian đăng ký không
     */
    public function isRegistrationOpen(): bool
    {
        if (! $this->registration_deadline) {
            return true;
        }

        return Carbon::now()->lt($this->registration_deadline);
    }

    /**
     * Kiểm tra giải đấu đã bắt đầu chưa
     */
    public function hasStarted(): bool
    {
        if (! $this->start_date) {
            return false;
        }

        return Carbon::now()->gte($this->start_date);
    }

    /**
     * Kiểm tra giải đấu đã kết thúc chưa
     */
    public function hasEnded(): bool
    {
        if (! $this->end_date) {
            return false;
        }

        return Carbon::now()->gt($this->end_date);
    }

    /**
     * Kiểm tra đang diễn ra
     */
    public function isOngoing(): bool
    {
        return $this->hasStarted() && ! $this->hasEnded();
    }

    /**
     * Lấy location type label
     */
    public function getLocationTypeLabelAttribute(): string
    {
        return match ($this->location_type) {
            'online' => 'Online',
            'lan' => 'LAN (Offline)',
            default => ucfirst($this->location_type),
        };
    }

    /**
     * Lấy địa điểm đầy đủ
     */
    public function getFullLocationAttribute(): string
    {
        if ($this->location_type === 'online') {
            return 'Online';
        }

        return $this->location_address ?? 'TBD';
    }

    /**
     * Số ngày còn lại đến deadline đăng ký
     */
    public function getDaysUntilDeadlineAttribute(): ?int
    {
        if (! $this->registration_deadline) {
            return null;
        }

        $days = Carbon::now()->diffInDays($this->registration_deadline, false);
        return $days > 0 ? $days : 0;
    }
}
