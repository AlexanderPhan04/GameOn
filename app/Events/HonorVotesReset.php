<?php

namespace App\Events;

use App\Models\HonorEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HonorVotesReset implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $eventId;
    public string $eventName;

    public function __construct(HonorEvent $honorEvent)
    {
        $this->eventId = $honorEvent->id;
        $this->eventName = $honorEvent->name;
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
        return 'votes.reset';
    }
}
