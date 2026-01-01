<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'type',
        'content',
        'attachment_name',
        'attachment_path',
        'attachment_type',
        'attachment_size',
        'reactions',
        'is_edited',
        'edited_at',
        'is_deleted',
        'deleted_at',
    ];

    protected $casts = [
        'reactions' => 'array', // Deprecated: use messageReactions() relationship instead
        'is_edited' => 'boolean', // Deprecated: use edited_at instead
        'edited_at' => 'datetime',
        'is_deleted' => 'boolean', // Deprecated: use deleted_at instead
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get reactions for this message (new normalized approach)
     */
    public function messageReactions()
    {
        return $this->hasMany(ChatMessageReaction::class, 'message_id');
    }

    /**
     * Get reaction counts grouped by type
     */
    public function getReactionCountsAttribute(): array
    {
        return ChatMessageReaction::getReactionCounts($this->id);
    }

    /**
     * Check if message is edited (using edited_at)
     */
    public function getIsEditedAttribute(): bool
    {
        return $this->edited_at !== null;
    }

    /**
     * Check if message is deleted (using deleted_at)
     */
    public function getIsDeletedAttribute(): bool
    {
        return $this->deleted_at !== null;
    }

    /**
     * Get the conversation this message belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    /**
     * Get the user who sent this message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Check if message has attachment
     */
    public function hasAttachment(): bool
    {
        return ! empty($this->attachment_path);
    }

    /**
     * Get formatted attachment size
     */
    public function getFormattedSizeAttribute()
    {
        if (! $this->attachment_size) {
            return '';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->attachment_size;

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get attachment URL
     */
    public function getAttachmentUrlAttribute()
    {
        if (! $this->attachment_path) {
            return null;
        }

        return asset('storage/' . $this->attachment_path);
    }

    /**
     * Check if message is image
     */
    public function isImage(): bool
    {
        return $this->type === 'image' ||
            (in_array($this->attachment_type, ['jpg', 'jpeg', 'png', 'gif', 'webp']));
    }

    /**
     * Check if message is file
     */
    public function isFile(): bool
    {
        return $this->type === 'file' && ! $this->isImage();
    }

    /**
     * Add reaction to message
     */
    public function addReaction($userId, $emoji)
    {
        $reactions = $this->reactions ?: [];

        if (! isset($reactions[$emoji])) {
            $reactions[$emoji] = [];
        }

        if (! in_array($userId, $reactions[$emoji])) {
            $reactions[$emoji][] = $userId;
        }

        $this->update(['reactions' => $reactions]);
    }

    /**
     * Remove reaction from message
     */
    public function removeReaction($userId, $emoji)
    {
        $reactions = $this->reactions ?: [];

        if (isset($reactions[$emoji])) {
            $reactions[$emoji] = array_filter($reactions[$emoji], function ($id) use ($userId) {
                return $id !== $userId;
            });

            if (empty($reactions[$emoji])) {
                unset($reactions[$emoji]);
            }
        }

        $this->update(['reactions' => $reactions]);
    }

    /**
     * Toggle reaction (add if not present, remove if present)
     */
    public function toggleReaction($userId, $emoji)
    {
        if ($this->hasUserReaction($userId, $emoji)) {
            $this->removeReaction($userId, $emoji);
        } else {
            $this->addReaction($userId, $emoji);
        }
    }

    /**
     * Get reaction count for emoji
     */
    public function getReactionCount($emoji): int
    {
        $reactions = $this->reactions ?: [];

        return isset($reactions[$emoji]) ? count($reactions[$emoji]) : 0;
    }

    /**
     * Check if user has reacted with emoji
     */
    public function hasUserReaction($userId, $emoji): bool
    {
        $reactions = $this->reactions ?: [];

        return isset($reactions[$emoji]) && in_array($userId, $reactions[$emoji]);
    }

    /**
     * Get formatted time for display
     */
    public function getFormattedTimeAttribute()
    {
        // Convert to Asia/Ho_Chi_Minh timezone for display
        return $this->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i');
    }

    /**
     * Get formatted date for display
     */
    public function getFormattedDateAttribute()
    {
        $now = now()->setTimezone('Asia/Ho_Chi_Minh');
        $messageDate = $this->created_at->setTimezone('Asia/Ho_Chi_Minh');

        if ($messageDate->isToday()) {
            return 'Hôm nay';
        } elseif ($messageDate->isYesterday()) {
            return 'Hôm qua';
        } elseif ($messageDate->isCurrentWeek()) {
            return $messageDate->format('l'); // Day name
        } else {
            return $messageDate->format('d/m/Y');
        }
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnreadForUser($query, $userId, $conversationId)
    {
        $participant = ChatParticipant::where('user_id', $userId)
            ->where('conversation_id', $conversationId)
            ->first();

        if (! $participant || ! $participant->last_read_at) {
            return $query->where('conversation_id', $conversationId);
        }

        return $query->where('conversation_id', $conversationId)
            ->where('created_at', '>', $participant->last_read_at);
    }

    /**
     * Scope for messages in conversation
     */
    public function scopeInConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId)
            ->where('is_deleted', false);
    }

    /**
     * Mark message as edited
     */
    public function markAsEdited()
    {
        $this->update([
            'is_edited' => true,
            'edited_at' => now(),
        ]);
    }

    /**
     * Soft delete message
     */
    public function softDelete()
    {
        $this->update([
            'is_deleted' => true,
            'deleted_at' => now(),
        ]);
    }
}
