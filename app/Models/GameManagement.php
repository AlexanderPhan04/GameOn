<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GameManagement extends Model
{
    protected $table = 'games';

    protected $fillable = [
        'name',
        'genre',
        'publisher',
        'release_date',
        'status',
        'esport_support',
        'team_size',
        'competition_formats',
        'game_modes',
        'logo',
        'banner',
        'description',
        'official_website',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'release_date' => 'date',
        'esport_support' => 'boolean',
        'competition_formats' => 'array',
        'game_modes' => 'array',
    ];

    /**
     * Relationship với User (người tạo)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship với User (người cập nhật)
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get logo URL with fallback
     */
    public function getLogoUrlAttribute(): string
    {
        if (! $this->logo) {
            return asset('images/default-game-logo.svg');
        }

        // Check if it's already a full URL (external CDN)
        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        // Check if local file exists, otherwise use default
        if (Storage::disk('public')->exists($this->logo)) {
            return asset('storage/'.$this->logo);
        }

        // File doesn't exist, use default
        return asset('images/default-game-logo.svg');
    }

    /**
     * Get banner URL with fallback
     */
    public function getBannerUrlAttribute(): string
    {
        if (! $this->banner) {
            return asset('images/default-game-banner.svg');
        }

        // Check if it's already a full URL (external CDN)
        if (filter_var($this->banner, FILTER_VALIDATE_URL)) {
            return $this->banner;
        }

        // Check if local file exists, otherwise use default
        if (Storage::disk('public')->exists($this->banner)) {
            return asset('storage/'.$this->banner);
        }

        // File doesn't exist, use default
        return asset('images/default-game-banner.svg');
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
        return $query->where('esport_support', true);
    }

    /**
     * Get formatted team size
     */
    public function getFormattedTeamSizeAttribute(): string
    {
        return $this->team_size ?: 'Chưa xác định';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'active' ? __('app.games.active') : __('app.games.inactive');
    }

    /**
     * Get esport support label
     */
    public function getEsportSupportLabelAttribute(): string
    {
        return $this->esport_support ? 'Có hỗ trợ' : 'Không hỗ trợ';
    }
}
