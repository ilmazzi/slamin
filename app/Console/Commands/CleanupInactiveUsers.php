<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CleanupInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:cleanup-inactive {--minutes=30 : Minuti di inattività prima di considerare offline}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imposta come offline gli utenti inattivi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = $this->option('minutes');
        $cutoffTime = Carbon::now()->subMinutes($minutes);

        $this->info("Pulizia utenti inattivi da più di {$minutes} minuti...");

        // Trova utenti online che non hanno avuto attività recente
        $inactiveUsers = User::where('is_online', true)
            ->where(function($query) use ($cutoffTime) {
                $query->whereNull('last_seen_at')
                      ->orWhere('last_seen_at', '<', $cutoffTime);
            })
            ->get();

        $count = 0;
        foreach ($inactiveUsers as $user) {
            $user->setOffline();
            $count++;
            
            $this->line("Utente {$user->name} impostato come offline (ultima attività: " . 
                       ($user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Mai') . ")");
        }

        $this->info("Completato! {$count} utenti impostati come offline.");
        
        // Mostra statistiche
        $onlineUsers = User::where('is_online', true)->count();
        $offlineUsers = User::where('is_online', false)->count();
        
        $this->newLine();
        $this->info("=== STATISTICHE FINALI ===");
        $this->line("Utenti online: {$onlineUsers}");
        $this->line("Utenti offline: {$offlineUsers}");
        $this->line("Totale utenti: " . ($onlineUsers + $offlineUsers));
    }
}
