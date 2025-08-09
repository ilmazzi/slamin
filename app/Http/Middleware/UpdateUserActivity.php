<?php

namespace App\Http\Middleware;

use App\Services\OnlineStatusService;
use Closure;
use Illuminate\Http\Request;

class UpdateUserActivity
{
    protected OnlineStatusService $online;

    public function __construct(OnlineStatusService $online)
    {
        $this->online = $online;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            // scrivo in redis
            $this->online->setOnline($user->id);

            // update last_seen_at con throttling
            $this->online->touchLastSeen($user);
        }

        return $next($request);
    }
}
