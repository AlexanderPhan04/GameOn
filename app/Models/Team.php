<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'game_id',
        'created_by',
        'captain_id',
        'logo_url',
        'max_members',
        'status',
    ];

    protected $casts = [
        //
    ];

    // Accessors
    public function getLogoAttribute()
    {
        if (!$this->logo_url) {
            return null;
        }
        
        // If it's already a full URL, return as is
        if (filter_var($this->logo_url, FILTER_VALIDATE_URL)) {
            return $this->logo_url;
        }
        
        // Logo is stored in storage/app/public via 'public' disk
        return asset('storage/' . $this->logo_url);
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')->withPivot(['role', 'status', 'joined_at'])->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->members()->wherePivot('status', 'active');
    }

    // Helper methods
    public function isCaptain($user)
    {
        return $this->captain_id === $user->id;
    }

    public function isMember($user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function canJoin()
    {
        return $this->activeMembers()->count() < $this->max_members;
    }

    public function getLogoUrlAttribute($value)
    {
        return $value ? asset('uploads/' . $value) : null;
    }
}
