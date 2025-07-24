<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class FixMissingPeerTubeAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:fix-missing-accounts {--user-id=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Risolve il problema degli account PeerTube mancanti per utenti con ruoli poet/organizer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 RISOLUZIONE ACCOUNT PEERTUBE MANCANTI');
        $this->info('========================================');
        $this->newLine();

        $userId = $this->option('user-id');
        $fixAll = $this->option('all');

        try {
            $peerTubeService = new PeerTubeService();

            // Verifica configurazione
            if (!$peerTubeService->isConfigured()) {
                $this->error('❌ PeerTube non è configurato!');
                return 1;
            }

            if (!$peerTubeService->testConnection()) {
                $this->error('❌ Connessione PeerTube fallita!');
                return 1;
            }

            $this->info('✅ PeerTube configurato e connesso');
            $this->newLine();

            // Trova utenti da sistemare
            if ($userId) {
                $user = User::find($userId);
                if (!$user) {
                    $this->error('❌ Utente non trovato con ID: ' . $userId);
                    return 1;
                }
                $users = collect([$user]);
            } elseif ($fixAll) {
                // Trova tutti gli utenti con ruoli poet/organizer ma senza account PeerTube
                $users = User::whereHas('roles', function($query) {
                    $query->whereIn('name', ['poet', 'organizer']);
                })->whereNull('peertube_user_id')->get();
                
                if ($users->count() === 0) {
                    $this->info('✅ Nessun utente da sistemare trovato');
                    return 0;
                }
                
                $this->info('📋 Trovati ' . $users->count() . ' utenti da sistemare:');
                $this->table(
                    ['ID', 'Nome', 'Email', 'Ruoli'],
                    $users->map(function($user) {
                        return [
                            $user->id,
                            $user->name,
                            $user->email,
                            implode(', ', $user->getRoleNames()->toArray())
                        ];
                    })
                );
                $this->newLine();
            } else {
                // Mostra utenti che necessitano di sistemazione
                $usersToFix = User::whereHas('roles', function($query) {
                    $query->whereIn('name', ['poet', 'organizer']);
                })->whereNull('peertube_user_id')->get();
                
                if ($usersToFix->count() === 0) {
                    $this->info('✅ Nessun utente da sistemare trovato');
                    return 0;
                }
                
                $this->info('📋 Utenti che necessitano di account PeerTube:');
                $this->table(
                    ['ID', 'Nome', 'Email', 'Ruoli'],
                    $usersToFix->map(function($user) {
                        return [
                            $user->id,
                            $user->name,
                            $user->email,
                            implode(', ', $user->getRoleNames()->toArray())
                        ];
                    })
                );
                
                $this->newLine();
                $this->info('💡 Usa --all per sistemare tutti gli utenti o --user-id=N per un utente specifico');
                return 0;
            }

            // Sistema gli utenti
            foreach ($users as $user) {
                $this->info('👤 Sistemazione utente: ' . $user->name . ' (' . $user->email . ')');
                
                $this->fixUserPeerTubeAccount($user, $peerTubeService);
                
                $this->newLine();
            }

            $this->info('🎉 Operazione completata!');

        } catch (\Exception $e) {
            $this->error('❌ Errore durante l\'operazione: ' . $e->getMessage());
            Log::error('Fix missing PeerTube accounts error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    private function fixUserPeerTubeAccount($user, $peerTubeService)
    {
        try {
            // Genera username PeerTube con timestamp per evitare conflitti
            $baseUsername = $user->nickname ?: strtolower(str_replace(['@', '.'], ['', '_'], $user->email));
            $baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $baseUsername);
            
            if (strlen($baseUsername) < 3) {
                $baseUsername = 'user_' . $user->id;
            }
            
            // Usa un username completamente nuovo per evitare conflitti
            $peertubeUsername = 'user_' . $user->id . '_' . time();

            // Genera nome canale
            $channelName = $user->nickname ?: $user->name;
            $channelName = preg_replace('/[^a-zA-Z0-9\-_.:]/', '', $channelName);
            
            if (strtolower($channelName) === strtolower($peertubeUsername)) {
                $channelName = $channelName . '_channel';
            }
            
            if (strlen($channelName) < 1) {
                $channelName = 'channel_' . $user->id;
            }
            
            if (strlen($channelName) > 50) {
                $channelName = substr($channelName, 0, 50);
            }

            $this->info('   Username: ' . $peertubeUsername);
            $this->info('   Channel: ' . $channelName);

            // Genera password temporanea
            $tempPassword = \Illuminate\Support\Str::random(12);
            
            // Prima prova a trovare l'utente esistente
            $this->info('   🔍 Ricerca utente esistente...');
            $accountInfo = $peerTubeService->getAccountInfoByUsername($peertubeUsername);
            
            if ($accountInfo) {
                $this->info('   ✅ Utente trovato su PeerTube!');
                
                // Aggiorna utente locale con le informazioni recuperate
                $updateData = [
                    'peertube_user_id' => $accountInfo['user']['id'],
                    'peertube_username' => $peertubeUsername,
                    'peertube_display_name' => $user->name,
                    'peertube_password' => encrypt($tempPassword),
                ];

                if (!empty($accountInfo['videoChannels'])) {
                    $updateData['peertube_channel_id'] = $accountInfo['videoChannels'][0]['id'];
                    $updateData['peertube_account_id'] = $accountInfo['id'];
                }

                $user->update($updateData);
                $this->info('   ✅ Dati salvati nel database locale');
                
            } else {
                $this->info('   📝 Creazione nuovo account...');
                
                // Crea nuovo utente su PeerTube
                $peerTubeUserData = [
                    'peertube_username' => $peertubeUsername,
                    'email' => $user->email,
                    'peertube_password' => $tempPassword,
                    'peertube_display_name' => $user->name,
                    'peertube_channel_name' => $channelName,
                ];

                try {
                    $peerTubeUser = $peerTubeService->createUser($peerTubeUserData);

                    if ($peerTubeUser['success']) {
                        $this->info('   ✅ Account PeerTube creato con successo!');
                        
                        // Aggiorna utente locale
                        $updateData = [
                            'peertube_user_id' => $peerTubeUser['peertube_user_id'],
                            'peertube_username' => $peerTubeUser['peertube_username'],
                            'peertube_display_name' => $peerTubeUser['peertube_display_name'],
                            'peertube_password' => $peerTubeUser['peertube_password'],
                        ];

                        if (!empty($peerTubeUser['peertube_channel_id'])) {
                            $updateData['peertube_channel_id'] = $peerTubeUser['peertube_channel_id'];
                        }
                        if (!empty($peerTubeUser['peertube_account_id'])) {
                            $updateData['peertube_account_id'] = $peerTubeUser['peertube_account_id'];
                        }

                        $user->update($updateData);
                        $this->info('   ✅ Dati salvati nel database locale');
                        
                    } else {
                        $this->error('   ❌ Errore nella creazione account PeerTube');
                    }
                    
                } catch (\Exception $e) {
                    $errorMessage = $e->getMessage();
                    
                    // Se l'utente esiste già (errore 409), prova a recuperare le informazioni
                    if (strpos($errorMessage, '409') !== false && strpos($errorMessage, 'already exists') !== false) {
                        $this->info('   ⚠️ Utente già esistente, tentativo di recupero...');
                        
                        // Prova a ottenere le informazioni dell'utente usando l'API admin
                        try {
                            $token = $peerTubeService->authenticate();
                            $baseUrl = $peerTubeService->getBaseUrl();
                            
                            // Cerca l'utente per email
                            $response = \Illuminate\Support\Facades\Http::timeout(30)
                                ->withHeaders([
                                    'Authorization' => 'Bearer ' . $token,
                                ])
                                ->get("{$baseUrl}/api/v1/users", [
                                    'search' => $user->email
                                ]);
                            
                            if ($response->successful()) {
                                $users = $response->json()['data'] ?? [];
                                
                                foreach ($users as $ptUser) {
                                    if ($ptUser['email'] === $user->email) {
                                        $this->info('   ✅ Utente trovato su PeerTube!');
                                        
                                        // Aggiorna utente locale
                                        $updateData = [
                                            'peertube_user_id' => $ptUser['id'],
                                            'peertube_username' => $ptUser['username'],
                                            'peertube_display_name' => $user->name,
                                            'peertube_password' => encrypt($tempPassword),
                                        ];
                                        
                                        // Prova a ottenere informazioni del canale
                                        $channelResponse = \Illuminate\Support\Facades\Http::timeout(30)
                                            ->withHeaders([
                                                'Authorization' => 'Bearer ' . $token,
                                            ])
                                            ->get("{$baseUrl}/api/v1/users/{$ptUser['id']}");
                                        
                                        if ($channelResponse->successful()) {
                                            $userInfo = $channelResponse->json();
                                            if (!empty($userInfo['videoChannels'])) {
                                                $updateData['peertube_channel_id'] = $userInfo['videoChannels'][0]['id'];
                                                $updateData['peertube_account_id'] = $userInfo['account']['id'];
                                            }
                                        }
                                        
                                        $user->update($updateData);
                                        $this->info('   ✅ Dati salvati nel database locale');
                                        break;
                                    }
                                }
                                
                                if (empty($updateData['peertube_user_id'])) {
                                    $this->error('   ❌ Utente non trovato nella ricerca');
                                }
                                
                            } else {
                                $this->error('   ❌ Errore nella ricerca utente: ' . $response->body());
                            }
                            
                        } catch (\Exception $recoveryError) {
                            $this->error('   ❌ Errore durante il recupero: ' . $recoveryError->getMessage());
                        }
                        
                    } else {
                        $this->error('   ❌ Errore durante la creazione: ' . $errorMessage);
                    }
                }
            }

            // Test del login solo se l'utente ha i dati PeerTube
            if ($user->peertube_user_id) {
                $this->info('   🔑 Test login...');
                try {
                    $tokenData = $peerTubeService->getUserToken($user);
                    $this->info('   ✅ Login riuscito! Token valido per ' . $tokenData['expires_in'] . ' secondi');
                } catch (\Exception $e) {
                    $this->error('   ❌ Login fallito: ' . $e->getMessage());
                }
            } else {
                $this->warn('   ⚠️ Account PeerTube non creato, salto test login');
            }

        } catch (\Exception $e) {
            $this->error('   ❌ Errore: ' . $e->getMessage());
        }
    }
} 