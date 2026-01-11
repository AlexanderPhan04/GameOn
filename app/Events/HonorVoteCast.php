<?php

namespace App\Events;

use App\Models\HonorEvent;
use App\Models\HonorVote;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HonorVoteCast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $eventId;
    public array $vote;
    public array $stats;

    public function __construct(HonorVote $vote, HonorEvent $honorEvent)
    {
        $this->eventId = $honorEvent->id;
        
        $this->vote = [
            'id' => $vote->id,
            'voted_item_id' => $vote->voted_item_id,
            'vote_type' => $vote->vote_type,
            'weight' => (float) $vote->weight,
            'is_anonymous' => $vote->is_anonymous,
            'voter_name' => $vote->is_anonymous ? null : $vote->voter->name,
            'voter_avatar' => $vote->is_anonymous ? null : $vote->voter->getDisplayAvatar(),
            'created_at' => $vote->created_at->toISOString(),
        ];

        // Calculate updated stats for the voted item
        $this->stats = $this->calculateItemStats($honorEvent, $vote->voted_item_id);
    }

    protected function calculateItemStats(HonorEvent $event, int $itemId): array
    {
        $votes = $event->votes()->where('voted_item_id', $itemId)->get();
        
        return [
            'item_id' => $itemId,
            'total_votes' => $votes->count(),
            'weighted_votes' => $votes->sum('weight'),
            'event_total_votes' => $event->getTotalVotesCount(),
            'event_weighted_total' => $event->getTotalWeightedVotes(),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('honor'),
            new Channel('honor.event.' . $this->eventId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'vote.cast';
    }
}
