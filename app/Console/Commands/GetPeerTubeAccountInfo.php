<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Exception;

class GetPeerTubeAccountInfo extends Command
{
    protected $signature = 'peertube:get-account-info {user-id}';
    protected $description = 'Ottiene le informazioni dell\'account PeerTube di un utente';

    public function handle()
    {
        $userId = $this->argument('user-id');

        $this->info('🔍 INFORMAZIONI ACCOUNT PEERTUBE');
        $this->info('================================');
        $this->newLine();

        // Trova utente
        $user = User::find($userId);
        if (!$user) {
            $this->error('❌ Utente non trovato');
            return 1;
        }

        $this->info('1. Utente:');
        $this->info('   ID: ' . $user->id);
        $this->info('   Nome: ' . $user->name);
        $this->info('   Email: ' . $user->email);

        if (!$user->hasPeerTubeAccount()) {
            $this->error('   ❌ Utente NON ha account PeerTube');
            return 1;
        }

        $this->info('   ✅ Utente ha account PeerTube');
        $this->info('   Username: ' . $user->peertube_username);
        $this->info('   User ID: ' . $user->peertube_user_id);
        $this->info('   Account ID: ' . $user->peertube_account_id);
        $this->info('   Channel ID: ' . ($user->peertube_channel_id ?: 'N/A'));

        $this->newLine();

        try {
            $service = new PeerTubeService();

            // Ottieni informazioni utente (inclusi canali)
            $this->info('2. Recupero informazioni utente:');
            $userInfo = $service->getUserInfo($user->peertube_user_id);

            if (!$userInfo) {
                $this->error('   ❌ Impossibile ottenere informazioni utente');
                return 1;
            }

            $this->info('   ✅ Informazioni utente ottenute');
            $this->info('   User ID: ' . ($userInfo['id'] ?? 'N/A'));
            $this->info('   Username: ' . ($userInfo['username'] ?? 'N/A'));
            $this->info('   Email: ' . ($userInfo['email'] ?? 'N/A'));
            $this->info('   Role: ' . ($userInfo['role']['label'] ?? 'N/A'));

            // Informazioni account
            if (isset($userInfo['account'])) {
                $account = $userInfo['account'];
                $this->info('   Account ID: ' . ($account['id'] ?? 'N/A'));
                $this->info('   Display Name: ' . ($account['displayName'] ?? 'N/A'));
                $this->info('   Description: ' . ($account['description'] ?? 'N/A'));
                $this->info('   Followers Count: ' . ($account['followersCount'] ?? 'N/A'));
                $this->info('   Following Count: ' . ($account['followingCount'] ?? 'N/A'));
            }

            // Verifica canali
            if (isset($userInfo['videoChannels']) && is_array($userInfo['videoChannels'])) {
                $this->info('   Channels: ' . count($userInfo['videoChannels']));
                
                foreach ($userInfo['videoChannels'] as $index => $channel) {
                    $this->info('   Channel ' . ($index + 1) . ':');
                    $this->info('     ID: ' . ($channel['id'] ?? 'N/A'));
                    $this->info('     Name: ' . ($channel['name'] ?? 'N/A'));
                    $this->info('     Display Name: ' . ($channel['displayName'] ?? 'N/A'));
                    $this->info('     Description: ' . ($channel['description'] ?? 'N/A'));
                    
                    // Aggiorna channel ID se mancante
                    if (!$user->peertube_channel_id && isset($channel['id'])) {
                        $user->update(['peertube_channel_id' => $channel['id']]);
                        $this->info('     ✅ Channel ID aggiornato nel database');
                    }
                }
            } else {
                $this->warn('   ⚠️  Nessun canale trovato');
            }

            $this->newLine();
            $this->info('✅ Informazioni recuperate con successo!');
            return 0;

        } catch (Exception $e) {
            $this->error('   ❌ Errore durante il recupero: ' . $e->getMessage());
            return 1;
        }
    }
} 