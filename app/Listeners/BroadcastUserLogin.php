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
        Log::info('🔍 Listener BroadcastUserLogin chiamato', [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'is_admin' => $event->user->hasRole('admin')
        ]);

        // Invia evento broadcast solo se l'utente NON è admin (quindi è un "utente normale")
        if (!$event->user->hasRole('admin')) {
            Log::info('📡 Invio evento broadcast per utente normale');
            broadcast(new UserLoggedIn(
                $event->user->name,
                $event->user->email
            ))->toOthers();
            Log::info('✅ Evento broadcast inviato con successo');
        } else {
            Log::info('🚫 Utente admin, evento broadcast non inviato');
        }

        Log::info('🎯 Evento UserLoggedIn processato', [
            'name' => $event->user->name,
            'email' => $event->user->email
        ]);
    }
}
