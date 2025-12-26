<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'joined_at',
        'last_read_at',
        'is_blocked',
        'is_muted',
        'muted_until',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_read_at' => 'datetime',
        'is_blocked' => 'boolean',
        'is_muted' => 'boolean',
        'muted_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the conversation this participant belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    /**
     * Get the user associated with this participant
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if participant is admin or owner
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'owner']);
    }

    /**
     * Check if participant is owner
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if participant can manage conversation
     */
    public function canManage(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if participant is currently muted
     */
    public function isMuted(): bool
    {
        if (! $this->is_muted) {
            return false;
        }

        if ($this->muted_until && $this->muted_until->isPast()) {
            // Unmute if time has passed
            $this->update([
                'is_muted' => false,
                'muted_until' => null,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Block this participant
     */
    public function block()
    {
        $this->update(['is_blocked' => true]);
    }

    /**
     * Unblock this participant
     */
    public function unblock()
    {
        $this->update(['is_blocked' => false]);
    }

    /**
     * Mute this participant
     */
    public function mute($until = null)
    {
        $this->update([
            'is_muted' => true,
            'muted_until' => $until,
        ]);
    }

    /**
     * Unmute this participant
     */
    public function unmute()
    {
        $this->update([
            'is_muted' => false,
            'muted_until' => null,
        ]);
    }

    /**
     * Update last read timestamp
     */
    public function markAsRead()
    {
        $this->update(['last_read_at' => now()]);
    }

    /**
     * Get unread messages count for this participant
     */
    public function getUnreadCount()
    {
        if (! $this->last_read_at) {
            return $this->conversation->messages()->count();
        }

        return $this->conversation->messages()
            ->where('created_at', '>', $this->last_read_at)
            ->count();
    }

    /**
     * Scope for active participants (not blocked)
     */
    public function scopeActive($query)
    {
        return $query->where('is_blocked', false);
    }

    /**
     * Scope for admins and owners
     */
    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['admin', 'owner']);
    }
}
