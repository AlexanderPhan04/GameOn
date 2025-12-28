<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'game_id',
        'created_by',
        'start_date',
        'end_date',
        'registration_deadline',
        'max_teams',
        'max_participants',
        'entry_fee',
        'prize_pool',
        'prize_distribution',
        'status',
        'format',
        'competition_type',
        'rules',
        'location_type',
        'location_address',
        'image_url',
        'stream_link',
        'organizer_id',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'entry_fee' => 'decimal:2',
        'prize_pool' => 'decimal:2',
        'prize_distribution' => 'array',
        'rules' => 'array',
        'is_active' => 'boolean',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'tournament_teams')
            ->withPivot('joined_at', 'status')
            ->withTimestamps();
    }

    /**
     * Scope for active tournaments
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_active', true)
              ->orWhere('status', '!=', 'cancelled');
        });
    }

    /**
     * Scope for upcoming tournaments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'registration')
            ->where('start_date', '>=', now());
    }
}
