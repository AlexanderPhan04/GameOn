<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HonorEvent extends Model
{
    protected $fillable = [
        'name',
        'description',
        'mode',
        'target_type',
        'start_time',
        'end_time',
        'is_active',
        'allow_viewer_vote',
        'allow_player_vote',
        'allow_admin_vote',
        'allow_anonymous',
        'viewer_weight',
        'player_weight',
        'admin_weight',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'allow_viewer_vote' => 'boolean',
        'allow_player_vote' => 'boolean',
        'allow_admin_vote' => 'boolean',
        'allow_anonymous' => 'boolean',
        'viewer_weight' => 'decimal:1',
        'player_weight' => 'decimal:1',
        'admin_weight' => 'decimal:1',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(HonorVote::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFreeMode($query)
    {
        return $query->where('mode', 'free');
    }

    public function scopeEventMode($query)
    {
        return $query->where('mode', 'event');
    }

    public function scopeCurrentlyRunning($query)
    {
        $now = Carbon::now();

        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_time')
                    ->orWhere('start_time', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_time')
                    ->orWhere('end_time', '>=', $now);
            });
    }

    // Helper methods
    public function isCurrentlyRunning(): bool
    {
        $now = Carbon::now();

        if (! $this->is_active) {
            return false;
        }

        if ($this->start_time && $this->start_time > $now) {
            return false;
        }

        if ($this->end_time && $this->end_time < $now) {
            return false;
        }

        return true;
    }

    public function canUserVote(User $user): bool
    {
        if (! $this->isCurrentlyRunning()) {
            return false;
        }

        $role = $user->user_role;

        switch ($role) {
            case 'viewer':
                return $this->allow_viewer_vote;
            case 'player':
                return $this->allow_player_vote;
            case 'admin':
            case 'super_admin':
                return $this->allow_admin_vote;
            default:
                return false;
        }
    }

    public function getWeightForRole(string $role): float
    {
        switch ($role) {
            case 'viewer':
                return (float) $this->viewer_weight;
            case 'player':
                return (float) $this->player_weight;
            case 'admin':
            case 'super_admin':
                return (float) $this->admin_weight;
            default:
                return 1.0;
        }
    }

    public function getTotalVotesCount(): int
    {
        return $this->votes()->count();
    }

    public function getTotalWeightedVotes(): float
    {
        return $this->votes()->sum('weight');
    }
}
