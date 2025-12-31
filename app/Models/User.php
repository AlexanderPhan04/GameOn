<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $user_role
 *
 * @prop    public function updateOnlineStatus($status = 'online')
    }ring $status
 * @property string $name
 * @property string $email
 * @property string $google_id
 * @property string $google_email
 *
 * @method bool isSuperAdmin()
 * @method bool isAdmin()
 * @method bool isPlayer()
 * @method bool isViewer()
 * @method bool canManageUsers()
 * @method bool isActive()
 * @method bool isSuspended()
 * @method bool isBanned()
 * @method bool isDeleted()
 * @method \Illuminate\Database\Eloquent\Collection getRecentConversations(int $limit = 20)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'google_email',
        'email_verification_token',
        'user_role',
        'status',
        'is_verified_gamer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELATIONSHIPS ==========

    /**
     * Get the user's profile.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    /**
     * Get the user's activity.
     */
    public function activity()
    {
        return $this->hasOne(UserActivity::class, 'user_id');
    }

    /**
     * Get user's transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Relationships
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members')->withPivot(['role', 'status', 'joined_at'])->withTimestamps();
    }

    public function captainTeams()
    {
        return $this->hasMany(Team::class, 'captain_id');
    }

    public function createdTeams()
    {
        return $this->hasMany(Team::class, 'created_by');
    }

    // Helper methods
    public function isSuperAdmin()
    {
        return $this->user_role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->user_role === 'admin' || $this->isSuperAdmin();
    }

    public function isPlayer()
    {
        return $this->user_role === 'participant' || $this->user_role === 'player';
    }

    /**
     * Check if user is a participant (new role combining player + viewer)
     */
    public function isParticipant()
    {
        return $this->user_role === 'participant';
    }

    /**
     * Check if user is a verified gamer (has blue tick)
     */
    public function isVerifiedGamer()
    {
        return $this->is_verified_gamer ?? false;
    }

    /**
     * Grant verified gamer status (blue tick)
     */
    public function grantVerifiedGamer()
    {
        $this->update(['is_verified_gamer' => true]);
        return $this;
    }

    /**
     * Revoke verified gamer status
     */
    public function revokeVerifiedGamer()
    {
        $this->update(['is_verified_gamer' => false]);
        return $this;
    }

    /**
     * Upgrade user to participant role with gaming profile
     */
    public function upgradeToParticipant($gamingNickname, $teamPreference = null, $description = null)
    {
        // Validate required fields
        if (empty($gamingNickname)) {
            throw new \Exception('Gaming nickname is required');
        }

        // Update user role to participant
        $this->update(['user_role' => 'participant']);

        // Update or create profile
        $this->profile()->updateOrCreate(
            ['user_id' => $this->id],
            [
                'gaming_nickname' => $gamingNickname,
                'team_preference' => $teamPreference,
                'description' => $description,
                'upgraded_to_player_at' => now(),
            ]
        );

        return $this;
    }

    /**
     * Check if user can upgrade (legacy method for compatibility)
     */
    public function canUpgradeToPlayer()
    {
        return $this->canUpgradeToParticipant();
    }

    /**
     * Check if user can set gaming profile
     */
    public function canUpgradeToParticipant()
    {
        return $this->user_role === 'participant' && $this->isActive();
    }

    public function canManageUsers()
    {
        return $this->isSuperAdmin();
    }

    public function getRoleDisplayNameAttribute()
    {
        return match ($this->user_role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Quản trị viên',
            'participant' => $this->is_verified_gamer ? 'Pro Gamer ✓' : 'Participant',
            'player' => 'Participant', // Legacy support
            'viewer' => 'Participant', // Legacy support
            default => 'Không xác định'
        };
    }

    public function getAvatarUrlAttribute()
    {
        return $this->profile?->avatar ? asset('storage/'.$this->profile->avatar) : null;
    }

    public function getDisplayNameAttribute()
    {
        return $this->profile?->full_name ?: $this->name;
    }

    /**
     * Get full_name from profile
     */
    public function getFullNameAttribute()
    {
        return $this->profile?->full_name;
    }

    /**
     * Get avatar from profile
     */
    public function getAvatarAttribute()
    {
        return $this->profile?->avatar;
    }

    /**
     * Get gaming_nickname from profile
     */
    public function getGamingNicknameAttribute()
    {
        return $this->profile?->gaming_nickname;
    }

    /**
     * Check if user is verified
     */
    public function isVerified()
    {
        return $this->profile?->is_verified ?? false;
    }

    // Status helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function isBanned()
    {
        return $this->status === 'banned';
    }

    public function isDeleted()
    {
        return $this->status === 'deleted';
    }

    public function getStatusDisplayNameAttribute()
    {
        return match ($this->status) {
            'active' => 'Hoạt động',
            'suspended' => 'Tạm khóa',
            'banned' => 'Cấm vĩnh viễn',
            'deleted' => 'Đã xóa',
            default => 'Không xác định'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'active' => 'bg-success',
            'suspended' => 'bg-warning',
            'banned' => 'bg-danger',
            'deleted' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    public function getRoleBadgeClassAttribute()
    {
        return match ($this->user_role) {
            'super_admin' => 'bg-danger',
            'admin' => 'bg-warning',
            'participant' => $this->is_verified_gamer ? 'bg-info' : 'bg-primary',
            'player' => 'bg-primary', // Legacy
            'viewer' => 'bg-secondary', // Legacy
            default => 'bg-secondary'
        };
    }

    // Scope methods for filtering
    public function scopeWithRole($query, $role)
    {
        if ($role && $role !== 'all') {
            return $query->where('user_role', $role);
        }

        return $query;
    }

    public function scopeWithStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "%{$search}%")
                    ->orWhereHas('profile', function ($profileQuery) use ($search) {
                        $profileQuery->where('full_name', 'LIKE', "%{$search}%")
                            ->orWhere('id_app', 'LIKE', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    // ========== CHAT RELATIONSHIPS ==========

    /**
     * Get conversations this user participates in
     */
    public function conversations()
    {
        return $this->belongsToMany(ChatConversation::class, 'chat_participants', 'user_id', 'conversation_id')
            ->withPivot(['role', 'joined_at', 'last_read_at', 'is_blocked', 'is_muted', 'muted_until'])
            ->withTimestamps()
            ->where('is_active', true);
    }

    /**
     * Get messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id')
            ->where('is_deleted', false);
    }

    /**
     * Get chat participants for this user
     */
    public function chatParticipants()
    {
        return $this->hasMany(ChatParticipant::class, 'user_id');
    }

    // ========== CHAT METHODS ==========

    /**
     * Check if user is online
     */
    public function isOnline(): bool
    {
        return $this->activity?->online_status === 'online';
    }

    /**
     * Set user online status
     */
    public function setOnlineStatus($status)
    {
        $this->activity()->updateOrCreate(
            ['user_id' => $this->id],
            [
                'online_status' => $status,
                'last_seen_at' => now(),
                'last_activity_at' => now(),
            ]
        );
    }

    /**
     * Get online_status from activity
     */
    public function getOnlineStatusAttribute()
    {
        return $this->activity?->online_status ?? 'offline';
    }

    /**
     * Get last_seen_at from activity
     */
    public function getLastSeenAtAttribute()
    {
        return $this->activity?->last_seen_at;
    }

    /**
     * Get last_activity_at from activity
     */
    public function getLastActivityAtAttribute()
    {
        return $this->activity?->last_activity_at;
    }

    /**
     * Get is_typing from activity
     */
    public function getIsTypingAttribute()
    {
        return $this->activity?->is_typing ?? false;
    }

    /**
     * Get user's display avatar
     */
    public function getDisplayAvatar()
    {
        return $this->profile?->avatar
            ? asset('storage/'.$this->profile->avatar)
            : asset('images/default-avatar.png');
    }

    /**
     * Get unread messages count for user
     */
    public function getUnreadMessagesCount()
    {
        return ChatParticipant::where('user_id', $this->id)
            ->whereHas('conversation', function ($q) {
                $q->where('is_active', true);
            })
            ->sum(function ($participant) {
                return $participant->getUnreadCount();
            });
    }

    /**
     * Create or get private conversation with another user
     */
    public function getOrCreateConversationWith(User $otherUser)
    {
        return ChatConversation::createOrGetPrivate($this->id, $otherUser->id);
    }

    /**
     * Get recent conversations for this user
     */
    public function getRecentConversations($limit = 20)
    {
        return $this->conversations()
            ->with(['participants.user', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->orderBy('last_message_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search users for chat (excluding current user)
     */
    public static function searchForChat($query, $excludeUserId = null)
    {
        $search = self::query();

        if ($excludeUserId) {
            $search->where('id', '!=', $excludeUserId);
        }

        if ($query) {
            $search->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('id', 'LIKE', "%{$query}%")
                    ->orWhereHas('profile', function ($profileQuery) use ($query) {
                        $profileQuery->where('full_name', 'LIKE', "%{$query}%")
                            ->orWhere('id_app', 'LIKE', "%{$query}%");
                    });
            });
        }

        return $search->where('status', 'active')
            ->with('activity')
            ->get()
            ->sortByDesc(function ($user) {
                return $user->activity?->online_status === 'online' ? 1 : 0;
            })
            ->take(50)
            ->values();
    }
}
