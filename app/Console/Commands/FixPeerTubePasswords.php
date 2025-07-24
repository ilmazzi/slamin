<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;
use Exception;

class FixPeerTubePasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:fix-passwords {--user-id=} {--test-login} {--reset-password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostica e risolve problemi con le password PeerTube';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 DIAGNOSI PASSWORD PEERTUBE');
        $this->info('============================');
        $this->newLine();

        $userId = $this->option('user-id');
        $testLogin = $this->option('test-login');
        $resetPassword = $this->option('reset-password');

        try {
            $peerTubeService = new PeerTubeService();

            // Verifica configurazione
            if (!$peerTubeService->isConfigured()) {
                $this->error('❌ PeerTube non è configurato!');
                return 1;
            }

            // Se è specificato un utente, lavora solo con quello
            if ($userId) {
                $user = User::find($userId);
                if (!$user) {
                    $this->error('❌ Utente non trovato con ID: ' . $userId);
                    return 1;
                }
                $users = collect([$user]);
            } else {
                $users = User::whereNotNull('peertube_user_id')->get();
            }

            if ($users->count() === 0) {
                $this->warn('⚠️ Nessun utente con account PeerTube trovato');
                return 0;
            }

            $this->info('📋 Analisi ' . $users->count() . ' utente/i...');
            $this->newLine();

            foreach ($users as $user) {
                $this->info('👤 Utente: ' . $user->name . ' (' . $user->email . ')');
                $this->info('   PeerTube ID: ' . $user->peertube_user_id);
                $this->info('   PeerTube Username: ' . $user->peertube_username);

                // Analizza la password
                $password = $user->peertube_password;
                $this->info('   Password nel DB: ' . substr($password, 0, 20) . '...');

                // Verifica se è criptata
                if (str_starts_with($password, 'eyJpdiI6')) {
                    $this->info('   ✅ Password criptata (Laravel encrypt)');
                    
                    try {
                        $decryptedPassword = decrypt($password);
                        $this->info('   🔓 Password decriptata: ' . substr($decryptedPassword, 0, 10) . '...');
                        
                                                 if ($testLogin) {
                             $this->info('   🔑 Test login...');
                             try {
                                 $tokenData = $this->testUserLogin($peerTubeService, $user->peertube_username, $decryptedPassword);
                                 $this->info('   ✅ Login riuscito! Token valido per ' . $tokenData['expires_in'] . ' secondi');
                             } catch (\Exception $e) {
                                 $this->error('   ❌ Login fallito: ' . $e->getMessage());
                                 
                                 if ($resetPassword) {
                                     $this->info('   🔄 Reset password...');
                                     $this->resetUserPassword($peerTubeService, $user);
                                 }
                             }
                         } elseif ($resetPassword) {
                             // Se non stiamo testando ma stiamo resettando, resetta direttamente
                             $this->info('   🔄 Reset password...');
                             $this->resetUserPassword($peerTubeService, $user);
                         }
                        
                    } catch (\Exception $e) {
                        $this->error('   ❌ Errore decriptazione: ' . $e->getMessage());
                        
                        if ($resetPassword) {
                            $this->info('   🔄 Reset password...');
                            $this->resetUserPassword($peerTubeService, $user);
                        }
                    }
                } else {
                    $this->warn('   ⚠️ Password non criptata o formato non riconosciuto');
                    
                    if ($testLogin) {
                        $this->info('   🔑 Test login...');
                        try {
                            $tokenData = $this->testUserLogin($peerTubeService, $user->peertube_username, $password);
                            $this->info('   ✅ Login riuscito! Token valido per ' . $tokenData['expires_in'] . ' secondi');
                        } catch (\Exception $e) {
                            $this->error('   ❌ Login fallito: ' . $e->getMessage());
                            
                            if ($resetPassword) {
                                $this->info('   🔄 Reset password...');
                                $this->resetUserPassword($peerTubeService, $user);
                            }
                        }
                    }
                }
                
                $this->newLine();
            }

            if (!$testLogin && !$resetPassword) {
                $this->info('💡 Suggerimenti:');
                $this->info('   - Usa --test-login per testare il login di tutti gli utenti');
                $this->info('   - Usa --reset-password per resettare le password non funzionanti');
                $this->info('   - Usa --user-id=N per lavorare con un utente specifico');
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante la diagnosi: ' . $e->getMessage());
            Log::error('Fix PeerTube passwords error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    private function testUserLogin($peerTubeService, $username, $password): array
    {
        $baseUrl = $peerTubeService->getBaseUrl();
        
        // 1. Ottieni client OAuth
        $clientResponse = \Illuminate\Support\Facades\Http::timeout(30)
            ->get("{$baseUrl}/api/v1/oauth-clients/local");

        if (!$clientResponse->successful()) {
            throw new Exception('Impossibile ottenere client OAuth');
        }

        $clientData = $clientResponse->json();

        // 2. Test login
        $loginResponse = \Illuminate\Support\Facades\Http::timeout(30)
            ->asForm()
            ->post("{$baseUrl}/api/v1/users/token", [
                'client_id' => $clientData['client_id'],
                'client_secret' => $clientData['client_secret'],
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password,
            ]);

        if (!$loginResponse->successful()) {
            throw new Exception('Login fallito: ' . $loginResponse->body());
        }

        return $loginResponse->json();
    }

    private function resetUserPassword($peerTubeService, $user): void
    {
        try {
            // Genera una nuova password
            $newPassword = \Illuminate\Support\Str::random(12);
            
            // Resetta la password su PeerTube
            $token = $peerTubeService->authenticate();
            $baseUrl = $peerTubeService->getBaseUrl();
            
            $response = \Illuminate\Support\Facades\Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->put("{$baseUrl}/api/v1/users/{$user->peertube_user_id}", [
                    'password' => $newPassword
                ]);

            if ($response->successful()) {
                // Aggiorna la password nel database locale (criptata)
                $user->update([
                    'peertube_password' => encrypt($newPassword)
                ]);
                
                $this->info('   ✅ Password resettata con successo!');
                $this->info('   🔑 Nuova password: ' . $newPassword);
                
                // Test del nuovo login
                $this->info('   🔑 Test nuovo login...');
                $tokenData = $this->testUserLogin($peerTubeService, $user->peertube_username, $newPassword);
                $this->info('   ✅ Nuovo login riuscito!');
                
            } else {
                $this->error('   ❌ Errore reset password: ' . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Errore durante il reset: ' . $e->getMessage());
        }
    }
} 