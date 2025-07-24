<?php

namespace App\Console\Commands;

use App\Services\PeerTubeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Log;

class DiagnosePeerTubeAuth extends Command
{
    protected $signature = 'peertube:diagnose-auth {--detailed}';
    protected $description = 'Diagnostica problemi di autenticazione PeerTube';

    public function handle()
    {
        $this->info('🔍 DIAGNOSI AUTENTICAZIONE PEERTUBE');
        $this->info('====================================');
        $this->newLine();

        // 1. Verifica configurazione
        $this->info('1. VERIFICA CONFIGURAZIONE');
        $this->info('-------------------------');
        
        $baseUrl = PeerTubeConfig::getValue('peertube_url');
        $username = PeerTubeConfig::getValue('peertube_admin_username');
        $password = PeerTubeConfig::getValue('peertube_admin_password');
        
        $this->line("URL: " . ($baseUrl ?: '❌ NON CONFIGURATO'));
        $this->line("Username: " . ($username ?: '❌ NON CONFIGURATO'));
        $this->line("Password: " . ($password ? str_repeat('*', strlen($password)) : '❌ NON CONFIGURATO'));
        
        if (!$baseUrl || !$username || !$password) {
            $this->error('❌ Configurazione incompleta!');
            return 1;
        }
        
        $this->info('✅ Configurazione completa');
        $this->newLine();

        // 2. Test connessione
        $this->info('2. TEST CONNESSIONE');
        $this->info('------------------');
        
        try {
            $response = Http::timeout(10)->get("{$baseUrl}/api/v1/config");
            
            if ($response->successful()) {
                $this->info('✅ Connessione OK');
                $config = $response->json();
                $this->line("   Versione: " . ($config['version'] ?? 'N/A'));
                $this->line("   Nome: " . ($config['instance']['name'] ?? 'N/A'));
            } else {
                $this->error('❌ Connessione fallita: ' . $response->status());
                $this->line("   Response: " . $response->body());
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Errore connessione: ' . $e->getMessage());
            return 1;
        }
        
        $this->newLine();

        // 3. Test client OAuth
        $this->info('3. TEST CLIENT OAUTH');
        $this->info('-------------------');
        
        try {
            $clientResponse = Http::timeout(30)->get("{$baseUrl}/api/v1/oauth-clients/local");
            
            if (!$clientResponse->successful()) {
                $this->error('❌ Errore client OAuth: ' . $clientResponse->status());
                $this->line("   Response: " . $clientResponse->body());
                return 1;
            }
            
            $clientData = $clientResponse->json();
            $this->info('✅ Client OAuth OK');
            $this->line("   Client ID: " . substr($clientData['client_id'], 0, 20) . '...');
            $this->line("   Client Secret: " . substr($clientData['client_secret'], 0, 20) . '...');
            
            if ($this->option('detailed')) {
                $this->line("   Client completo: " . json_encode($clientData, JSON_PRETTY_PRINT));
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Errore client OAuth: ' . $e->getMessage());
            return 1;
        }
        
        $this->newLine();

        // 4. Test autenticazione dettagliato
        $this->info('4. TEST AUTENTICAZIONE DETTAGLIATO');
        $this->info('----------------------------------');
        
        try {
            // Test con diversi formati di richiesta
            $this->testAuthFormats($baseUrl, $clientData, $username, $password);
            
        } catch (\Exception $e) {
            $this->error('❌ Errore test autenticazione: ' . $e->getMessage());
            return 1;
        }
        
        $this->newLine();
        $this->info('✅ Diagnosi completata!');
        return 0;
    }
    
    private function testAuthFormats($baseUrl, $clientData, $username, $password)
    {
        $formats = [
            'form' => [
                'method' => 'asForm',
                'endpoint' => '/api/v1/users/token',
                'data' => [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password
                ]
            ],
            'json' => [
                'method' => 'asJson',
                'endpoint' => '/api/v1/users/token',
                'data' => [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password
                ]
            ],
            'oauth2' => [
                'method' => 'asForm',
                'endpoint' => '/api/v1/oauth/token',
                'data' => [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password
                ]
            ]
        ];
        
        foreach ($formats as $format => $config) {
            $this->line("Testando formato: {$format}");
            
            try {
                $http = Http::timeout(30);
                
                if ($config['method'] === 'asForm') {
                    $http = $http->asForm();
                } elseif ($config['method'] === 'asJson') {
                    $http = $http->asJson();
                }
                
                $response = $http->post($baseUrl . $config['endpoint'], $config['data']);
                
                if ($response->successful()) {
                    $tokenData = $response->json();
                    $this->info("   ✅ {$format} - Autenticazione riuscita!");
                    $this->line("      Access Token: " . substr($tokenData['access_token'], 0, 20) . '...');
                    $this->line("      Expires In: " . ($tokenData['expires_in'] ?? 'N/A') . ' secondi');
                    
                    // Test chiamata API con token
                    $this->testApiCall($baseUrl, $tokenData['access_token']);
                    return true;
                    
                } else {
                    $this->line("   ❌ {$format} - Fallito: " . $response->status());
                    if ($this->option('detailed')) {
                        $this->line("      Response: " . $response->body());
                    }
                }
                
            } catch (\Exception $e) {
                $this->line("   ❌ {$format} - Errore: " . $e->getMessage());
            }
        }
        
        $this->error('❌ Tutti i formati di autenticazione sono falliti!');
        return false;
    }
    
    private function testApiCall($baseUrl, $accessToken)
    {
        $this->line("   Testando chiamata API...");
        
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken
                ])
                ->get("{$baseUrl}/api/v1/users/me");
            
            if ($response->successful()) {
                $userData = $response->json();
                $this->info("   ✅ API call riuscita!");
                $this->line("      Username: " . ($userData['username'] ?? 'N/A'));
                $this->line("      Role: " . ($userData['role']['label'] ?? 'N/A'));
            } else {
                $this->line("   ❌ API call fallita: " . $response->status());
            }
            
        } catch (\Exception $e) {
            $this->line("   ❌ Errore API call: " . $e->getMessage());
        }
    }
} 