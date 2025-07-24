<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Exception;

class CheckUserPeerTube extends Command
{
    protected $signature = 'users:check-peertube {--user-id=} {--fix} {--create-missing}';
    protected $description = 'Verifica e aggiorna gli account PeerTube degli utenti';

    public function handle()
    {
        $this->info('🔍 VERIFICA ACCOUNT PEERTUBE UTENTI');
        $this->info('==================================');
        $this->newLine();

        // Test configurazione PeerTube
        $this->info('1. Test configurazione PeerTube:');
        $service = new PeerTubeService();
        
        if (!$service->isConfigured()) {
            $this->error('   ❌ PeerTube NON configurato');
            return 1;
        }
        
        $this->info('   ✅ PeerTube configurato');
        
        if (!$service->testConnection()) {
            $this->error('   ❌ Connessione PeerTube FALLITA');
            return 1;
        }
        
        $this->info('   ✅ Connessione PeerTube OK');
        $this->newLine();

        // Seleziona utenti da verificare
        if ($userId = $this->option('user-id')) {
            $users = User::where('id', $userId)->get();
        } else {
            // Tutti gli utenti con ruoli che necessitano PeerTube
            $users = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['poet', 'organizer']);
            })->get();
        }

        $this->info('2. Verifica utenti (' . $users->count() . ' trovati):');
        $this->newLine();

        $issues = [];
        $fixed = 0;

        foreach ($users as $user) {
            $this->line("   👤 {$user->name} ({$user->email})");
            
            $hasAccount = $user->hasPeerTubeAccount();
            $this->line("      Account PeerTube: " . ($hasAccount ? '✅ ATTIVO' : '❌ NON ATTIVO'));
            
            if ($hasAccount) {
                $this->line("      Username: {$user->peertube_username}");
                $this->line("      User ID: {$user->peertube_user_id}");
                $this->line("      Channel ID: " . ($user->peertube_channel_id ?: '❌ MANCANTE'));
            }

            // Verifica se l'utente esiste su PeerTube
            if ($hasAccount) {
                try {
                    $peerTubeUser = $service->getAccountInfoByUsername($user->peertube_username);
                    if ($peerTubeUser) {
                        $this->line("      ✅ Utente trovato su PeerTube");
                        
                        // Verifica se i dati sono sincronizzati
                        if ($peerTubeUser['id'] != $user->peertube_user_id) {
                            $this->line("      ⚠️  User ID non sincronizzato (DB: {$user->peertube_user_id}, PeerTube: {$peerTubeUser['id']})");
                            $issues[] = [
                                'user' => $user,
                                'type' => 'user_id_mismatch',
                                'data' => $peerTubeUser
                            ];
                        }
                    } else {
                        $this->line("      ❌ Utente NON trovato su PeerTube");
                        $issues[] = [
                            'user' => $user,
                            'type' => 'not_found_on_peertube'
                        ];
                    }
                } catch (Exception $e) {
                    $this->line("      ❌ Errore verifica: " . $e->getMessage());
                    $issues[] = [
                        'user' => $user,
                        'type' => 'verification_error',
                        'error' => $e->getMessage()
                    ];
                }
            } else {
                $this->line("      ❌ Nessun account PeerTube configurato");
                $issues[] = [
                    'user' => $user,
                    'type' => 'no_account'
                ];
            }
            
            $this->newLine();
        }

        // Mostra problemi trovati
        if (!empty($issues)) {
            $this->warn('3. Problemi trovati (' . count($issues) . '):');
            foreach ($issues as $issue) {
                $user = $issue['user'];
                switch ($issue['type']) {
                    case 'no_account':
                        $this->line("   ❌ {$user->name}: Nessun account PeerTube");
                        break;
                    case 'not_found_on_peertube':
                        $this->line("   ❌ {$user->name}: Account non trovato su PeerTube");
                        break;
                    case 'user_id_mismatch':
                        $this->line("   ⚠️  {$user->name}: User ID non sincronizzato");
                        break;
                    case 'verification_error':
                        $this->line("   ❌ {$user->name}: Errore verifica - {$issue['error']}");
                        break;
                }
            }
            $this->newLine();
        } else {
            $this->info('3. ✅ Nessun problema trovato!');
            $this->newLine();
        }

        // Opzione fix
        if ($this->option('fix') && !empty($issues)) {
            $this->info('4. 🔧 Riparazione problemi:');
            
            foreach ($issues as $issue) {
                $user = $issue['user'];
                $this->line("   🔧 Riparando {$user->name}...");
                
                try {
                    switch ($issue['type']) {
                        case 'user_id_mismatch':
                            // Aggiorna User ID
                            $user->update([
                                'peertube_user_id' => $issue['data']['id']
                            ]);
                            $this->line("      ✅ User ID aggiornato");
                            $fixed++;
                            break;
                            
                        case 'not_found_on_peertube':
                            // Rimuovi dati PeerTube corrotti
                            $user->update([
                                'peertube_user_id' => null,
                                'peertube_username' => null,
                                'peertube_display_name' => null,
                                'peertube_account_id' => null,
                                'peertube_channel_id' => null,
                                'peertube_token' => null,
                                'peertube_refresh_token' => null,
                                'peertube_token_expires_at' => null,
                            ]);
                            $this->line("      ✅ Dati PeerTube rimossi");
                            $fixed++;
                            break;
                    }
                } catch (Exception $e) {
                    $this->error("      ❌ Errore riparazione: " . $e->getMessage());
                }
            }
        }

        // Opzione create-missing
        if ($this->option('create-missing')) {
            $this->info('5. 🆕 Creazione account mancanti:');
            
            $usersWithoutAccount = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['poet', 'organizer']);
            })->whereNull('peertube_user_id')->get();
            
            foreach ($usersWithoutAccount as $user) {
                $this->line("   🆕 Creando account per {$user->name}...");
                
                try {
                    // Genera username
                    $peertubeUsername = $user->nickname ?: strtolower(str_replace(['@', '.'], ['', '_'], $user->email));
                    $peertubeUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $peertubeUsername);
                    
                    if (strlen($peertubeUsername) < 3) {
                        $peertubeUsername = 'user_' . $user->id;
                    }
                    
                    // Crea utente su PeerTube
                    $peerTubeUserData = [
                        'peertube_username' => $peertubeUsername,
                        'email' => $user->email,
                        'peertube_password' => 'password', // Password di default
                        'peertube_display_name' => $user->name,
                    ];
                    
                    $peerTubeUser = $service->createUser($peerTubeUserData);
                    
                    // Aggiorna utente locale
                    $user->update([
                        'peertube_user_id' => $peerTubeUser['peertube_user_id'],
                        'peertube_username' => $peerTubeUser['peertube_username'],
                        'peertube_display_name' => $peerTubeUser['peertube_display_name'],
                        'peertube_password' => $peerTubeUser['peertube_password'],
                    ]);
                    
                    $this->line("      ✅ Account creato: {$peerTubeUser['peertube_username']}");
                    $fixed++;
                    
                } catch (Exception $e) {
                    $this->error("      ❌ Errore creazione: " . $e->getMessage());
                }
            }
        }

        $this->newLine();
        $this->info('🎯 Verifica completata!');
        
        if ($fixed > 0) {
            $this->info("✅ {$fixed} problemi risolti");
        }
        
        return 0;
    }
}
