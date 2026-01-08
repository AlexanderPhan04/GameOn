<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for specific notification type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Check if notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Create a team invitation notification.
     */
    public static function createTeamInvitation(int $userId, array $invitation): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'team_invitation',
            'title' => 'Lời mời tham gia đội',
            'message' => "{$invitation['inviter_name']} mời bạn tham gia đội {$invitation['team_name']}",
            'data' => [
                'icon' => 'users',
                'avatar' => $invitation['team_logo'] ?? null,
                'url' => '/teams',
                'team_id' => $invitation['team_id'],
                'invitation_id' => $invitation['invitation_id'],
            ],
        ]);
    }

    /**
     * Create a chat message notification.
     */
    public static function createChatMessage(int $userId, array $data): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'chat_message',
            'title' => 'Tin nhắn mới',
            'message' => "{$data['sender_name']} đã gửi tin nhắn: \"{$data['content']}\"",
            'data' => [
                'icon' => 'comment',
                'avatar' => $data['sender_avatar'] ?? null,
                'url' => "/chat/conversation/{$data['conversation_id']}",
                'conversation_id' => $data['conversation_id'],
            ],
        ]);
    }
}
