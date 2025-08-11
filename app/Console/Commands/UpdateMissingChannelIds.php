<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Exception;

class UpdateMissingChannelIds extends Command
{
    protected $signature = 'peertube:update-channels {--user-id=} {--all}';
    protected $description = 'Recupera e aggiorna i channel ID mancanti per gli utenti';

    public function handle()
    {
        $this->info('🔄 AGGIORNAMENTO CHANNEL ID MANCANTI');
        $this->info('====================================');
        $this->newLine();

        // Test configurazione PeerTube
        $service = new PeerTubeService();
        
        if (!$service->isConfigured()) {
            $this->error('❌ PeerTube NON configurato');
            return 1;
        }
        
        $this->info('✅ PeerTube configurato e connesso');
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
            $this->info('✅ Nessun utente trovato senza channel ID');
            return 0;
        }

        $this->info('2. Utenti senza channel ID (' . $users->count() . ' trovati):');
        $this->newLine();

        $updated = 0;
        $errors = 0;

        foreach ($users as $user) {
            $this->line("   👤 {$user->name} ({$user->email})");
            $this->line("      Username: {$user->peertube_username}");
            $this->line("      User ID: {$user->peertube_user_id}");
            
            try {
                // Recupera informazioni utente da PeerTube
                $userInfo = $service->getUserInfo($user->peertube_user_id);
                
                if ($userInfo && isset($userInfo['videoChannels']) && !empty($userInfo['videoChannels'])) {
                    // Prendi il primo canale dell'utente
                    $channel = $userInfo['videoChannels'][0];
                    $user->update(['peertube_channel_id' => $channel['id']]);
                    $this->line("      ✅ Channel ID aggiornato: {$channel['id']} ({$channel['name']})");
                    $updated++;
                } else {
                    $this->line("      ❌ Nessun canale trovato per l'utente");
                    $errors++;
                }
                
            } catch (Exception $e) {
                $this->line("      ❌ Errore: " . $e->getMessage());
                $errors++;
            }
            
            $this->newLine();
        }

        $this->info('3. Risultato:');
        $this->line("   ✅ Channel ID aggiornati: {$updated}");
        if ($errors > 0) {
            $this->line("   ❌ Errori: {$errors}");
        }
        
        $this->newLine();
        $this->info('🎯 Operazione completata!');
        
        return 0;
    }
} 
