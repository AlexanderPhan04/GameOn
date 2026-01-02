<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'round_name',
        'round_number',
        'match_number',
        'bracket_type',
        'participant_1_id',
        'participant_1_type',
        'participant_2_id',
        'participant_2_type',
        'winner_id',
        'winner_type',
        'score_1',
        'score_2',
        'game_scores',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'stream_url',
        'vod_url',
        'notes',
    ];

    protected $casts = [
        'game_scores' => 'array',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get participant 1 (user or team)
     */
    public function participant1()
    {
        if ($this->participant_1_type === 'team') {
            return $this->belongsTo(Team::class, 'participant_1_id');
        }
        return $this->belongsTo(User::class, 'participant_1_id');
    }

    /**
     * Get participant 2 (user or team)
     */
    public function participant2()
    {
        if ($this->participant_2_type === 'team') {
            return $this->belongsTo(Team::class, 'participant_2_id');
        }
        return $this->belongsTo(User::class, 'participant_2_id');
    }

    /**
     * Get winner (user or team)
     */
    public function winner()
    {
        if ($this->winner_type === 'team') {
            return $this->belongsTo(Team::class, 'winner_id');
        }
        return $this->belongsTo(User::class, 'winner_id');
    }

    // ========== ACCESSORS ==========

    public function getParticipant1NameAttribute(): string
    {
        if ($this->participant_1_type === 'team') {
            return Team::find($this->participant_1_id)?->name ?? 'TBD';
        }
        return User::find($this->participant_1_id)?->name ?? 'TBD';
    }

    public function getParticipant2NameAttribute(): string
    {
        if ($this->participant_2_type === 'team') {
            return Team::find($this->participant_2_id)?->name ?? 'TBD';
        }
        return User::find($this->participant_2_id)?->name ?? 'TBD';
    }

    public function getScoreDisplayAttribute(): string
    {
        return ($this->score_1 ?? '-') . ' : ' . ($this->score_2 ?? '-');
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsLiveAttribute(): bool
    {
        return $this->status === 'live';
    }

    // ========== SCOPES ==========

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    public function scopeInRound($query, $roundNumber)
    {
        return $query->where('round_number', $roundNumber);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at');
    }

    // ========== METHODS ==========

    public function start(): bool
    {
        return $this->update([
            'status' => 'live',
            'started_at' => now(),
        ]);
    }

    public function complete(int $score1, int $score2, $winnerId = null, $winnerType = null): bool
    {
        return $this->update([
            'status' => 'completed',
            'score_1' => $score1,
            'score_2' => $score2,
            'winner_id' => $winnerId,
            'winner_type' => $winnerType,
            'ended_at' => now(),
        ]);
    }

    public function cancel(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }

    public function setWalkover($winnerId, $winnerType): bool
    {
        return $this->update([
            'status' => 'walkover',
            'winner_id' => $winnerId,
            'winner_type' => $winnerType,
            'ended_at' => now(),
        ]);
    }
}
