<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SystemSetting;

class TestPeerTubeConnection extends Command
{
    protected $signature = 'peertube:test-connection';
    protected $description = 'Test PeerTube connection and OAuth2 authentication';

    public function handle()
    {
        $this->info('Testing PeerTube connection...');

        // Carica configurazioni
        $baseUrl = SystemSetting::where('key', 'peertube_url')->value('value');
        $username = SystemSetting::where('key', 'peertube_admin_username')->value('value');
        $password = SystemSetting::where('key', 'peertube_admin_password')->value('value');

        if (!$baseUrl || !$username || !$password) {
            $this->error('Configurazioni PeerTube mancanti!');
            return 1;
        }

        $this->info("URL: $baseUrl");
        $this->info("Username: $username");
        $this->info("Password: " . str_repeat('*', strlen($password)));

        // Step 1: Test connessione base
        $this->info("\n1. Testing base connection...");
        try {
            $response = Http::timeout(10)->get($baseUrl . '/api/v1/oauth-clients/local');
            $this->info("Status: " . $response->status());

            if ($response->successful()) {
                $this->info("✅ Base connection successful");
                $clientData = $response->json();
                $this->info("Client ID: " . $clientData['client_id']);
                $this->info("Client Secret: " . substr($clientData['client_secret'], 0, 10) . "...");
            } else {
                $this->error("❌ Base connection failed: " . $response->body());
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Connection error: " . $e->getMessage());
            return 1;
        }

        // Step 2: Test OAuth2 authentication
        $this->info("\n2. Testing OAuth2 authentication...");
        try {
            $clientData = $response->json();
            $clientId = $clientData['client_id'];
            $clientSecret = $clientData['client_secret'];

            // Prova prima con username
            $formData = [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password,
            ];

            $this->info("Trying with username: $username");
            $tokenResponse = Http::asForm()->timeout(10)->post($baseUrl . '/api/v1/users/token', $formData);

            if (!$tokenResponse->successful()) {
                $this->warn("Failed with username, trying with email...");

                // Prova con email (se username contiene @)
                if (strpos($username, '@') !== false) {
                    $formData['username'] = $username; // già un'email
                } else {
                    // Prova a indovinare l'email
                    $possibleEmails = [
                        $username . '@slamin.it',
                        $username . '@gmail.com',
                        $username . '@example.com'
                    ];

                    foreach ($possibleEmails as $email) {
                        $this->info("Trying with email: $email");
                        $formData['username'] = $email;
                        $tokenResponse = Http::asForm()->timeout(10)->post($baseUrl . '/api/v1/users/token', $formData);

                        if ($tokenResponse->successful()) {
                            break;
                        }
                    }
                }
            }

            $this->info("Sending OAuth2 request...");

            $this->info("Status: " . $tokenResponse->status());
            $this->info("Response: " . $tokenResponse->body());

            if ($tokenResponse->successful()) {
                $tokenData = $tokenResponse->json();
                $this->info("✅ OAuth2 authentication successful");
                $this->info("Access Token: " . substr($tokenData['access_token'], 0, 20) . "...");
                $this->info("Token Type: " . $tokenData['token_type']);
                $this->info("Expires In: " . $tokenData['expires_in'] . " seconds");
            } else {
                $this->error("❌ OAuth2 authentication failed");
                $this->error("Response: " . $tokenResponse->body());
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ OAuth2 error: " . $e->getMessage());
            return 1;
        }

        $this->info("\n✅ All tests passed! PeerTube connection is working.");
        return 0;
    }

    public function testConfig()
    {
        $this->info('Testing PeerTube configuration...');

        $baseUrl = SystemSetting::where('key', 'peertube_url')->value('value');
        if (!$baseUrl) {
            $this->error('URL PeerTube non configurata!');
            return 1;
        }

        try {
            $response = Http::timeout(10)->get($baseUrl . '/api/v1/config');
            $this->info("Status: " . $response->status());

            if ($response->successful()) {
                $config = $response->json();
                $this->info("✅ Configurazione ottenuta");
                $this->info("Signup enabled: " . ($config['signup']['enabled'] ?? 'unknown'));
                $this->info("OAuth2 enabled: " . ($config['oauth2']['enabled'] ?? 'unknown'));
                $this->info("Server version: " . ($config['serverVersion'] ?? 'unknown'));
            } else {
                $this->error("❌ Errore ottenimento configurazione: " . $response->body());
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Errore connessione: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
