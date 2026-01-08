<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public string $status;
    public string $statusDisplay;
    public string $message;

    public function __construct(User $user)
    {
        $this->userId = $user->id;
        $this->status = $user->status;
        $this->statusDisplay = $user->status_display_name;
        $this->message = $this->getStatusMessage($user->status);
    }

    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            'suspended' => 'Tài khoản của bạn đã bị tạm khóa. Bạn không thể thực hiện các thao tác cho đến khi được mở khóa.',
            'banned' => 'Tài khoản của bạn đã bị cấm vĩnh viễn do vi phạm quy định.',
            'deleted' => 'Tài khoản của bạn đã bị xóa.',
            'active' => 'Tài khoản của bạn đã được kích hoạt trở lại.',
            default => 'Trạng thái tài khoản của bạn đã thay đổi.'
        };
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'status' => $this->status,
            'status_display' => $this->statusDisplay,
            'message' => $this->message,
        ];
    }
}
