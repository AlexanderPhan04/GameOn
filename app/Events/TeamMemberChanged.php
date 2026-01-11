<?php

namespace App\Events;

use App\Models\Team;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeamMemberChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $teamId;
    public $action;
    public $member;

    public function __construct(Team $team, User $user, string $action)
    {
        $this->teamId = $team->id;
        $this->action = $action; // 'added' or 'removed'
        $this->member = [
            'id' => $user->id,
            'name' => $user->display_name,
            'avatar' => $user->getDisplayAvatar(),
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
        return 'member.changed';
    }
}
