<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestSpecificCredentials extends Command
{
    protected $signature = 'peertube:test-credentials {--username=} {--password=} {--url=}';
    protected $description = 'Testa credenziali specifiche per PeerTube';

    public function handle()
    {
        $this->info('🔐 TEST CREDENZIALI SPECIFICHE PEERTUBE');
        $this->info('======================================');
        $this->newLine();

        // Usa credenziali specifiche o quelle configurate
        $baseUrl = $this->option('url') ?: PeerTubeConfig::getValue('peertube_url');
        $username = $this->option('username') ?: PeerTubeConfig::getValue('peertube_admin_username');
        $password = $this->option('password') ?: PeerTubeConfig::getValue('peertube_admin_password');

        $this->line("URL: {$baseUrl}");
        $this->line("Username: {$username}");
        $this->line("Password: " . str_repeat('*', strlen($password)));
        $this->newLine();

        if (empty($baseUrl) || empty($username) || empty($password)) {
            $this->error('❌ Credenziali incomplete!');
            return 1;
        }

        // 1. Test connessione base
        $this->info('1. Test connessione base...');
        try {
            $response = Http::timeout(10)->get("{$baseUrl}/api/v1/config");
            if ($response->successful()) {
                $this->line("   ✅ Connessione OK");
            } else {
                $this->line("   ❌ Connessione fallita: " . $response->status());
                return 1;
            }
        } catch (\Exception $e) {
            $this->line("   ❌ Errore connessione: " . $e->getMessage());
            return 1;
        }

        // 2. Test client OAuth
        $this->info('2. Test client OAuth...');
        try {
            $clientResponse = Http::timeout(30)->get("{$baseUrl}/api/v1/oauth-clients/local");
            if ($clientResponse->successful()) {
                $clientData = $clientResponse->json();
                $clientId = $clientData['client_id'] ?? '';
                $clientSecret = $clientData['client_secret'] ?? '';
                $this->line("   ✅ Client OAuth OK");
                $this->line("   Client ID: {$clientId}");
            } else {
                $this->line("   ❌ Client OAuth fallito: " . $clientResponse->status());
                return 1;
            }
        } catch (\Exception $e) {
            $this->line("   ❌ Errore client OAuth: " . $e->getMessage());
            return 1;
        }

        // 3. Test autenticazione con diversi endpoint
        $this->info('3. Test autenticazione con diversi endpoint...');
        
        $endpoints = [
            '/api/v1/users/token' => 'Endpoint standard',
            '/oauth/token' => 'Endpoint OAuth root',
            '/users/token' => 'Endpoint utenti root'
        ];

        foreach ($endpoints as $endpoint => $description) {
            $this->line("   Testando {$description} ({$endpoint})...");
            
            try {
                $tokenResponse = Http::timeout(30)
                    ->asForm()
                    ->post("{$baseUrl}{$endpoint}", [
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'grant_type' => 'password',
                        'username' => $username,
                        'password' => $password
                    ]);

                if ($tokenResponse->successful()) {
                    $tokenData = $tokenResponse->json();
                    $this->line("   ✅ {$description}: SUCCESSO!");
                    $this->line("   Access Token: " . substr($tokenData['access_token'] ?? '', 0, 20) . "...");
                    $this->line("   Expires In: " . ($tokenData['expires_in'] ?? 'N/A') . " secondi");
                    
                    // Test chiamata API con il token
                    $this->info('4. Test chiamata API...');
                    $apiResponse = Http::timeout(30)
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $tokenData['access_token'],
                            'Accept' => 'application/json'
                        ])
                        ->get("{$baseUrl}/api/v1/users/me");
                    
                    if ($apiResponse->successful()) {
                        $userData = $apiResponse->json();
                        $this->line("   ✅ API Call: SUCCESSO!");
                        $this->line("   Username: " . ($userData['username'] ?? 'N/A'));
                        $this->line("   Role: " . ($userData['role']['label'] ?? 'N/A'));
                    } else {
                        $this->line("   ❌ API Call: FALLITO - " . $apiResponse->status());
                    }
                    
                    $this->newLine();
                    $this->info('🎉 Autenticazione riuscita!');
                    return 0;
                    
                } else {
                    $errorBody = $tokenResponse->body();
                    $this->line("   ❌ {$description}: FALLITO");
                    $this->line("   Status: " . $tokenResponse->status());
                    $this->line("   Error: " . substr($errorBody, 0, 200));
                    
                    // Analizza l'errore specifico
                    $errorData = json_decode($errorBody, true);
                    if ($errorData && isset($errorData['code'])) {
                        $this->line("   Code: " . $errorData['code']);
                        $this->line("   Detail: " . ($errorData['detail'] ?? 'N/A'));
                    }
                }
            } catch (\Exception $e) {
                $this->line("   ❌ Errore {$description}: " . $e->getMessage());
            }
            
            $this->newLine();
        }

        $this->error('❌ Tutti i tentativi di autenticazione sono falliti!');
        return 1;
    }
} 