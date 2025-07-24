<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Exception;

class UpdatePeerTubeUser extends Command
{
    protected $signature = 'peertube:update-user {user-id} {--new-username=}';
    protected $description = 'Aggiorna l\'account PeerTube di un utente';

    public function handle()
    {
        $userId = $this->argument('user-id');
        $newUsername = $this->option('new-username');

        $this->info('🔄 AGGIORNAMENTO ACCOUNT PEERTUBE UTENTE');
        $this->info('========================================');
        $this->newLine();

        // Trova utente
        $user = User::find($userId);
        if (!$user) {
            $this->error('❌ Utente non trovato');
            return 1;
        }

        $this->info('1. Utente trovato:');
        $this->info('   ID: ' . $user->id);
        $this->info('   Nome: ' . $user->name);
        $this->info('   Email: ' . $user->email);
        $this->info('   Username PeerTube attuale: ' . ($user->peertube_username ?: 'Nessuno'));

        $this->newLine();

        // Genera nuovo username se non specificato
        if (!$newUsername) {
            $newUsername = 'user_' . $user->id . '_' . time();
        }

        $this->info('2. Creazione nuovo account PeerTube:');
        $this->info('   Nuovo username: ' . $newUsername);

        try {
            $service = new PeerTubeService();

            // Crea nuovo utente su PeerTube
            $peerTubeUserData = [
                'username' => $newUsername,
                'email' => $user->email,
                'password' => 'testpass123', // Password di test
                'display_name' => $user->name,
            ];

            $peerTubeUser = $service->createUser($peerTubeUserData);

            // Aggiorna utente locale
            $user->update([
                'peertube_user_id' => $peerTubeUser['user_id'],
                'peertube_username' => $peerTubeUser['username'],
                'peertube_display_name' => $peerTubeUser['display_name'],
                'peertube_account_id' => $peerTubeUser['account_id'],
                'peertube_channel_id' => $peerTubeUser['channel_id'],
                'peertube_password' => 'testpass123',
            ]);

            $this->info('   ✅ Account PeerTube creato con successo!');
            $this->info('   User ID: ' . $peerTubeUser['user_id']);
            $this->info('   Username: ' . $peerTubeUser['username']);
            $this->info('   Account ID: ' . $peerTubeUser['account_id']);
            $this->info('   Channel ID: ' . $peerTubeUser['channel_id']);

            $this->newLine();
            $this->info('✅ Aggiornamento completato!');
            return 0;

        } catch (Exception $e) {
            $this->error('   ❌ Errore durante la creazione: ' . $e->getMessage());
            return 1;
        }
    }
} 