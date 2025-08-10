<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Events\UserStartedTyping;
use App\Events\UserStoppedTyping;

class TypingService
{
    const TYPING_TIMEOUT = 300; // 5 minuti in secondi
    const TYPING_PREFIX = 'typing:room:';
    const TIMESTAMP_PREFIX = 'typing:timestamps:';

    /**
     * Inizia il typing per un utente in una chat room
     */
    public function startTyping($roomId, $userId, $userName)
    {
        Log::info('TypingService: Starting typing', [
            'room_id' => $roomId,
            'user_id' => $userId,
            'user_name' => $userName
        ]);

        $roomKey = self::TYPING_PREFIX . $roomId;
        $timestampKey = self::TIMESTAMP_PREFIX . $roomId;
        $timestamp = microtime(true);

        // Aggiungi utente al set typing
        Redis::hset($roomKey, "user:{$userId}", $userName);
        Redis::zadd($timestampKey, $timestamp, "user:{$userId}");

        // Imposta TTL per cleanup automatico
        Redis::expire($roomKey, self::TYPING_TIMEOUT);
        Redis::expire($timestampKey, self::TYPING_TIMEOUT);

        // Ottieni lista aggiornata utenti typing
        $typingUsers = $this->getTypingUsers($roomId);

        Log::info('TypingService: Typing users after start', [
            'room_id' => $roomId,
            'typing_users' => $typingUsers
        ]);

        // Broadcast evento
        Log::info('TypingService: Broadcasting UserStartedTyping event', [
            'room_id' => $roomId,
            'user_id' => $userId,
            'user_name' => $userName
        ]);

        try {
            $event = new UserStartedTyping($roomId, $userId, $userName, $typingUsers);
            Log::info('TypingService: Event created', [
                'event_class' => get_class($event),
                'event_data' => [
                    'room_id' => $event->roomId,
                    'user_id' => $event->userId,
                    'user_name' => $event->userName,
                    'typing_users' => $event->typingUsers
                ]
            ]);

            event($event);
            Log::info('TypingService: Event broadcasted successfully');
        } catch (\Exception $e) {
            Log::error('TypingService: Error broadcasting event', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $typingUsers;
    }

    /**
     * Ferma il typing per un utente in una chat room
     */
    public function stopTyping($roomId, $userId, $userName)
    {
        Log::info('TypingService: Stopping typing', [
            'room_id' => $roomId,
            'user_id' => $userId,
            'user_name' => $userName
        ]);

        $roomKey = self::TYPING_PREFIX . $roomId;
        $timestampKey = self::TIMESTAMP_PREFIX . $roomId;

        // Rimuovi utente dal set typing
        Redis::hdel($roomKey, "user:{$userId}");
        Redis::zrem($timestampKey, "user:{$userId}");

        // Ottieni lista aggiornata utenti typing
        $typingUsers = $this->getTypingUsers($roomId);

        Log::info('TypingService: Typing users after stop', [
            'room_id' => $roomId,
            'typing_users' => $typingUsers
        ]);

        // Broadcast evento
        Log::info('TypingService: Broadcasting UserStoppedTyping event', [
            'room_id' => $roomId,
            'user_id' => $userId,
            'user_name' => $userName
        ]);

        try {
            $event = new UserStoppedTyping($roomId, $userId, $userName, $typingUsers);
            event($event);
            Log::info('TypingService: UserStoppedTyping event broadcasted successfully');
        } catch (\Exception $e) {
            Log::error('TypingService: Error broadcasting UserStoppedTyping event', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $typingUsers;
    }

    /**
     * Ottieni tutti gli utenti che stanno scrivendo in una room
     */
    public function getTypingUsers($roomId)
    {
        $roomKey = self::TYPING_PREFIX . $roomId;
        return Redis::hgetall($roomKey);
    }

    /**
     * Controlla se un utente specifico sta scrivendo
     */
    public function isUserTyping($roomId, $userId)
    {
        $roomKey = self::TYPING_PREFIX . $roomId;
        return Redis::hexists($roomKey, "user:{$userId}");
    }

    /**
     * Cleanup automatico utenti scaduti
     */
    public function cleanupExpired($roomId)
    {
        $timestampKey = self::TIMESTAMP_PREFIX . $roomId;
        $cutoffTime = microtime(true) - self::TYPING_TIMEOUT;

        // Trova utenti con timestamp scaduto
        $expiredUsers = Redis::zrangebyscore($timestampKey, 0, $cutoffTime);

        foreach ($expiredUsers as $userKey) {
            $userId = str_replace('user:', '', $userKey);
            $userName = $this->getTypingUsers($roomId)["user:{$userId}"] ?? 'Unknown User';
            $this->stopTyping($roomId, $userId, $userName);
        }

        return count($expiredUsers);
    }

    /**
     * Cleanup globale per tutte le room
     */
    public function cleanupAllExpired()
    {
        $pattern = self::TYPING_PREFIX . '*';
        $keys = Redis::keys($pattern);
        $totalCleaned = 0;

        foreach ($keys as $key) {
            $roomId = str_replace(self::TYPING_PREFIX, '', $key);
            $totalCleaned += $this->cleanupExpired($roomId);
        }

        return $totalCleaned;
    }

    /**
     * Ottieni statistiche typing per una room
     */
    public function getTypingStats($roomId)
    {
        $roomKey = self::TYPING_PREFIX . $roomId;
        $timestampKey = self::TIMESTAMP_PREFIX . $roomId;

        return [
            'active_users' => Redis::hlen($roomKey),
            'total_typing' => Redis::zcard($timestampKey),
            'room_id' => $roomId,
            'last_activity' => Redis::zrevrange($timestampKey, 0, 0, 'WITHSCORES')
        ];
    }
}
