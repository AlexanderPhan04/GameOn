<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostVisibilityUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'type',
    ];

    // ========== RELATIONSHIPS ==========

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========== SCOPES ==========

    public function scopeIncluded($query)
    {
        return $query->where('type', 'include');
    }

    public function scopeExcluded($query)
    {
        return $query->where('type', 'exclude');
    }

    public function scopeForPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ========== STATIC METHODS ==========

    /**
     * Kiểm tra user có được phép xem post không
     */
    public static function canUserViewPost(int $postId, int $userId, string $visibility): bool
    {
        // Public posts: ai cũng xem được
        if ($visibility === 'public') {
            // Check exclude list
            $isExcluded = self::where('post_id', $postId)
                ->where('user_id', $userId)
                ->where('type', 'exclude')
                ->exists();
            return !$isExcluded;
        }

        // Private/Friends posts: check include list
        if (in_array($visibility, ['private', 'friends', 'custom'])) {
            return self::where('post_id', $postId)
                ->where('user_id', $userId)
                ->where('type', 'include')
                ->exists();
        }

        return false;
    }

    /**
     * Set visibility list cho post
     */
    public static function setVisibilityList(int $postId, array $includeIds, array $excludeIds = []): void
    {
        // Clear existing
        self::where('post_id', $postId)->delete();

        // Add includes
        foreach ($includeIds as $userId) {
            self::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'type' => 'include',
            ]);
        }

        // Add excludes
        foreach ($excludeIds as $userId) {
            self::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'type' => 'exclude',
            ]);
        }
    }
}
