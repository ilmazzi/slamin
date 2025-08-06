<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Events\UserLoggedIn;
use Illuminate\Support\Facades\Log;

class BroadcastUserLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        
        // Invia evento broadcast solo se l'utente NON è admin (quindi è un "utente normale")
        if (!$event->user->hasRole('admin')) {
            broadcast(new UserLoggedIn(
                $event->user->name,
                $event->user->email
            ))->toOthers();
        }
        Log::info('🎯 Evento UserLoggedIn inviato via broadcast', [
            'name' => $event->user->name,
            'email' => $event->user->email
        ]);
    }
}
