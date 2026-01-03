<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentManagement extends Model
{
    use HasFactory;

    protected $table = 'tournaments';

    protected $fillable = [
        // Thông tin cơ bản
        'name',
        'game_id',
        'competition_type',
        'format',
        'description',
        'banner',
        'logo',

        // Thời gian & địa điểm
        'start_date',
        'end_date',
        'location_type',
        'location_address',
        'scheduled_time',

        // Cấu trúc & luật chơi
        'tournament_format',
        'max_participants',
        'substitute_players',
        'rules_details',

        // Quản lý & phần thưởng
        'organizer_name',
        'organizer_contact',
        'referees',
        'prize_structure',
        'sponsors',

        // Hệ thống & hiển thị
        'status',
        'participation_type',
        'stream_link',
        'hashtags',

        // Metadata
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'rules_details' => 'array',
        'referees' => 'array',
        'prize_structure' => 'array',
        'sponsors' => 'array',
        'hashtags' => 'array',
    ];

    // Relationships
    public function game(): BelongsTo
    {
        return $this->belongsTo(GameManagement::class, 'game_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors for file URLs
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('uploads/' . $this->logo) : null;
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner ? asset('uploads/' . $this->banner) : null;
    }

    // Status helpers
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => __('app.tournaments.draft'),
            'registration_open' => __('app.tournaments.registration_open'),
            'ongoing' => __('app.tournaments.ongoing'),
            'completed' => __('app.tournaments.completed'),
            'cancelled' => __('app.tournaments.cancelled'),
            default => __('app.tournaments.unknown')
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'registration_open' => 'primary',
            'ongoing' => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getCompetitionTypeLabelAttribute(): string
    {
        return $this->competition_type === 'individual' ? __('app.tournaments.individual') : __('app.tournaments.team');
    }

    public function getTournamentFormatLabelAttribute(): string
    {
        return match ($this->tournament_format) {
            'single_elimination' => __('app.tournaments.single_elimination'),
            'double_elimination' => __('app.tournaments.double_elimination'),
            'round_robin' => __('app.tournaments.round_robin'),
            'swiss_system' => __('app.tournaments.swiss_system'),
            default => __('app.tournaments.unknown')
        };
    }

    public function getLocationTypeLabelAttribute(): string
    {
        return match ($this->location_type) {
            'online' => __('app.tournaments.online'),
            'lan' => __('app.tournaments.lan'),
            'physical' => __('app.tournaments.physical'),
            default => __('app.tournaments.unknown')
        };
    }

    public function getParticipationTypeLabelAttribute(): string
    {
        return $this->participation_type === 'public' ? 'Công khai' : 'Chỉ mời';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['registration_open', 'ongoing']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'registration_open')
            ->where('start_date', '>=', now());
    }

    public function scopeByGame($query, $gameId)
    {
        return $query->where('game_id', $gameId);
    }

    public function scopePublic($query)
    {
        return $query->where('participation_type', 'public');
    }

    // Helper methods
    public function isRegistrationOpen(): bool
    {
        return $this->status === 'registration_open' &&
            $this->start_date >= now()->toDateString();
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'registration_open']);
    }

    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getFormattedDateRange(): string
    {
        if ($this->start_date->isSameDay($this->end_date)) {
            return $this->start_date->format('d/m/Y');
        }

        return $this->start_date->format('d/m/Y') . ' - ' . $this->end_date->format('d/m/Y');
    }
}
