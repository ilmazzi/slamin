<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Http;

class TestOAuthAuthentication extends Command
{
    protected $signature = 'peertube:test-oauth {--url=} {--username=} {--password=} {--detailed}';
    protected $description = 'Testa autenticazione OAuth PeerTube con diversi approcci';

    public function handle()
    {
        $this->info('🔐 TEST AUTENTICAZIONE OAUTH PEERTUBE');
        $this->info('=====================================');
        $this->newLine();

        $url = $this->option('url') ?: PeerTubeConfig::getValue('peertube_url');
        $username = $this->option('username') ?: PeerTubeConfig::getValue('peertube_admin_username');
        $password = $this->option('password') ?: PeerTubeConfig::getValue('peertube_admin_password');
        
        if (!$url || !$username || !$password) {
            $this->error('❌ Configurazione incompleta!');
            return 1;
        }

        $this->line("URL: {$url}");
        $this->line("Username: {$username}");
        $this->line("Password: " . str_repeat('*', strlen($password)));
        $this->newLine();

        // 1. Ottieni client OAuth
        $this->info('1. OTTENIMENTO CLIENT OAUTH');
        $this->info('----------------------------');
        
        $clientData = $this->getOAuthClient($url);
        if (!$clientData) {
            return 1;
        }
        
        $this->newLine();

        // 2. Test diversi formati di richiesta
        $this->info('2. TEST FORMATI RICHIESTA');
        $this->info('-------------------------');
        
        $this->testRequestFormats($url, $clientData, $username, $password);
        $this->newLine();

        // 3. Test diversi endpoint
        $this->info('3. TEST ENDPOINT');
        $this->info('----------------');
        
        $this->testEndpoints($url, $clientData, $username, $password);
        $this->newLine();

        // 4. Test diversi headers
        $this->info('4. TEST HEADERS');
        $this->info('----------------');
        
        $this->testHeaders($url, $clientData, $username, $password);
        $this->newLine();

        // 5. Test timeout
        $this->info('5. TEST TIMEOUT');
        $this->info('---------------');
        
        $this->testTimeouts($url, $clientData, $username, $password);
        $this->newLine();

        $this->info('✅ Test OAuth completati!');
        return 0;
    }

    private function getOAuthClient($url)
    {
        $this->line("   Ottenendo client OAuth...");
        
        try {
            $response = Http::timeout(30)->get("{$url}/api/v1/oauth-clients/local");
            
            if (!$response->successful()) {
                $this->line("   ❌ Errore client OAuth: " . $response->status());
                if ($this->option('detailed')) {
                    $this->line("   Response: " . $response->body());
                }
                return null;
            }
            
            $clientData = $response->json();
            $this->line("   ✅ Client OAuth OK");
            $this->line("   Client ID: " . substr($clientData['client_id'], 0, 20) . '...');
            $this->line("   Client Secret: " . substr($clientData['client_secret'], 0, 20) . '...');
            
            return $clientData;
            
        } catch (\Exception $e) {
            $this->line("   ❌ Errore client OAuth: " . $e->getMessage());
            return null;
        }
    }

    private function testRequestFormats($url, $clientData, $username, $password)
    {
        $formats = [
            'form' => [
                'method' => 'asForm',
                'endpoint' => '/api/v1/users/token',
                'description' => 'Form data standard'
            ],
            'json' => [
                'method' => 'asJson',
                'endpoint' => '/api/v1/users/token',
                'description' => 'JSON data'
            ],
            'form_oauth' => [
                'method' => 'asForm',
                'endpoint' => '/api/v1/oauth/token',
                'description' => 'OAuth endpoint con form'
            ],
            'json_oauth' => [
                'method' => 'asJson',
                'endpoint' => '/api/v1/oauth/token',
                'description' => 'OAuth endpoint con JSON'
            ]
        ];

        foreach ($formats as $format => $config) {
            $this->line("   Testando: {$config['description']}");
            
            try {
                $http = Http::timeout(30);
                
                if ($config['method'] === 'asForm') {
                    $http = $http->asForm();
                } elseif ($config['method'] === 'asJson') {
                    $http = $http->asJson();
                }
                
                $response = $http->post($url . $config['endpoint'], [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password
                ]);
                
                if ($response->successful()) {
                    $tokenData = $response->json();
                    $this->line("   ✅ {$format}: SUCCESSO");
                    $this->line("      Token: " . substr($tokenData['access_token'], 0, 20) . '...');
                } else {
                    $this->line("   ❌ {$format}: FALLITO - " . $response->status());
                    if ($this->option('detailed')) {
                        $this->line("      Response: " . $response->body());
                    }
                }
                
            } catch (\Exception $e) {
                $this->line("   ❌ {$format}: ERRORE - " . $e->getMessage());
            }
        }
    }

