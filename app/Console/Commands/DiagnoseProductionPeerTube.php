<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiagnoseProductionPeerTube extends Command
{
    protected $signature = 'peertube:diagnose-production';
    protected $description = 'Diagnostica problemi PeerTube in produzione';

    public function handle()
    {
        $this->info('🔍 DIAGNOSI PEERTUBE PRODUZIONE');
        $this->info('================================');
        $this->newLine();

        // 1. Verifica configurazione
        $this->info('1. VERIFICA CONFIGURAZIONE');
        $this->info('--------------------------');
        
        $configs = PeerTubeConfig::getAllAsArray();
        $this->line("URL: " . ($configs['peertube_url'] ?? 'NON CONFIGURATO'));
        $this->line("Username: " . ($configs['peertube_admin_username'] ?? 'NON CONFIGURATO'));
        $this->line("Password: " . (isset($configs['peertube_admin_password']) ? 'CONFIGURATA' : 'NON CONFIGURATA'));
        $this->line("Channel ID: " . ($configs['peertube_channel_id'] ?? 'NON CONFIGURATO'));
        $this->line("Account ID: " . ($configs['peertube_account_id'] ?? 'NON CONFIGURATO'));

        $this->newLine();

        // 2. Test connessione base
        $this->info('2. TEST CONNESSIONE BASE');
        $this->info('------------------------');
        
        $baseUrl = $configs['peertube_url'] ?? '';
        if (empty($baseUrl)) {
            $this->error('❌ URL PeerTube non configurato!');
            return 1;
        }

        try {
            $response = Http::timeout(10)->get("{$baseUrl}/api/v1/config");
            if ($response->successful()) {
                $this->line("✅ Connessione HTTP: OK");
                $this->line("   Status: " . $response->status());
                $this->line("   Server: " . $response->header('Server', 'N/A'));
            } else {
                $this->line("❌ Connessione HTTP: FALLITA");
                $this->line("   Status: " . $response->status());
                $this->line("   Body: " . substr($response->body(), 0, 200));
            }
        } catch (\Exception $e) {
            $this->line("❌ Errore connessione: " . $e->getMessage());
        }

        $this->newLine();

        // 3. Test OAuth client
        $this->info('3. TEST OAUTH CLIENT');
        $this->info('--------------------');
        
        try {
            $clientResponse = Http::timeout(30)->get("{$baseUrl}/api/v1/oauth-clients/local");
            if ($clientResponse->successful()) {
                $clientData = $clientResponse->json();
                $this->line("✅ Client OAuth: OK");
                $this->line("   Client ID: " . ($clientData['client_id'] ?? 'N/A'));
            } else {
                $this->line("❌ Client OAuth: FALLITO");
                $this->line("   Status: " . $clientResponse->status());
                $this->line("   Body: " . substr($clientResponse->body(), 0, 200));
            }
        } catch (\Exception $e) {
            $this->line("❌ Errore client OAuth: " . $e->getMessage());
        }

        $this->newLine();

        // 4. Test autenticazione dettagliato
        $this->info('4. TEST AUTENTICAZIONE DETTAGLIATO');
        $this->info('----------------------------------');
        
        $username = $configs['peertube_admin_username'] ?? '';
        $password = $configs['peertube_admin_password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $this->error('❌ Credenziali non configurate!');
            return 1;
        }

        try {
            // Test endpoint token
            $tokenResponse = Http::timeout(30)
                ->asForm()
                ->post("{$baseUrl}/api/v1/users/token", [
                    'client_id' => 'hwe0nx0epvgj4w10s60vbfpjpvu1ao3v', // Client ID fisso per test
                    'client_secret' => 'hwe0nx0epvgj4w10s60vbfpjpvu1ao3v',
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password
                ]);

            if ($tokenResponse->successful()) {
                $tokenData = $tokenResponse->json();
                $this->line("✅ Token OAuth: OK");
                $this->line("   Access Token: " . substr($tokenData['access_token'] ?? '', 0, 20) . "...");
                $this->line("   Expires In: " . ($tokenData['expires_in'] ?? 'N/A') . " secondi");
            } else {
                $this->line("❌ Token OAuth: FALLITO");
                $this->line("   Status: " . $tokenResponse->status());
                $this->line("   Body: " . substr($tokenResponse->body(), 0, 300));
            }
        } catch (\Exception $e) {
            $this->line("❌ Errore token OAuth: " . $e->getMessage());
        }

        $this->newLine();

        // 5. Test PeerTubeService
        $this->info('5. TEST PEERTUBE SERVICE');
        $this->info('------------------------');
        
        $peerTubeService = new PeerTubeService();
        
        $this->line("Configurato: " . ($peerTubeService->isConfigured() ? '✅ SI' : '❌ NO'));
        $this->line("Connessione: " . ($peerTubeService->testConnection() ? '✅ OK' : '❌ FALLITA'));
        
        try {
            $authTest = $peerTubeService->testAuthentication();
            $this->line("Autenticazione: " . ($authTest ? '✅ OK' : '❌ FALLITA'));
        } catch (\Exception $e) {
            $this->line("❌ Errore autenticazione: " . $e->getMessage());
        }

        $this->newLine();

        // 6. Test pannello admin
        $this->info('6. TEST PANNELLO ADMIN');
        $this->info('----------------------');
        
        try {
            // Simula il controller admin
            $isConfigured = $peerTubeService->isConfigured();
            $connectionTest = $peerTubeService->testConnection();
            $authTest = $isConfigured ? $peerTubeService->testAuthentication() : false;
            
            $this->line("Configurazione: " . ($isConfigured ? '✅ Completa' : '❌ Incompleta'));
            $this->line("Connessione: " . ($connectionTest ? '✅ Attiva' : '❌ Fallita'));
            $this->line("Autenticazione: " . ($authTest ? '✅ OK' : '❌ Fallita'));
            
            if ($authTest) {
                $channelInfo = $peerTubeService->getChannelInfo();
                $accountInfo = $peerTubeService->getAccountInfo();
                
                $this->line("Canale: " . ($channelInfo ? '✅ Configurato' : '⚠️  Non configurato'));
                $this->line("Account: " . ($accountInfo ? '✅ Configurato' : '⚠️  Non configurato'));
            }
        } catch (\Exception $e) {
            $this->line("❌ Errore test pannello: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('🎯 Diagnosi completata!');
        
        return 0;
    }
} 