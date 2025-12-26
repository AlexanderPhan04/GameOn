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
        'release_date',
        'developer',
        'publisher',
        'platform',
        'image',
        'status',
    ];

    protected $casts = [
        'release_date' => 'date',
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
}
