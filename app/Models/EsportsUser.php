<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * EsportsUser Model
 * Chuyển đổi từ EsportsManager.DAL.Entities.Users
 * Quản lý người dùng hệ thống Esports
 */
class EsportsUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'esports_users';

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'full_name',
        'role',
        'status',
        'is_email_verified',
        'email_verification_token',
        'password_reset_token',
        'password_reset_expiry',
        'security_question',
        'security_answer',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password_hash',
        'password_reset_token',
        'email_verification_token',
        'security_answer',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_email_verified' => 'boolean',
        'password_reset_expiry' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Constants cho Role (tương ứng UsersRoles trong C#)
    const ROLE_ADMIN = 'Admin';

    const ROLE_PLAYER = 'Player';

    const ROLE_VIEWER = 'Viewer';

    // Constants cho Status (tương ứng UsersStatus trong C#)
    const STATUS_ACTIVE = 'Active';

    const STATUS_SUSPENDED = 'Suspended';

    const STATUS_INACTIVE = 'Inactive';

    const STATUS_PENDING = 'Pending';

    const STATUS_DELETED = 'Deleted';

    /**
     * Override password field for authentication
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Hash password before saving
     */
    public function setPasswordHashAttribute($value)
    {
        $this->attributes['password_hash'] = Hash::make($value);
    }

    /**
     * Hash security answer before saving
     */
    public function setSecurityAnswerAttribute($value)
    {
        if ($value) {
            $this->attributes['security_answer'] = Hash::make(strtolower($value));
        }
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is player
     */
    public function isPlayer(): bool
    {
        return $this->role === self::ROLE_PLAYER;
    }

    /**
     * Check if user is viewer
     */
    public function isViewer(): bool
    {
        return $this->role === self::ROLE_VIEWER;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    /**
     * Check if user is pending approval
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->last_login_at = now();
        $this->save();
    }

    /**
     * Verify security answer
     */
    public function verifySecurityAnswer(string $answer): bool
    {
        return $this->security_answer && Hash::check(strtolower($answer), $this->security_answer);
    }

    /**
     * Get role display name in Vietnamese
     */
    public function getRoleDisplayNameAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Quản trị viên',
            self::ROLE_PLAYER => 'Người chơi',
            self::ROLE_VIEWER => 'Người xem',
            default => 'Không xác định'
        };
    }

    /**
     * Get status display name in Vietnamese
     */
    public function getStatusDisplayNameAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_SUSPENDED => 'Tạm khóa',
            self::STATUS_INACTIVE => 'Không hoạt động',
            self::STATUS_PENDING => 'Chờ duyệt',
            self::STATUS_DELETED => 'Đã xóa',
            default => 'Không xác định'
        };
    }

    /**
     * Scope for active users only
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