    private function testEndpoints($url, $clientData, $username, $password)
    {
        $endpoints = [
            '/api/v1/users/token' => 'Endpoint utenti standard',
            '/api/v1/oauth/token' => 'Endpoint OAuth standard',
            '/api/v1/auth/token' => 'Endpoint auth alternativo',
            '/oauth/token' => 'Endpoint OAuth root',
            '/users/token' => 'Endpoint utenti root'
        ];

        foreach ($endpoints as $endpoint => $description) {
            $this->line("   Testando: {$description} ({$endpoint})");
            
            try {
                $response = Http::timeout(30)
                    ->asForm()
                    ->post($url . $endpoint, [
                        'client_id' => $clientData['client_id'],
                        'client_secret' => $clientData['client_secret'],
                        'grant_type' => 'password',
                        'username' => $username,
                        'password' => $password
                    ]);
                
                if ($response->successful()) {
                    $this->line("   ✅ {$endpoint}: SUCCESSO");
                } else {
                    $this->line("   ❌ {$endpoint}: " . $response->status());
                }
                
            } catch (\Exception $e) {
                $this->line("   ❌ {$endpoint}: ERRORE - " . $e->getMessage());
            }
        }
    }

    private function testHeaders($url, $clientData, $username, $password)
    {
        $headersConfigs = [
            'default' => [],
            'json_accept' => ['Accept' => 'application/json'],
            'form_accept' => ['Accept' => 'application/x-www-form-urlencoded'],
            'user_agent' => ['User-Agent' => 'Laravel-PeerTube-Client/1.0'],
            'no_cache' => ['Cache-Control' => 'no-cache'],
            'all_headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'Laravel-PeerTube-Client/1.0',
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ];

        foreach ($headersConfigs as $config => $headers) {
            $this->line("   Testando headers: {$config}");
            
            try {
                $http = Http::timeout(30)->asForm();
                
                if (!empty($headers)) {
                    $http = $http->withHeaders($headers);
                }
                
                $response = $http->post($url . '/api/v1/users/token', [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password
                ]);
                
                if ($response->successful()) {
                    $this->line("   ✅ {$config}: SUCCESSO");
                } else {
                    $this->line("   ❌ {$config}: " . $response->status());
                }
                
            } catch (\Exception $e) {
                $this->line("   ❌ {$config}: ERRORE - " . $e->getMessage());
            }
        }
    }

    private function testTimeouts($url, $clientData, $username, $password)
    {
        $timeouts = [10, 30, 60, 120];

        foreach ($timeouts as $timeout) {
            $this->line("   Testando timeout: {$timeout}s");
            
            try {
                $start = microtime(true);
                $response = Http::timeout($timeout)
                    ->asForm()
                    ->post($url . '/api/v1/users/token', [
                        'client_id' => $clientData['client_id'],
                        'client_secret' => $clientData['client_secret'],
                        'grant_type' => 'password',
                        'username' => $username,
                        'password' => $password
                    ]);
                $end = microtime(true);
                $duration = round(($end - $start) * 1000, 2);
                
                if ($response->successful()) {
                    $this->line("   ✅ {$timeout}s: SUCCESSO ({$duration}ms)");
                } else {
                    $this->line("   ❌ {$timeout}s: " . $response->status() . " ({$duration}ms)");
                }
                
            } catch (\Exception $e) {
                $this->line("   ❌ {$timeout}s: ERRORE - " . $e->getMessage());
            }
        }
    }
} 