<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Exception;

class TestPeerTubeIntegration extends Command
{
    protected $signature = 'test:peertube-integration {--user-id=} {--create-user}';
    protected $description = 'Testa l\'integrazione PeerTube nel sistema principale';

    public function handle()
    {
        $this->info('🧪 TEST INTEGRAZIONE PEERTUBE NEL SISTEMA PRINCIPALE');
        $this->info('==================================================');
        $this->newLine();

        // Test configurazione
        $this->info('1. Test configurazione PeerTube:');
        $service = new PeerTubeService();
        
        if ($service->isConfigured()) {
            $this->info('   ✅ PeerTube configurato correttamente');
            
            if ($service->testConnection()) {
                $this->info('   ✅ Connessione PeerTube OK');
            } else {
                $this->error('   ❌ Connessione PeerTube FALLITA');
                return 1;
            }
        } else {
            $this->error('   ❌ PeerTube NON configurato');
            return 1;
        }

        $this->newLine();

        // Test utente esistente o creazione
        if ($this->option('create-user')) {
            $this->info('2. Creazione nuovo utente di test:');
            $user = $this->createTestUser();
        } else {
            $userId = $this->option('user-id');
            if (!$userId) {
                $this->error('❌ Specifica --user-id o --create-user');
                return 1;
            }
            
            $user = User::find($userId);
            if (!$user) {
                $this->error('❌ Utente non trovato');
                return 1;
            }
            
            $this->info('2. Test con utente esistente:');
            $this->info('   ID: ' . $user->id);
            $this->info('   Nome: ' . $user->name);
            $this->info('   Email: ' . $user->email);
        }

        $this->newLine();

        // Test account PeerTube
        $this->info('3. Test account PeerTube:');
        if ($user->hasPeerTubeAccount()) {
            $this->info('   ✅ Utente ha già account PeerTube');
            $this->info('   Username: ' . $user->peertube_username);
            $this->info('   User ID: ' . $user->peertube_user_id);
            $this->info('   Account ID: ' . $user->peertube_account_id);
            $this->info('   Channel ID: ' . $user->peertube_channel_id);
        } else {
            $this->info('   ⚠️  Utente NON ha account PeerTube');
            $this->info('   Creazione account PeerTube...');
            
            try {
                $this->createPeerTubeAccount($user);
                $this->info('   ✅ Account PeerTube creato con successo!');
            } catch (Exception $e) {
                $this->error('   ❌ Errore creazione account: ' . $e->getMessage());
                return 1;
            }
        }

        $this->newLine();
        $this->info('✅ Test completato con successo!');
        return 0;
    }

    private function createTestUser()
    {
        $username = 'test_user_' . time();
        $email = 'test' . time() . '@example.com';
        
        $user = User::create([
            'name' => 'Test User',
            'nickname' => $username,
            'email' => $email,
            'password' => bcrypt('testpass123'),
            'status' => 'active',
        ]);

        $user->assignRole('poet');
        
        $this->info('   ✅ Utente creato:');
        $this->info('   ID: ' . $user->id);
        $this->info('   Username: ' . $username);
        $this->info('   Email: ' . $email);
        
        return $user;
    }

    private function createPeerTubeAccount(User $user)
    {
        $service = new PeerTubeService();
        
        // Genera username PeerTube
        $peertubeUsername = $user->nickname ?: strtolower(str_replace(['@', '.'], ['', '_'], $user->email));
        $peertubeUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $peertubeUsername);
        
        if (strlen($peertubeUsername) < 3) {
            $peertubeUsername = 'user_' . $user->id;
        }

        // Crea utente su PeerTube
        $peerTubeUserData = [
            'username' => $peertubeUsername,
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
    }
}
