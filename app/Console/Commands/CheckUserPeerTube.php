<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserPeerTube extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-peertube {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Controlla lo stato PeerTube di un utente o tutti gli utenti';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Utente con ID {$userId} non trovato!");
                return 1;
            }
            $this->checkUser($user);
        } else {
            $users = User::all();
            $this->info("Controllando {$users->count()} utenti...");
            foreach ($users as $user) {
                $this->checkUser($user);
            }
        }
    }

    private function checkUser(User $user)
    {
        $this->line("\n👤 UTENTE: {$user->name} (ID: {$user->id})");
        $this->line("📧 Email: {$user->email}");
        
        $hasAccount = $user->hasPeerTubeAccount();
        $this->line("🔗 Account PeerTube: " . ($hasAccount ? '✅ Sì' : '❌ No'));
        
        if ($hasAccount) {
            $this->line("   - PeerTube User ID: {$user->peertube_user_id}");
            $this->line("   - Username: {$user->peertube_username}");
            $this->line("   - Display Name: {$user->peertube_display_name}");
            $this->line("   - Channel ID: {$user->peertube_channel_id}");
            $this->line("   - Token valido: " . ($user->hasValidPeerTubeToken() ? '✅ Sì' : '❌ No'));
            $this->line("   - Può caricare video: " . ($user->canUploadMoreVideosToPeerTube() ? '✅ Sì' : '❌ No'));
        } else {
            $this->line("   - PeerTube User ID: " . ($user->peertube_user_id ?: 'NULL'));
            $this->line("   - Username: " . ($user->peertube_username ?: 'NULL'));
            $this->line("   - Display Name: " . ($user->peertube_display_name ?: 'NULL'));
            $this->line("   - Channel ID: " . ($user->peertube_channel_id ?: 'NULL'));
        }
        
        $this->line("📹 Video caricati: {$user->videos()->count()}");
    }
}
