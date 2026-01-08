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
        $team = $invitation->team;
        
        $this->invitation = [
            'id' => $invitation->id,
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
                'logo' => $team->logo_url,
                'members_count' => $team->members()->count(),
                'max_members' => $team->max_members,
                'game' => $team->game ? [
                    'id' => $team->game->id,
                    'name' => $team->game->name,
                ] : null,
            ],
            'inviter' => [
                'id' => $invitation->inviter->id,
                'name' => $invitation->inviter->display_name,
                'display_name' => $invitation->inviter->display_name,
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
