<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostComment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'parent_id', 'content', 'likes_count'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostCommentLike::class, 'comment_id');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostCommentReaction::class, 'comment_id');
    }
}
