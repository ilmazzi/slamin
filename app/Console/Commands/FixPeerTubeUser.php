<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Exception;

class FixPeerTubeUser extends Command
{
    protected $signature = 'peertube:fix-user {user-id}';
    protected $description = 'Aggiorna token e canale di un utente PeerTube esistente';

    public function handle()
    {
        $userId = $this->argument('user-id');

        $this->info('🔧 AGGIORNAMENTO TOKEN E CANALE PEERTUBE');
        $this->info('==========================================');
        $this->newLine();

        // Trova utente
        $user = User::find($userId);
        if (!$user) {
            $this->error('❌ Utente non trovato');
            return 1;
        }

        if (!$user->peertube_username) {
            $this->error('❌ Utente non ha un account PeerTube');
            return 1;
        }

        $this->info('1. Utente trovato:');
        $this->info('   ID: ' . $user->id);
        $this->info('   Nome: ' . $user->name);
        $this->info('   Username PeerTube: ' . $user->peertube_username);
        $this->info('   User ID PeerTube: ' . ($user->peertube_user_id ?: 'N/A'));
        $this->info('   Channel ID PeerTube: ' . ($user->peertube_channel_id ?: 'N/A'));
        $this->info('   Token PeerTube: ' . ($user->peertube_token ? 'PRESENTE' : 'ASSENTE'));

        $this->newLine();

        try {
            $service = new PeerTubeService();

            // 1. Ottieni token per l'utente
            $this->info('2. Ottenimento token PeerTube...');
            $tokenData = $service->getUserToken($user);
            
            $user->update([
                'peertube_token' => $tokenData['access_token'],
                'peertube_refresh_token' => $tokenData['refresh_token'],
                'peertube_token_expires_at' => now()->addSeconds($tokenData['expires_in']),
            ]);

            $this->info('   ✅ Token ottenuto e salvato');

            // 2. Ottieni informazioni account e canale
            $this->info('3. Ottenimento informazioni account...');
            $accountInfo = $service->getUserInfo($user->peertube_user_id);
            
            if (isset($accountInfo['videoChannels']) && !empty($accountInfo['videoChannels'])) {
                $channel = $accountInfo['videoChannels'][0];
                $user->update([
                    'peertube_channel_id' => $channel['id'],
                ]);
                $this->info('   ✅ Channel ID aggiornato: ' . $channel['id']);
            } else {
                // Crea un canale se non esiste
                $this->info('4. Creazione canale video...');
                $channelData = $service->createChannel($user);
                $user->update([
                    'peertube_channel_id' => $channelData['id'],
                ]);
                $this->info('   ✅ Canale creato: ' . $channelData['id']);
            }

            $this->newLine();
            $this->info('✅ Aggiornamento completato!');
            $this->info('🎉 L\'utente può ora caricare video su PeerTube!');
            return 0;

        } catch (Exception $e) {
            $this->error('   ❌ Errore: ' . $e->getMessage());
            return 1;
        }
    }
} 