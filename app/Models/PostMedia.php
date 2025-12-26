<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends Model
{
    protected $fillable = ['post_id', 'type', 'path', 'thumb'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
