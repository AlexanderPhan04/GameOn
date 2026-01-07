<?php

namespace App\Events;

use App\Models\TeamInvitation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeamInvitationSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invitation;
    public $userId;

    public function __construct(TeamInvitation $invitation)
    {
        $this->userId = $invitation->user_id;
        $this->invitation = [
            'id' => $invitation->id,
            'team' => [
                'id' => $invitation->team->id,
                'name' => $invitation->team->name,
                'logo' => $invitation->team->logo_url,
            ],
            'inviter' => [
                'id' => $invitation->inviter->id,
                'name' => $invitation->inviter->display_name,
            ],
            'created_at' => $invitation->created_at->diffForHumans(),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'team.invitation';
    }
}
