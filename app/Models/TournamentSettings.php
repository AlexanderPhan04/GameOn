<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'prize_pool',
        'prize_distribution',
        'max_teams',
        'format',
        'competition_type',
        'rules',
    ];

    protected $casts = [
        'prize_pool' => 'decimal:2',
        'prize_distribution' => 'array',
        'max_teams' => 'integer',
        'rules' => 'array',
    ];

    /**
     * Tournament này thuộc về
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Lấy format hiển thị đẹp
     */
    public function getFormatLabelAttribute(): string
    {
        return match ($this->format) {
            'single_elimination' => 'Single Elimination',
            'double_elimination' => 'Double Elimination',
            'round_robin' => 'Round Robin',
            'swiss' => 'Swiss System',
            default => ucfirst($this->format),
        };
    }

    /**
     * Lấy competition type label
     */
    public function getCompetitionTypeLabelAttribute(): string
    {
        return match ($this->competition_type) {
            'individual' => 'Cá nhân',
            'team' => 'Đội',
            'mixed' => 'Hỗn hợp',
            default => ucfirst($this->competition_type),
        };
    }

    /**
     * Kiểm tra còn slot không
     */
    public function hasAvailableSlots(int $currentTeams): bool
    {
        return $currentTeams < $this->max_teams;
    }
}
