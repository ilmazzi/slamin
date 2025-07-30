<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\Dashboard\DashboardController;

class TestAllUserStats extends Command
{
    protected $signature = 'test:all-user-stats';
    protected $description = 'Testa le statistiche di tutti gli utenti';

    public function handle()
    {
        $this->info("=== TEST STATISTICHE TUTTI GLI UTENTI ===");

        $users = User::all();

        foreach ($users as $user) {
            $this->info("\n--- UTENTE: {$user->name} (ID: {$user->id}) ---");

            // Usa il metodo del DashboardController
            $controller = new DashboardController();
            $reflection = new \ReflectionClass($controller);
            $method = $reflection->getMethod('getUserStats');
            $method->setAccessible(true);

            $stats = $method->invoke($controller, $user);

            $this->info("Eventi Passati: {$stats['past_events']}");
            $this->info("Eventi Futuri: {$stats['future_events']}");
            $this->info("Eventi Organizzati: {$stats['organized_events']}");
            $this->info("Inviti in Attesa: {$stats['pending_invitations']}");

            // Se ci sono statistiche non zero, mostra i dettagli
            if ($stats['past_events'] > 0 || $stats['future_events'] > 0 || $stats['organized_events'] > 0) {
                $this->info("  DETTAGLI:");
                $this->info("  - Eventi organizzati: " . $user->organizedEvents()->count());
                $this->info("  - Eventi organizzati passati: " . $user->organizedEvents()->where('start_datetime', '<', now())->count());
                $this->info("  - Eventi organizzati futuri: " . $user->organizedEvents()->where('start_datetime', '>=', now())->count());
                $this->info("  - Inviti accettati: " . $user->receivedInvitations()->where('status', 'accepted')->count());
                $this->info("  - Richieste accettate: " . $user->eventRequests()->where('status', 'accepted')->count());
            }
        }
    }
}
