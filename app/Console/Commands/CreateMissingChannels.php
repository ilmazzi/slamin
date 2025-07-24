<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Exception;

class CreateMissingChannels extends Command
{
    protected $signature = 'peertube:create-channels {--user-id=} {--all}';
    protected $description = 'Crea i canali PeerTube mancanti per gli utenti';

    public function handle()
    {
        $this->info('📺 CREAZIONE CANALI PEERTUBE MANCANTI');
        $this->info('====================================');
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

        // Seleziona utenti
        if ($userId = $this->option('user-id')) {
            $users = User::where('id', $userId)->get();
        } elseif ($this->option('all')) {
            $users = User::whereNotNull('peertube_user_id')
                        ->whereNull('peertube_channel_id')
                        ->get();
        } else {
            $this->error('❌ Specifica --user-id o --all');
            return 1;
        }

        if ($users->isEmpty()) {
            $this->info('✅ Nessun utente trovato senza canale');
            return 0;
        }

        $this->info('2. Utenti senza canale (' . $users->count() . ' trovati):');
        $this->newLine();

        $created = 0;
        $errors = 0;

        foreach ($users as $user) {
            $this->line("   👤 {$user->name} ({$user->email})");
            $this->line("      Username: {$user->peertube_username}");
            $this->line("      User ID: {$user->peertube_user_id}");
            $this->line("      Channel ID: " . ($user->peertube_channel_id ?: '❌ MANCANTE'));
            
            if (!$user->peertube_channel_id) {
                $this->line("      🆕 Creando canale...");
                
                try {
                    // Genera nome canale
                    $channelName = strtolower(str_replace([' ', '@', '.'], ['_', '', '_'], $user->email));
                    $channelName = preg_replace('/[^a-zA-Z0-9_]/', '', $channelName);
                    
                    if (strlen($channelName) < 3) {
                        $channelName = 'channel_' . $user->id;
                    }
                    
                    // Crea canale
                    $channelData = [
                        'name' => $channelName,
                        'display_name' => $user->name . ' Channel',
                        'description' => 'Canale di ' . $user->name,
                        'privacy' => 1, // Public
                    ];
                    
                    $channel = $service->createChannel($user, $channelData);
                    
                    // Aggiorna utente
                    $user->update([
                        'peertube_channel_id' => $channel['channel_id']
                    ]);
                    
                    $this->line("      ✅ Canale creato: {$channel['name']} (ID: {$channel['channel_id']})");
                    $created++;
                    
                } catch (Exception $e) {
                    $this->line("      ❌ Errore creazione: " . $e->getMessage());
                    $errors++;
                }
            } else {
                $this->line("      ✅ Canale già presente");
            }
            
            $this->newLine();
        }

        $this->info('3. Risultato:');
        $this->line("   ✅ Canali creati: {$created}");
        if ($errors > 0) {
            $this->line("   ❌ Errori: {$errors}");
        }
        
        $this->newLine();
        $this->info('🎯 Operazione completata!');
        
        return 0;
    }
} 