<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HonorEventDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $eventId;
    public string $eventName;

    public function __construct(int $eventId, string $eventName)
    {
        $this->eventId = $eventId;
        $this->eventName = $eventName;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('honor'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'event.deleted';
    }
}
