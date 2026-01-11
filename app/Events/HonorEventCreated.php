<?php

namespace App\Events;

use App\Models\HonorEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HonorEventCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $event;

    public function __construct(HonorEvent $honorEvent)
    {
        $this->event = [
            'id' => $honorEvent->id,
            'name' => $honorEvent->name,
            'description' => $honorEvent->description,
            'mode' => $honorEvent->mode,
            'target_type' => $honorEvent->target_type,
            'is_active' => $honorEvent->is_active,
            'start_time' => $honorEvent->start_time?->toISOString(),
            'end_time' => $honorEvent->end_time?->toISOString(),
            'total_votes' => 0,
            'created_at' => $honorEvent->created_at->toISOString(),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('honor'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'event.created';
    }
}
