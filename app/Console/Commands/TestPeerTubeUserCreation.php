<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestPeerTubeUserCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:test-user-creation {--email=test@slamin.it} {--name=TestUser} {--username=testuser}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa la creazione di un utente PeerTube';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TEST CREAZIONE UTENTE PEERTUBE');
        $this->info('==================================');
        $this->newLine();

        $email = $this->option('email');
        $name = $this->option('name');
        $username = $this->option('username');

        try {
            $peerTubeService = new PeerTubeService();

            // Verifica configurazione
            $this->info('1️⃣ Verifica configurazione PeerTube...');
            if (!$peerTubeService->isConfigured()) {
                $this->error('❌ PeerTube non è configurato!');
                $this->info('Configura PeerTube tramite il pannello admin.');
                return 1;
            }
            $this->info('✅ PeerTube configurato');

            // Test connessione
            $this->info('2️⃣ Test connessione PeerTube...');
            if (!$peerTubeService->testConnection()) {
                $this->error('❌ Connessione PeerTube fallita!');
                return 1;
            }
            $this->info('✅ Connessione PeerTube OK');

            // Test autenticazione
            $this->info('3️⃣ Test autenticazione PeerTube...');
            if (!$peerTubeService->testAuthentication()) {
                $this->error('❌ Autenticazione PeerTube fallita!');
                return 1;
            }
            $this->info('✅ Autenticazione PeerTube OK');

            // Crea utente di test
            $this->info('4️⃣ Creazione utente PeerTube...');
            $password = 'testpassword123';
            
            $userData = [
                'peertube_username' => $username,
                'email' => $email,
                'peertube_password' => $password,
                'peertube_display_name' => $name,
                'peertube_channel_name' => $name . ' Channel',
            ];

            $result = $peerTubeService->createUser($userData);

            if ($result['success']) {
                $this->info('✅ Utente PeerTube creato con successo!');
                $this->newLine();
                
                $this->info('📋 Dettagli utente creato:');
                $this->table(
                    ['Campo', 'Valore'],
                    [
                        ['User ID', $result['peertube_user_id']],
                        ['Username', $result['peertube_username']],
                        ['Display Name', $result['peertube_display_name']],
                        ['Channel ID', $result['peertube_channel_id'] ?? 'N/A'],
                        ['Account ID', $result['peertube_account_id'] ?? 'N/A'],
                        ['Password', $password],
                    ]
                );

                // Test recupero informazioni utente
                $this->info('5️⃣ Test recupero informazioni utente...');
                $userInfo = $peerTubeService->getUserInfo($result['peertube_user_id']);
                
                if ($userInfo) {
                    $this->info('✅ Informazioni utente recuperate con successo!');
                    $this->info('📧 Email: ' . ($userInfo['email'] ?? 'N/A'));
                    $this->info('🎭 Ruolo: ' . ($userInfo['role']['label'] ?? 'N/A'));
                    $this->info('📺 Canali: ' . count($userInfo['videoChannels'] ?? []));
                } else {
                    $this->warn('⚠️ Impossibile recuperare informazioni utente');
                }

                // Crea utente locale per test completo
                $this->info('6️⃣ Creazione utente locale per test completo...');
                $localUser = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'peertube_user_id' => $result['peertube_user_id'],
                    'peertube_username' => $result['peertube_username'],
                    'peertube_display_name' => $result['peertube_display_name'],
                    'peertube_password' => $result['peertube_password'],
                    'peertube_channel_id' => $result['peertube_channel_id'],
                    'peertube_account_id' => $result['peertube_account_id'],
                ]);

                $localUser->assignRole('poet');
                $this->info('✅ Utente locale creato con ID: ' . $localUser->id);

                // Test token utente
                $this->info('7️⃣ Test ottenimento token utente...');
                try {
                    $tokenData = $peerTubeService->getUserToken($localUser);
                    $this->info('✅ Token utente ottenuto con successo!');
                    $this->info('🔑 Access Token: ' . substr($tokenData['access_token'], 0, 20) . '...');
                    $this->info('⏰ Scade in: ' . $tokenData['expires_in'] . ' secondi');
                } catch (\Exception $e) {
                    $this->warn('⚠️ Errore ottenimento token: ' . $e->getMessage());
                }

                $this->newLine();
                $this->info('🎉 Test completato con successo!');
                $this->info('L\'utente è stato creato sia su PeerTube che localmente.');
                $this->info('Puoi accedere con email: ' . $email . ' e password: ' . $password);

            } else {
                $this->error('❌ Errore nella creazione utente PeerTube');
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante il test: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 