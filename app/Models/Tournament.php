<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'game_id',
        'created_by',
        'status',
        'image_url',
        'stream_link',
        // Legacy fields (sẽ bị remove sau khi tách hoàn tất)
        'start_date',
        'end_date',
        'registration_deadline',
        'max_teams',
        'max_participants',
        'entry_fee',
        'prize_pool',
        'prize_distribution',
        'format',
        'competition_type',
        'rules',
        'location_type',
        'location_address',
        'organizer_id',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'entry_fee' => 'decimal:2',
        'prize_pool' => 'decimal:2',
        'prize_distribution' => 'array',
        'rules' => 'array',
        'is_active' => 'boolean',
    ];

    // ========== RELATIONSHIPS (NEW TABLES) ==========

    /**
     * Tournament settings (format, prize, rules...)
     */
    public function settings(): HasOne
    {
        return $this->hasOne(TournamentSettings::class);
    }

    /**
     * Tournament schedule (dates, location...)
     */
    public function schedule(): HasOne
    {
        return $this->hasOne(TournamentSchedule::class);
    }

    // ========== EXISTING RELATIONSHIPS ==========

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'tournament_teams')
            ->withPivot('joined_at', 'status')
            ->withTimestamps();
    }

    /**
     * Get all registrations for this tournament
     */
    public function registrations()
    {
        return $this->hasMany(TournamentRegistration::class);
    }

    /**
     * Get all matches for this tournament
     */
    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }

    /**
     * Get approved registrations
     */
    public function approvedRegistrations()
    {
        return $this->hasMany(TournamentRegistration::class)->where('status', 'approved');
    }

    /**
     * Get checked-in participants
     */
    public function checkedInParticipants()
    {
        return $this->hasMany(TournamentRegistration::class)->where('status', 'checked_in');
    }

    /**
     * Get upcoming matches
     */
    public function upcomingMatches()
    {
        return $this->hasMany(TournamentMatch::class)
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at');
    }

    /**
     * Get live matches
     */
    public function liveMatches()
    {
        return $this->hasMany(TournamentMatch::class)->where('status', 'live');
    }

    /**
     * Get participant count
     */
    public function getParticipantCountAttribute(): int
    {
        return $this->registrations()->whereIn('status', ['approved', 'checked_in'])->count();
    }

    /**
     * Check if registration is open
     */
    public function isRegistrationOpen(): bool
    {
        return $this->status === 'registration' &&
            $this->registration_deadline >= now();
    }

    /**
     * Scope for active tournaments
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    /**
     * Scope for upcoming tournaments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'registration')
            ->where('start_date', '>=', now());
    }

    // ========== HELPER ACCESSORS (delegate to new tables) ==========

    /**
     * Get prize pool (từ settings hoặc legacy field)
     */
    public function getPrizePoolValueAttribute()
    {
        return $this->settings?->prize_pool ?? $this->prize_pool ?? 0;
    }

    /**
     * Get format (từ settings hoặc legacy field)
     */
    public function getFormatValueAttribute()
    {
        return $this->settings?->format ?? $this->format ?? 'single_elimination';
    }

    /**
     * Get start date (từ schedule hoặc legacy field)
     */
    public function getStartDateValueAttribute()
    {
        return $this->schedule?->start_date ?? $this->start_date;
    }

    /**
     * Get registration deadline (từ schedule hoặc legacy field)
     */
    public function getRegistrationDeadlineValueAttribute()
    {
        return $this->schedule?->registration_deadline ?? $this->registration_deadline;
    }

    /**
     * Check if registration is open (delegate to schedule)
     */
    public function isRegistrationOpenNew(): bool
    {
        if ($this->status !== 'registration') {
            return false;
        }

        if ($this->schedule) {
            return $this->schedule->isRegistrationOpen();
        }

        // Fallback to legacy field
        return $this->registration_deadline >= now();
    }
}
