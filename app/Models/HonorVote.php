<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HonorVote extends Model
{
    protected $fillable = [
        'honor_event_id',
        'voter_id',
        'voted_user_id',
        'vote_type',
        'voted_item_id',
        'voter_role',
        'weight',
        'is_anonymous',
        'comment',
    ];

    protected $casts = [
        'weight' => 'decimal:1',
        'is_anonymous' => 'boolean',
    ];

    // Relationships
    public function honorEvent(): BelongsTo
    {
        return $this->belongsTo(HonorEvent::class);
    }

    public function voter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voter_id');
    }

    public function votedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voted_user_id');
    }

    // Helper methods
    public function getVotedItem()
    {
        switch ($this->vote_type) {
            case 'user':
                return User::find($this->voted_item_id);
            case 'team':
                return Team::find($this->voted_item_id);
            case 'tournament':
                return Tournament::find($this->voted_item_id);
            case 'game':
                return Game::find($this->voted_item_id);
            default:
                return null;
        }
    }

    public function getVotedItemName(): string
    {
        $item = $this->getVotedItem();

        if (! $item) {
            return 'Unknown';
        }

        switch ($this->vote_type) {
            case 'user':
                return $item->name ?? $item->display_name ?? 'Unknown User';
            case 'team':
                return $item->name ?? 'Unknown Team';
            case 'tournament':
                return $item->name ?? 'Unknown Tournament';
            case 'game':
                return $item->name ?? 'Unknown Game';
            default:
                return 'Unknown';
        }
    }

    public function getVoterDisplayName(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->voter->name ?? $this->voter->display_name ?? 'Unknown';
    }
}
