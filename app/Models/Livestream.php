<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Livestream extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'embed_url',
        'platform',
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'game_id',
        'tournament_id',
        'created_by',
        'view_count',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_LIVE = 'live';
    const STATUS_ENDED = 'ended';

    const PLATFORM_YOUTUBE = 'youtube';
    const PLATFORM_FACEBOOK = 'facebook';

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLive($query)
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Helpers
    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isEnded(): bool
    {
        return $this->status === self::STATUS_ENDED;
    }

    /**
     * Chuyển đổi URL thành embed URL
     */
    public static function convertToEmbedUrl(string $url): array
    {
        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|live\/|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return [
                'platform' => self::PLATFORM_YOUTUBE,
                'embed_url' => 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1',
                'video_id' => $matches[1],
            ];
        }

        // Facebook Video/Live
        if (preg_match('/facebook\.com.*\/videos\/(\d+)|facebook\.com\/.*\/live/', $url)) {
            $encodedUrl = urlencode($url);
            return [
                'platform' => self::PLATFORM_FACEBOOK,
                'embed_url' => 'https://www.facebook.com/plugins/video.php?href=' . $encodedUrl . '&show_text=false&autoplay=true',
                'video_id' => null,
            ];
        }

        // Nếu đã là embed URL
        if (str_contains($url, 'youtube.com/embed/') || str_contains($url, 'facebook.com/plugins/video.php')) {
            $platform = str_contains($url, 'youtube') ? self::PLATFORM_YOUTUBE : self::PLATFORM_FACEBOOK;
            return [
                'platform' => $platform,
                'embed_url' => $url,
                'video_id' => null,
            ];
        }

        return [
            'platform' => 'unknown',
            'embed_url' => $url,
            'video_id' => null,
        ];
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_SCHEDULED => 'Đã lên lịch',
            self::STATUS_LIVE => 'Đang phát',
            self::STATUS_ENDED => 'Đã kết thúc',
            default => 'Không xác định',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_SCHEDULED => 'status-scheduled',
            self::STATUS_LIVE => 'status-live',
            self::STATUS_ENDED => 'status-ended',
            default => 'status-unknown',
        };
    }

    public function getPlatformIcon(): string
    {
        return match ($this->platform) {
            self::PLATFORM_YOUTUBE => 'fab fa-youtube',
            self::PLATFORM_FACEBOOK => 'fab fa-facebook',
            default => 'fas fa-video',
        };
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
