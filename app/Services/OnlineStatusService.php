<?php

namespace App\Services;

use App\Events\PresenceUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

class OnlineStatusService
{
    protected string $redisPrefix;
    protected int $ttlSeconds;
    protected int $dbUpdateInterval;
    protected int $recentWindow;
    protected int $idleWindow;
    protected string $connection = 'default';

    public function __construct()
    {
        $this->redisPrefix      = (string) config('online.redis_prefix', 'online:user:');
        $this->ttlSeconds       = (int) config('online.ttl_seconds', 120);
        $this->dbUpdateInterval = (int) config('online.db_update_interval_seconds', 300);
        $this->recentWindow     = (int) config('online.recent_window_seconds', 300);
        $this->idleWindow       = (int) config('online.idle_window_seconds', 1800);
    }

    protected function key(int|string $userId): string
    {
        return $this->redisPrefix . $userId;
    }

    public function setOnline(int|string $userId): void
    {
        $key   = $this->key($userId);
        $value = Carbon::now()->toIso8601String();

        try {
            // rinnova presenza
            Redis::connection($this->connection)->setex($key, $this->ttlSeconds, $value);
            $ttl = (int) Redis::connection($this->connection)->ttl($key);

            // throttling broadcast per non spammare (es. max 1 evento/15s per utente)
            $throttleKey = "presence:broadcast:{$userId}";
            if (Cache::add($throttleKey, 1, 15)) {
                event(new PresenceUpdated($userId, 'online', $ttl));
            }
        } catch (\Throwable $e) {
            \Log::error('OnlineStatusService setOnline error', [
                'key' => $key,
                'ttl' => $this->ttlSeconds,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function isOnline(int|string $userId): bool
    {
        try {
            $exists = Redis::connection($this->connection)->exists($this->key($userId));
            return (int) $exists >= 1;
        } catch (\Throwable $e) {
            \Log::error('OnlineStatusService isOnline error', [
                'key' => $this->key($userId),
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function getPresenceState(Authenticatable $user): string
    {
        $userId = $user->getAuthIdentifier();

        if ($this->isOnline($userId)) {
            return 'online';
        }

        $lastSeen = $user->last_seen_at instanceof Carbon
            ? $user->last_seen_at
            : ($user->last_seen_at ? Carbon::parse($user->last_seen_at) : null);

        if (!$lastSeen) {
            return 'offline';
        }

        $seconds = $lastSeen->diffInSeconds(now());
        if ($seconds <= $this->recentWindow) return 'recent';
        if ($seconds <= $this->idleWindow)   return 'idle';
        return 'offline';
    }

    public function touchLastSeen(Authenticatable $user): void
    {
        $lockKey = "last_seen_lock:user:{$user->getAuthIdentifier()}";

        if (Cache::add($lockKey, true, $this->dbUpdateInterval)) {
            DB::table($user->getTable())
                ->where('id', $user->getAuthIdentifier())
                ->update(['last_seen_at' => Carbon::now()]);
        }
    }

    public function labelFor(string $state): string
    {
        return (string) data_get(config('online.ui.labels'), $state, ucfirst($state));
    }

    public function classFor(string $state): string
    {
        return (string) data_get(config('online.ui.classes'), $state, '');
    }

    public function iconFor(string $state): string
    {
        return (string) data_get(config('online.ui.icons'), $state, '');
    }
}
