<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public int $senderId;
    public int $conversationId;
    public string $senderName;
    public ?string $senderAvatar;
    public string $content;

    public function __construct(int $userId, ChatMessage $message)
    {
        $this->userId = $userId;
        $this->conversationId = $message->conversation_id;
        $this->senderName = $message->sender->name;
        $this->senderAvatar = $message->sender->getDisplayAvatar();
        $this->content = $message->content ?? 'Đã gửi một tệp đính kèm';
        $this->senderId = $message->sender_id;
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
            'conversation_id' => $this->conversationId,
            'sender_id' => $this->senderId,
            'sender_name' => $this->senderName,
            'sender_avatar' => $this->senderAvatar,
            'content' => $this->content,
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat.message';
    }
}
