<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'last_login_at',
        'last_seen_at',
        'online_status',
        'is_typing',
        'typing_started_at',
        'last_activity_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'typing_started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'is_typing' => 'boolean',
    ];

    /**
     * Get the user that owns the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if user is online
     */
    public function isOnline(): bool
    {
        return $this->online_status === 'online';
    }

    /**
     * Update last seen timestamp
     */
    public function updateLastSeen()
    {
        $this->update([
            'last_seen_at' => now(),
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Set online status
     */
    public function setOnlineStatus(string $status)
    {
        $this->update([
            'online_status' => $status,
            'last_seen_at' => now(),
            'last_activity_at' => now(),
        ]);
    }
}

