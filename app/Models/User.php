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
        'avatar',
        'full_name',
        'id_app',
        'user_role',
        'bio',
        'date_of_birth',
        'phone',
        'country',
        'status',
        'last_login',
        'last_activity_at',
        'gaming_nickname',
        'team_preference',
        'description',
        'upgraded_to_player_at',
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
            'date_of_birth' => 'date',
            'last_login' => 'datetime',
            'last_activity_at' => 'datetime',
            'upgraded_to_player_at' => 'datetime',
        ];
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
        return $this->user_role === 'player';
    }

    /**
     * Upgrade user to player role
     */
    public function upgradeToPlayer($gamingNickname, $teamPreference = null, $description = null)
    {
        // Validate required fields
        if (empty($gamingNickname)) {
            throw new \Exception('Gaming nickname is required');
        }

        // Update user data
        $this->update([
            'user_role' => 'player',
            'gaming_nickname' => $gamingNickname,
            'team_preference' => $teamPreference,
            'description' => $description,
            'upgraded_to_player_at' => now(),
        ]);

        return $this;
    }

    /**
     * Check if user can upgrade to player
     */
    public function canUpgradeToPlayer()
    {
        return $this->user_role === 'viewer' && $this->isActive();
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
            'player' => 'Người chơi',
            'viewer' => 'Người xem',
            default => 'Không xác định'
        };
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/'.$this->avatar) : null;
    }

    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->name;
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
            'player' => 'bg-primary',
            'viewer' => 'bg-secondary',
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
                    ->orWhere('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "%{$search}%");
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
        return $this->online_status === 'online';
    }

    /**
     * Set user online status
     */
    public function setOnlineStatus($status)
    {
        $this->update([
            'online_status' => $status,
            'last_seen_at' => now(),
        ]);
    }

    /**
     * Get user's display avatar
     */
    public function getDisplayAvatar()
    {
        return $this->avatar
            ? asset('storage/'.$this->avatar)
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
                    ->orWhere('full_name', 'LIKE', "%{$query}%")
                    ->orWhere('id', 'LIKE', "%{$query}%");
            });
        }

        return $search->where('status', 'active')
            ->orderBy('online_status', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }
}
