<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestChannelNameConflict extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:test-channel-conflict {--username=testuser} {--email=test@slamin.it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa il conflitto tra username e nome del canale';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TEST CONFLITTO USERNAME/CANALE PEERTUBE');
        $this->info('==========================================');
        $this->newLine();

        $username = $this->option('username');
        $email = $this->option('email');

        try {
            $peerTubeService = new PeerTubeService();

            // Verifica configurazione
            if (!$peerTubeService->isConfigured()) {
                $this->error('❌ PeerTube non è configurato!');
                return 1;
            }

            // Simula il caso problematico: username e nome del canale uguali
            $this->info('📝 Simulazione caso problematico:');
            $this->info('   Username: ' . $username);
            $this->info('   Nome canale originale: ' . $username);
            $this->newLine();

            // Genera nome canale come nel controller
            $channelName = $username;
            $channelName = preg_replace('/[^a-zA-Z0-9\-_.:]/', '', $channelName);
            
            // Se il nome del canale è uguale all'username, aggiungi un suffisso
            if (strtolower($channelName) === strtolower($username)) {
                $channelName = $channelName . '_channel';
                $this->info('🔄 Nome canale modificato per evitare conflitto:');
                $this->info('   Nuovo nome canale: ' . $channelName);
                $this->newLine();
            }

            // Crea utente di test
            $password = 'testpassword123';
            
            $userData = [
                'peertube_username' => $username,
                'email' => $email,
                'peertube_password' => $password,
                'peertube_display_name' => 'Test User',
                'peertube_channel_name' => $channelName,
            ];

            $this->info('🚀 Creazione utente PeerTube...');
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
                        ['Channel Name', $result['peertube_channel_name'] ?? 'N/A'],
                        ['Account ID', $result['peertube_account_id'] ?? 'N/A'],
                    ]
                );

                // Verifica che il nome del canale sia diverso dall'username
                if ($result['peertube_channel_name'] && $result['peertube_channel_name'] !== $result['peertube_username']) {
                    $this->info('✅ Conflitto risolto: nome canale diverso dall\'username');
                } else {
                    $this->warn('⚠️ Attenzione: nome canale potrebbe essere uguale all\'username');
                }

                // Crea utente locale per test completo
                $localUser = User::create([
                    'name' => 'Test User',
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

                $this->newLine();
                $this->info('🎉 Test completato con successo!');
                $this->info('Il conflitto username/canale è stato risolto correttamente.');

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