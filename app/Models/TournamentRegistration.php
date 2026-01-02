<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'user_id',
        'team_id',
        'participant_type',
        'status',
        'seed',
        'registered_at',
        'approved_at',
        'checked_in_at',
        'notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // ========== ACCESSORS ==========

    /**
     * Get the participant (user or team)
     */
    public function getParticipantAttribute()
    {
        return $this->participant_type === 'team' ? $this->team : $this->user;
    }

    /**
     * Get participant name
     */
    public function getParticipantNameAttribute(): string
    {
        if ($this->participant_type === 'team') {
            return $this->team?->name ?? 'Unknown Team';
        }
        return $this->user?->name ?? 'Unknown User';
    }

    // ========== SCOPES ==========

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }

    public function scopeForTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    public function scopeIndividual($query)
    {
        return $query->where('participant_type', 'individual');
    }

    public function scopeTeamBased($query)
    {
        return $query->where('participant_type', 'team');
    }

    // ========== METHODS ==========

    public function approve(): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function reject(): bool
    {
        return $this->update(['status' => 'rejected']);
    }

    public function checkIn(): bool
    {
        return $this->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);
    }

    public function withdraw(): bool
    {
        return $this->update(['status' => 'withdrawn']);
    }
}
