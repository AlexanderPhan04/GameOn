<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessageReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'user_id',
        'type',
    ];

    // ========== RELATIONSHIPS ==========

    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'message_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========== SCOPES ==========

    public function scopeForMessage($query, $messageId)
    {
        return $query->where('message_id', $messageId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ========== STATIC METHODS ==========

    /**
     * Toggle reaction cho user trên message
     */
    public static function toggleReaction(int $messageId, int $userId, string $type): ?self
    {
        $existing = self::where('message_id', $messageId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            if ($existing->type === $type) {
                // Nếu đã có cùng reaction → xóa
                $existing->delete();
                return null;
            } else {
                // Nếu khác reaction → update
                $existing->update(['type' => $type]);
                return $existing;
            }
        }

        // Chưa có → tạo mới
        return self::create([
            'message_id' => $messageId,
            'user_id' => $userId,
            'type' => $type,
        ]);
    }

    /**
     * Get reaction counts cho message
     */
    public static function getReactionCounts(int $messageId): array
    {
        return self::where('message_id', $messageId)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }
}
