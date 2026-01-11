<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'full_name',
        'avatar',
        'google_avatar',
        'bio',
        'date_of_birth',
        'phone',
        'country',
        'id_app',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null;
        }

        // Check if it's already a full URL (e.g., Google avatar)
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        // System avatar path (e.g., "system/avatar_1.png")
        if (str_starts_with($this->avatar, 'system/')) {
            return asset('images/system-avatars/' . str_replace('system/', '', $this->avatar));
        }

        return asset('storage/' . $this->avatar);
    }

    /**
     * Check if user is verified
     */
    public function isVerified()
    {
        return $this->is_verified === true;
    }

    /**
     * Check if Google avatar URL is likely valid (not expired)
     * Note: Google avatar URLs from OAuth often expire after some time
     */
    public function hasValidGoogleAvatar(): bool
    {
        if (empty($this->google_avatar)) {
            return false;
        }

        // Google URLs with certain patterns are known to expire
        // We can't truly validate without making HTTP request, so we assume invalid
        // if it's a googleusercontent URL (they expire frequently)
        if (str_contains($this->google_avatar, 'googleusercontent.com')) {
            return false;
        }

        return filter_var($this->google_avatar, FILTER_VALIDATE_URL) !== false;
    }
}
