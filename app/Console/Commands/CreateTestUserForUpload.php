<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTestUserForUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:create-test-user {--email=test.upload@slamin.it} {--name=Test Upload User} {--username=testupload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un utente di test con credenziali valide per testare l\'upload';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('👤 CREAZIONE UTENTE TEST PER UPLOAD');
        $this->info('==================================');
        $this->newLine();

        $email = $this->option('email');
        $name = $this->option('name');
        $username = $this->option('username');

        try {
            $peerTubeService = new PeerTubeService();

            // Verifica configurazione
            if (!$peerTubeService->isConfigured()) {
                $this->error('❌ PeerTube non è configurato!');
                return 1;
            }

            // Verifica che l'utente non esista già
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                $this->warn('⚠️ Utente con email ' . $email . ' esiste già');
                $this->info('   ID: ' . $existingUser->id);
                $this->info('   Nome: ' . $existingUser->name);
                
                if ($existingUser->peertube_user_id) {
                    $this->info('   ✅ Ha già account PeerTube');
                    $this->info('   PeerTube ID: ' . $existingUser->peertube_user_id);
                    $this->info('   PeerTube Username: ' . $existingUser->peertube_username);
                    
                    // Test del login
                    $this->info('🔑 Test login...');
                    try {
                        $tokenData = $peerTubeService->getUserToken($existingUser);
                        $this->info('✅ Login riuscito! Token valido per ' . $tokenData['expires_in'] . ' secondi');
                        $this->info('🎉 Utente pronto per l\'upload!');
                        return 0;
                    } catch (\Exception $e) {
                        $this->error('❌ Login fallito: ' . $e->getMessage());
                        $this->info('💡 Usa php artisan peertube:fix-passwords --user-id=' . $existingUser->id . ' --reset-password');
                        return 1;
                    }
                } else {
                    $this->info('   ❌ Non ha account PeerTube');
                }
            }

            // Genera password sicura
            $password = Str::random(12);
            
            $this->info('📝 Creazione utente PeerTube...');
            $this->info('   Email: ' . $email);
            $this->info('   Nome: ' . $name);
            $this->info('   Username: ' . $username);
            $this->info('   Password: ' . $password);

            // Crea utente su PeerTube
            $userData = [
                'peertube_username' => $username,
                'email' => $email,
                'peertube_password' => $password,
                'peertube_display_name' => $name,
            ];

            $result = $peerTubeService->createUser($userData);

            if ($result['success']) {
                $this->info('✅ Utente PeerTube creato con successo!');
                $this->info('   PeerTube User ID: ' . $result['peertube_user_id']);
                $this->info('   PeerTube Username: ' . $result['peertube_username']);
                $this->info('   Channel ID: ' . ($result['peertube_channel_id'] ?? 'N/A'));

                // Crea utente locale
                $this->info('📝 Creazione utente locale...');
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

                // Test del login
                $this->info('🔑 Test login...');
                try {
                    $tokenData = $peerTubeService->getUserToken($localUser);
                    $this->info('✅ Login riuscito! Token valido per ' . $tokenData['expires_in'] . ' secondi');
                } catch (\Exception $e) {
                    $this->error('❌ Login fallito: ' . $e->getMessage());
                    return 1;
                }

                $this->newLine();
                $this->info('🎉 Utente di test creato con successo!');
                $this->info('📋 Credenziali:');
                $this->table(
                    ['Campo', 'Valore'],
                    [
                        ['ID Locale', $localUser->id],
                        ['Email', $email],
                        ['Password', $password],
                        ['PeerTube ID', $result['peertube_user_id']],
                        ['PeerTube Username', $result['peertube_username']],
                    ]
                );

                $this->info('💡 Per testare l\'upload:');
                $this->info('   php artisan peertube:test-upload-no-transcoding --user-id=' . $localUser->id);

            } else {
                $this->error('❌ Errore nella creazione utente PeerTube');
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante la creazione: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 