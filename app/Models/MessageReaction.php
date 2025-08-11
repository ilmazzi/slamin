<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Redis;

class MessageReaction extends Model
{
    use HasFactory;

    protected $table = 'chat_message_reactions';

    protected $fillable = [
        'message_id',
        'user_id',
        'reaction',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relazione con il messaggio
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'message_id', 'id');
    }

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cache delle reazioni per un messaggio in Redis
     */
    public static function getCachedReactions(int $messageId): array
    {
        $cacheKey = "message_reactions:{$messageId}";
        $cached = Redis::get($cacheKey);

        if ($cached) {
            return json_decode($cached, true);
        }

        // Se non in cache, recupera dal DB e metti in cache
        $reactions = static::where('message_id', $messageId)
            ->with('user:id,name')
            ->get()
            ->groupBy('reaction')
            ->map(function ($group) {
                return [
                    'emoji' => $group->first()->reaction,
                    'count' => $group->count(),
                    'users' => $group->pluck('user')->map(function($user) {
                        // Usa AvatarHelper per generare l'URL dell'avatar
                        $user['avatar'] = \App\Helpers\AvatarHelper::getUserAvatarUrl($user);
                        return $user;
                    })->toArray()
                ];
            })
            ->values()
            ->toArray();

        // Cache per 1 ora
        Redis::setex($cacheKey, 3600, json_encode($reactions));

        return $reactions;
    }

    /**
     * Aggiunge una reazione e aggiorna la cache
     */
    public static function addReaction(int $messageId, int $userId, string $emoji): static
    {
        // Rimuovi reazioni esistenti dello stesso utente per questo messaggio
        static::where('message_id', $messageId)
            ->where('user_id', $userId)
            ->delete();

        // Aggiungi nuova reazione
        $reaction = static::create([
            'message_id' => $messageId,
            'user_id' => $userId,
            'reaction' => $emoji,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Invalida cache
        Redis::del("message_reactions:{$messageId}");

        return $reaction;
    }

    /**
     * Rimuove una reazione e aggiorna la cache
     */
    public static function removeReaction(int $messageId, int $userId): bool
    {
        $deleted = static::where('message_id', $messageId)
            ->where('user_id', $userId)
            ->delete();

        if ($deleted) {
            // Invalida cache
            Redis::del("message_reactions:{$messageId}");
        }

        return $deleted > 0;
    }

    /**
     * Ottiene le reazioni aggregate per emoji
     */
    public static function getAggregatedReactions(int $messageId): array
    {
        return static::where('message_id', $messageId)
            ->selectRaw('reaction, COUNT(*) as count')
            ->groupBy('reaction')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
    }
}
