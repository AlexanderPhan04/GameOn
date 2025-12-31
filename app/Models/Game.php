<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'genre',
        'developer',
        'publisher',
        'release_date',
        'image_url',
        'banner_url',
        'max_team_size',
        'format',
        'is_active',
        'status',
        'is_esport_supported',
        'format_metadata',
        'official_website',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_active' => 'boolean',
        'is_esport_supported' => 'boolean',
        'format_metadata' => 'array',
    ];

    /**
     * Get the tournaments for this game.
     */
    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }

    /**
     * Get the teams that play this game.
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the user who created this game.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this game.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get logo URL (alias for image_url)
     */
    public function getLogoUrlAttribute()
    {
        return $this->image_url;
    }

    /**
     * Get banner URL
     */
    public function getBannerUrlAttribute()
    {
        return $this->attributes['banner_url'] ?? null;
    }

    /**
     * Scope for active games
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_active', true)
              ->orWhere('status', 'active');
        });
    }

    /**
     * Scope for esport supported games
     */
    public function scopeEsportSupported($query)
    {
        return $query->where('is_esport_supported', true);
    }
}
