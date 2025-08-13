<?php

namespace App\Http\Middleware;

use App\Services\OnlineStatusService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            try {
                // scrivo in redis
                $this->online->setOnline($user->id);

                // update last_seen_at con throttling
                $this->online->touchLastSeen($user);
            } catch (\Exception $e) {
                // Log dell'errore ma non bloccare la richiesta
                Log::warning('OnlineStatusService error in UpdateUserActivity middleware: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
