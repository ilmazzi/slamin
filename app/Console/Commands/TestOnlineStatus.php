<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestOnlineStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:online-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test online status of users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== STATO ONLINE UTENTI ===');
        
        $users = User::all();
        
        foreach ($users as $user) {
            $status = $user->online_status;
            $isOnline = $user->is_online ? 'SI' : 'NO';
            $lastSeen = $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Mai';
            $currentlyOnline = $user->isCurrentlyOnline() ? 'ONLINE ORA' : 'OFFLINE';
            
            $this->line(sprintf(
                "%-20s | %-10s | Online: %-3s | Ultima attività: %-15s | Stato attuale: %s",
                $user->name,
                $status,
                $isOnline,
                $lastSeen,
                $currentlyOnline
            ));
        }
        
        $this->newLine();
        $this->info('=== STATISTICHE ===');
        $this->line('Utenti online: ' . User::where('is_online', true)->count());
        $this->line('Utenti offline: ' . User::where('is_online', false)->count());
        $this->line('Utenti attualmente online: ' . User::all()->filter(fn($u) => $u->isCurrentlyOnline())->count());
        
        $this->newLine();
        $this->info('=== TEST API ===');
        $this->line('Per testare l\'API, vai su: /online-status/user/1/status');
        $this->line('(sostituisci "1" con l\'ID di un utente)');
    }
}
