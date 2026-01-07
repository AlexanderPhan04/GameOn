<?php

namespace App\Events;

use App\Models\TeamMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeamMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $teamId;

    public function __construct(TeamMessage $message)
    {
        $this->teamId = $message->team_id;
        $this->message = [
            'id' => $message->id,
            'message' => $message->message,
            'time' => $message->created_at->format('H:i'),
            'user' => [
                'id' => $message->user->id,
                'name' => $message->user->display_name,
                'avatar' => $message->user->avatar ? get_avatar_url($message->user->avatar) : null,
            ],
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('team.' . $this->teamId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
