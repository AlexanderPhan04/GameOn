<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'description',
        'avatar',
        'created_by',
        'last_message_at',
        'is_active',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the creator of the conversation
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all messages in this conversation
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message
     */
    public function latestMessage(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id')
            ->whereNull('deleted_at')
            ->latest()
            ->limit(1);
    }

    /**
     * Get all participants in this conversation
     */
    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'conversation_id');
    }

    /**
     * Get users who participate in this conversation
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants', 'conversation_id', 'user_id')
            ->withPivot(['role', 'joined_at', 'last_read_at', 'is_blocked', 'is_muted', 'muted_until'])
            ->withTimestamps();
    }

    /**
     * Get unread messages count for a specific user
     */
    public function getUnreadCountForUser($userId)
    {
        $participant = $this->participants()->where('user_id', $userId)->first();
        if (! $participant) {
            return 0;
        }

        $lastReadAt = $participant->last_read_at;
        if (! $lastReadAt) {
            return $this->messages()->count();
        }

        return $this->messages()->where('created_at', '>', $lastReadAt)->count();
    }

    /**
     * Mark conversation as read for a user
     */
    public function markAsReadForUser($userId)
    {
        $this->participants()
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);
    }

    /**
     * Check if user is participant
     */
    public function hasParticipant($userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    /**
     * Add participant to conversation
     */
    public function addParticipant($userId, $role = 'member')
    {
        if (! $this->hasParticipant($userId)) {
            return $this->participants()->create([
                'user_id' => $userId,
                'role' => $role,
                'joined_at' => now(),
            ]);
        }

        return null;
    }

    /**
     * Get conversation name for display
     */
    public function getDisplayName($currentUserId = null)
    {
        if ($this->type === 'group') {
            return $this->name ?: 'Group Chat';
        }

        // For private chat, show other participant's name
        if ($currentUserId) {
            $otherUser = $this->users()->where('user_id', '!=', $currentUserId)->first();

            return $otherUser ? $otherUser->name : 'Private Chat';
        }

        return 'Private Chat';
    }

    /**
     * Scope for private conversations between two users
     */
    public function scopePrivateBetween($query, $userId1, $userId2)
    {
        return $query->where('type', 'private')
            ->whereHas('participants', function ($q) use ($userId1) {
                $q->where('user_id', $userId1);
            })
            ->whereHas('participants', function ($q) use ($userId2) {
                $q->where('user_id', $userId2);
            });
    }

    /**
     * Scope for user's conversations
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('is_blocked', false);
        });
    }

    /**
     * Create or get private conversation between two users
     */
    public static function createOrGetPrivate($userId1, $userId2)
    {
        // Try to find existing conversation
        $conversation = self::privateBetween($userId1, $userId2)->first();

        if ($conversation) {
            return $conversation;
        }

        // Create new conversation
        $conversation = self::create([
            'type' => 'private',
            'created_by' => $userId1,
            'is_active' => true,
        ]);

        // Add participants
        $conversation->addParticipant($userId1);
        $conversation->addParticipant($userId2);

        return $conversation;
    }

    /**
     * Get display avatar for a conversation from the perspective of a specific user
     */
    public function getDisplayAvatar($userId)
    {
        if ($this->type === 'group') {
            if ($this->avatar) {
                return asset('uploads/' . $this->avatar);
            }
            // Generate group avatar
            $name = urlencode($this->name ?? 'Group');
            return "https://ui-avatars.com/api/?name={$name}&size=128&background=8b5cf6&color=ffffff&bold=true&format=svg";
        }

        // For private conversations, show the other user's avatar
        $otherUser = $this->participants()
            ->with('user')
            ->where('user_id', '!=', $userId)
            ->first();

        if ($otherUser && $otherUser->user) {
            return $otherUser->user->getDisplayAvatar();
        }

        return "https://ui-avatars.com/api/?name=User&size=128&background=667eea&color=ffffff&bold=true&format=svg";
    }

    /**
     * Get unread count for a specific user
     */
    public function getUnreadCount($userId)
    {
        $participant = $this->participants()->where('user_id', $userId)->first();

        if (! $participant || ! $participant->last_read_at) {
            return $this->messages()->count();
        }

        return $this->messages()
            ->where('created_at', '>', $participant->last_read_at)
            ->where('sender_id', '!=', $userId)
            ->count();
    }

    /**
     * Get last message preview
     */
    public function getLastMessagePreviewAttribute()
    {
        $lastMessage = $this->messages()->latest()->first();

        if (! $lastMessage) {
            return 'No messages yet';
        }

        if ($lastMessage->type === 'image') {
            return '📷 Image';
        }

        if ($lastMessage->type === 'file') {
            return '📎 File: ' . $lastMessage->attachment_name;
        }

        return strlen($lastMessage->content) > 50
            ? substr($lastMessage->content, 0, 50) . '...'
            : $lastMessage->content;
    }
}
