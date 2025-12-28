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
        'upgraded_to_player_at',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'upgraded_to_player_at' => 'datetime',
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
        return $this->avatar ? asset('storage/'.$this->avatar) : null;
    }

    /**
     * Check if user is verified
     */
    public function isVerified()
    {
        return $this->is_verified === true;
    }
}

