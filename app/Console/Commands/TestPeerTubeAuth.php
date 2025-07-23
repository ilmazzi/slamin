<?php

namespace App\Console\Commands;

use App\Services\PeerTubeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\PeerTubeConfig;

class TestPeerTubeAuth extends Command
{
    protected $signature = 'peertube:test-auth';
    protected $description = 'Testa l\'autenticazione PeerTube';

    public function handle()
    {
        $this->info('🔐 Test autenticazione PeerTube...');
        
        $service = new PeerTubeService();
        
        if (!$service->isConfigured()) {
            $this->error('❌ PeerTube non è configurato!');
            return 1;
        }
        
        $this->info('✅ PeerTube configurato');
        
        if (!$service->testConnection()) {
            $this->error('❌ Connessione fallita!');
            return 1;
        }
        
        $this->info('✅ Connessione OK');
        
        // Test dettagliato autenticazione
        $this->testDetailedAuth();
        
        return 0;
    }
    
    private function testDetailedAuth()
    {
        $this->info('🔍 Test dettagliato autenticazione...');
        
        $baseUrl = PeerTubeConfig::getValue('peertube_url');
        $username = PeerTubeConfig::getValue('peertube_admin_username');
        $password = PeerTubeConfig::getValue('peertube_admin_password');
        
        $this->line("URL: {$baseUrl}");
        $this->line("Username: {$username}");
        $this->line("Password: " . str_repeat('*', strlen($password)));
        
        try {
            // 1. Test client OAuth
            $this->info('1. Testando client OAuth...');
            $clientResponse = Http::timeout(30)->get("{$baseUrl}/api/v1/oauth-clients/local");
            
            if (!$clientResponse->successful()) {
                $this->error('❌ Errore client OAuth: ' . $clientResponse->status() . ' - ' . $clientResponse->body());
                return;
            }
            
            $clientData = $clientResponse->json();
            $this->info('✅ Client OAuth OK');
            $this->line("   Client ID: {$clientData['client_id']}");
            
            // 2. Login non necessario, andiamo direttamente al token
            $this->info('2. Saltando login (non necessario)...');
            
            // 3. Test token endpoint con form (endpoint corretto)
            $this->info('3. Testando token endpoint...');
            $tokenResponse = Http::timeout(30)
                ->asForm()
                ->post("{$baseUrl}/api/v1/users/token", [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password
                ]);
            
            if (!$tokenResponse->successful()) {
                $this->error('❌ Token fallito: ' . $tokenResponse->status() . ' - ' . $tokenResponse->body());
                return;
            }
            
            $tokenData = $tokenResponse->json();
            $this->info('✅ Token OK');
            $this->line("   Access Token: " . substr($tokenData['access_token'], 0, 20) . '...');
            $this->line("   Expires In: {$tokenData['expires_in']} secondi");
            
            // 4. Test chiamata API con token
            $this->info('4. Testando chiamata API...');
            $apiResponse = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $tokenData['access_token']
                ])
                ->get("{$baseUrl}/api/v1/users/me");
            
            if (!$apiResponse->successful()) {
                $this->error('❌ API fallita: ' . $apiResponse->status() . ' - ' . $apiResponse->body());
                return;
            }
            
            $userData = $apiResponse->json();
            $this->info('✅ API OK');
            $this->line("   Username: {$userData['username']}");
            $this->line("   Role: {$userData['role']['label']}");
            
            $this->info('🎉 Autenticazione completata con successo!');
            
        } catch (\Exception $e) {
            $this->error('❌ Errore durante il test: ' . $e->getMessage());
        }
    }
}
