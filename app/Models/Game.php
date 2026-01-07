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
        'status',
        'is_esport_supported',
        'format_metadata',
        'official_website',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'release_date' => 'date',
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
        $imageUrl = $this->attributes['image_url'] ?? null;
        
        if (!$imageUrl) {
            return null;
        }
        
        // Check if it's already a full URL
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return $imageUrl;
        }
        
        return asset('storage/' . $imageUrl);
    }

    /**
     * Get logo (alias for logo_url)
     */
    public function getLogoAttribute()
    {
        return $this->logo_url;
    }

    /**
     * Get banner URL with full path
     */
    public function getBannerUrlAttribute()
    {
        $banner = $this->attributes['banner_url'] ?? null;
        
        if (!$banner) {
            return null;
        }
        
        // Check if it's already a full URL
        if (filter_var($banner, FILTER_VALIDATE_URL)) {
            return $banner;
        }
        
        return asset('storage/' . $banner);
    }

    /**
     * Get banner (alias for banner_url)
     */
    public function getBannerAttribute()
    {
        return $this->banner_url;
    }

    /**
     * Scope for active games
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for esport supported games
     */
    public function scopeEsportSupported($query)
    {
        return $query->where('is_esport_supported', true);
    }
}
