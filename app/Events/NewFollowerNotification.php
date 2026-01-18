<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewFollowerNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public int $followerId;
    public string $followerName;
    public ?string $followerAvatar;
    public int $notificationId;

    public function __construct(int $userId, User $follower, int $notificationId)
    {
        $this->userId = $userId;
        $this->followerId = $follower->id;
        $this->followerName = $follower->profile?->full_name ?: $follower->name;
        $this->followerAvatar = $follower->getDisplayAvatar();
        $this->notificationId = $notificationId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'notification_id' => $this->notificationId,
            'follower_id' => $this->followerId,
            'follower_name' => $this->followerName,
            'follower_avatar' => $this->followerAvatar,
            'type' => 'new_follower',
            'title' => 'Người theo dõi mới',
            'message' => "{$this->followerName} đã bắt đầu theo dõi bạn",
        ];
    }

    public function broadcastAs(): string
    {
        return 'follow.new';
    }
}
