<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestUserList extends Command
{
    protected $signature = 'test:user-list {username?}';
    protected $description = 'Lista gli utenti e verifica la configurazione PeerTube';

    public function handle()
    {
        $username = $this->argument('username');

        $this->info('👥 LISTA UTENTI E CONFIGURAZIONE PEERTUBE');
        $this->info('==========================================');
        $this->newLine();

        $query = User::query();
        
        if ($username) {
            $query->where('name', 'like', "%{$username}%")
                  ->orWhere('email', 'like', "%{$username}%")
                  ->orWhere('peertube_username', 'like', "%{$username}%");
            $this->info("Filtrando per: {$username}");
        }

        $users = $query->latest()->get();

        if ($users->isEmpty()) {
            $this->info('Nessun utente trovato');
            return 0;
        }

        $this->info("Trovati {$users->count()} utenti:");
        $this->newLine();

        foreach ($users as $user) {
            $this->info("ID: {$user->id}");
            $this->info("  Nome: {$user->name}");
            $this->info("  Email: {$user->email}");
            $this->info("  PeerTube Username: " . ($user->peertube_username ?: 'NON CONFIGURATO'));
            $this->info("  PeerTube User ID: " . ($user->peertube_user_id ?: 'N/A'));
            $this->info("  PeerTube Channel ID: " . ($user->peertube_channel_id ?: 'N/A'));
            $this->info("  Token PeerTube: " . ($user->peertube_token ? 'PRESENTE' : 'ASSENTE'));
            $this->info("  Password PeerTube: " . ($user->peertube_password ? 'CRIPTATA' : 'ASSENTE'));
            $this->info("  Account PeerTube: " . ($user->hasPeerTubeAccount() ? '✅ ATTIVO' : '❌ NON ATTIVO'));
            $this->newLine();
        }

        return 0;
    }
} 