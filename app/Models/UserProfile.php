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
        'bio',
        'date_of_birth',
        'phone',
        'country',
        'gaming_nickname',
        'team_preference',
        'id_app',
        'description',
        'upgraded_to_participant_at',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'upgraded_to_participant_at' => 'datetime',
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

        return asset('uploads/' . $this->avatar);
    }

    /**
     * Check if user is verified
     */
    public function isVerified()
    {
        return $this->is_verified === true;
    }
}
