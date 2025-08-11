<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\PeerTubeConfig;

class TestAdminPanel extends Command
{
    protected $signature = 'peertube:test-admin-panel';
    protected $description = 'Testa il pannello admin PeerTube';

    public function handle()
    {
        $this->info('🔧 TEST PANNELLO ADMIN PEERTUBE');
        $this->info('================================');
        $this->newLine();

        $peerTubeService = new PeerTubeService();

        // 1. Test configurazione
        $this->info('1. Test configurazione:');
        $isConfigured = $peerTubeService->isConfigured();
        $this->line("   Configurato: " . ($isConfigured ? '✅ SI' : '❌ NO'));
        
        if (!$isConfigured) {
            $this->error('❌ PeerTube non configurato!');
            return 1;
        }

        // Mostra configurazioni
        $configs = PeerTubeConfig::getAllAsArray();
        $this->line("   URL: " . ($configs['peertube_url'] ?? 'N/A'));
        $this->line("   Username: " . ($configs['peertube_admin_username'] ?? 'N/A'));
        $this->line("   Password: " . (isset($configs['peertube_admin_password']) ? '***' : 'N/A'));

        $this->newLine();

        // 2. Test connessione
        $this->info('2. Test connessione:');
        $connectionTest = $peerTubeService->testConnection();
        $this->line("   Connessione: " . ($connectionTest ? '✅ OK' : '❌ FALLITA'));

        if (!$connectionTest) {
            $this->error('❌ Connessione PeerTube fallita!');
            return 1;
        }

        $this->newLine();

        // 3. Test autenticazione (come nel pannello admin)
        $this->info('3. Test autenticazione (pannello admin):');
        try {
            $authTest = $peerTubeService->testAuthentication();
            $this->line("   Autenticazione: " . ($authTest ? '✅ OK' : '❌ FALLITA'));
            
            if (!$authTest) {
                $this->error('❌ Autenticazione fallita nel pannello admin!');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Errore autenticazione: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();

        // 4. Test informazioni canale e account
        $this->info('4. Test informazioni aggiuntive:');
        
        $channelInfo = $peerTubeService->getChannelInfo();
        $this->line("   Info Canale: " . ($channelInfo ? '✅ OK' : '⚠️  Non configurato'));
        
        $accountInfo = $peerTubeService->getAccountInfo();
        $this->line("   Info Account: " . ($accountInfo ? '✅ OK' : '⚠️  Non configurato'));

        $this->newLine();
        $this->info('🎉 Test pannello admin completato con successo!');
        return 0;
    }
} 
