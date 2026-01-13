<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $user_role
 * @property string $status
 * @property string $name
 * @property string $email
 * @property string $google_id
 * @property string $google_email
 *
 * @method bool isSuperAdmin()
 * @method bool isAdmin()
 * @method bool isParticipant()
 * @method bool canManageUsers()
 * @method bool isActive()
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
        'google_id',
        'google_email',
        'email_verification_token',
        'user_role',
        'status',
        'is_verified_gamer',
        'suspended_from',
        'suspended_until',
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
            'suspended_from' => 'datetime',
            'suspended_until' => 'datetime',
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

    /**
     * Check if user is a participant
     */
    public function isParticipant()
    {
        return $this->user_role === 'participant';
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
            default => 'Không xác định'
        };
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

    // Status helper methods
    public function isActive()
    {
        return $this->status === 'active';
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

    // ========== CHAT METHODS ==========

    /**
     * Check if user is online
     */
    public function isOnline(): bool
    {
        return $this->activity?->online_status === 'online';
    }

    /**
     * Get online_status from activity
     */
    public function getOnlineStatusAttribute()
    {
        return $this->activity?->online_status ?? 'offline';
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
     * Avatar is stored locally (uploaded, system, or downloaded from Google)
     * google_avatar column stores original Google URL for user to switch back
     */
    public function getDisplayAvatar()
    {
        $avatar = $this->profile?->avatar;

        if ($avatar) {
            // Check if it's a full URL (legacy data - Google URL stored directly)
            if (filter_var($avatar, FILTER_VALIDATE_URL)) {
                // Google URLs expire, return default instead
                if (str_contains($avatar, 'googleusercontent.com') || str_contains($avatar, 'google.com')) {
                    return $this->getDefaultAvatar();
                }
                return $avatar;
            }

            // System avatar path (e.g., "system/avatar_1.png")
            if (str_starts_with($avatar, 'system/')) {
                return asset('images/system-avatars/' . str_replace('system/', '', $avatar));
            }

            // Local uploaded/downloaded file path
            // Use Storage facade to get correct URL based on filesystems config
            return \Illuminate\Support\Facades\Storage::disk('public')->url($avatar);
        }

        // No avatar set, return default
        return $this->getDefaultAvatar();
    }

    /**
     * Get default avatar URL based on user's name
     */
    public function getDefaultAvatar()
    {
        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&size=128&background=667eea&color=ffffff&bold=true&format=svg";
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
            ->with('profile')
            ->orderBy('name')
            ->take(50)
            ->get();
    }

    // ========== ADMIN PERMISSION METHODS ==========

    /**
     * Get the admin permission record for this user
     */
    public function adminPermission()
    {
        return $this->hasOne(AdminPermission::class);
    }

    /**
     * Check if admin has a specific permission
     */
    public function hasAdminPermission(string $permission): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Regular user has no admin permissions
        if (!$this->isAdmin()) {
            return false;
        }

        return $this->adminPermission?->hasPermission($permission) ?? false;
    }

    /**
     * Check if admin has all specified permissions
     */
    public function hasAllAdminPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasAdminPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if admin has any of the specified permissions
     */
    public function hasAnyAdminPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasAdminPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get all admin permissions as array
     */
    public function getAdminPermissions(): array
    {
        if ($this->isSuperAdmin()) {
            return array_keys(AdminPermission::AVAILABLE_PERMISSIONS);
        }

        return $this->adminPermission?->permissions ?? [];
    }
}
