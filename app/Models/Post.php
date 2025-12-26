<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'content', 'media_path', 'shared_post_id', 'root_post_id', 'likes_count', 'comments_count', 'shares_count', 'visibility', 'visibility_include_ids', 'visibility_exclude_ids',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sharedPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'shared_post_id');
    }

    public function rootPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'root_post_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class);
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(PostMention::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }
}
