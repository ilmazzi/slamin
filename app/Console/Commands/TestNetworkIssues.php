<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Http;

class TestNetworkIssues extends Command
{
    protected $signature = 'peertube:test-network {--url=}';
    protected $description = 'Testa problemi di rete e SSL per PeerTube';

    public function handle()
    {
        $this->info('🌐 TEST PROBLEMI DI RETE PEERTUBE');
        $this->info('==================================');
        $this->newLine();

        $url = $this->option('url') ?: PeerTubeConfig::getValue('peertube_url');
        
        if (!$url) {
            $this->error('❌ URL PeerTube non configurato!');
            return 1;
        }

        $this->line("URL da testare: {$url}");
        $this->newLine();

        // 1. Test DNS
        $this->info('1. TEST DNS');
        $this->info('-----------');
        $this->testDNS($url);
        $this->newLine();

        // 2. Test connessione base
        $this->info('2. TEST CONNESSIONE BASE');
        $this->info('-------------------------');
        $this->testBasicConnection($url);
        $this->newLine();

        // 3. Test SSL/TLS
        $this->info('3. TEST SSL/TLS');
        $this->info('---------------');
        $this->testSSL($url);
        $this->newLine();

        // 4. Test timeout
        $this->info('4. TEST TIMEOUT');
        $this->info('---------------');
        $this->testTimeouts($url);
        $this->newLine();

        // 5. Test User-Agent
        $this->info('5. TEST USER-AGENT');
        $this->info('-------------------');
        $this->testUserAgent($url);
        $this->newLine();

        // 6. Test Headers
        $this->info('6. TEST HEADERS');
        $this->info('----------------');
        $this->testHeaders($url);
        $this->newLine();

        $this->info('✅ Test di rete completati!');
        return 0;
    }

    private function testDNS($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        
        $this->line("   Testando risoluzione DNS per: {$host}");
        
        $ip = gethostbyname($host);
        
        if ($ip === $host) {
            $this->line("   ❌ DNS: Fallito - Impossibile risolvere {$host}");
        } else {
            $this->line("   ✅ DNS: OK - {$host} risolve a {$ip}");
        }
    }

    private function testBasicConnection($url)
    {
        $this->line("   Testando connessione HTTP...");
        
        try {
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $this->line("   ✅ Connessione HTTP: OK");
                $this->line("   Status: " . $response->status());
                $this->line("   Content-Type: " . $response->header('Content-Type'));
            } else {
                $this->line("   ⚠️  Connessione HTTP: Status " . $response->status());
            }
        } catch (\Exception $e) {
            $this->line("   ❌ Connessione HTTP: Fallita - " . $e->getMessage());
        }
    }

    private function testSSL($url)
    {
        $this->line("   Testando certificato SSL...");
        
        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT) ?: 443;
        
        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);
        
        $client = @stream_socket_client(
            "ssl://{$host}:{$port}",
            $errno,
            $errstr,
            10,
            STREAM_CLIENT_CONNECT,
            $context
        );
        
        if ($client) {
            $this->line("   ✅ Connessione SSL: OK");
            
            $params = stream_context_get_params($client);
            if (isset($params['options']['ssl']['peer_certificate'])) {
                $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
                $this->line("   Certificato: " . ($cert['subject']['CN'] ?? 'N/A'));
                $this->line("   Valido fino: " . date('Y-m-d H:i:s', $cert['validTo_time_t']));
            }
            
            fclose($client);
        } else {
            $this->line("   ❌ Connessione SSL: Fallita - {$errstr} ({$errno})");
        }
    }

    private function testTimeouts($url)
    {
        $timeouts = [5, 10, 30, 60];
        
        foreach ($timeouts as $timeout) {
            $this->line("   Testando timeout {$timeout}s...");
            
            try {
                $start = microtime(true);
                $response = Http::timeout($timeout)->get($url);
                $end = microtime(true);
                $duration = round(($end - $start) * 1000, 2);
                
                if ($response->successful()) {
                    $this->line("   ✅ Timeout {$timeout}s: OK ({$duration}ms)");
                } else {
                    $this->line("   ⚠️  Timeout {$timeout}s: Status " . $response->status() . " ({$duration}ms)");
                }
            } catch (\Exception $e) {
                $this->line("   ❌ Timeout {$timeout}s: Fallito - " . $e->getMessage());
            }
        }
    }

    private function testUserAgent($url)
    {
        $userAgents = [
            'Laravel HTTP Client' => null,
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'curl/7.68.0' => 'curl/7.68.0',
        ];
        
        foreach ($userAgents as $name => $userAgent) {
            $this->line("   Testando User-Agent: {$name}");
            
            try {
                $http = Http::timeout(10);
                if ($userAgent) {
                    $http = $http->withHeaders(['User-Agent' => $userAgent]);
                }
                
                $response = $http->get($url);
                
                if ($response->successful()) {
                    $this->line("   ✅ {$name}: OK");
                } else {
                    $this->line("   ⚠️  {$name}: Status " . $response->status());
                }
            } catch (\Exception $e) {
                $this->line("   ❌ {$name}: Fallito - " . $e->getMessage());
            }
        }
    }

    private function testHeaders($url)
    {
        $this->line("   Testando headers di richiesta...");
        
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Accept-Language' => 'it-IT,it;q=0.9,en;q=0.8',
                    'Cache-Control' => 'no-cache',
                ])
                ->get($url);
            
            if ($response->successful()) {
                $this->line("   ✅ Headers personalizzati: OK");
                $this->line("   Content-Length: " . $response->header('Content-Length'));
                $this->line("   Server: " . $response->header('Server'));
            } else {
                $this->line("   ⚠️  Headers personalizzati: Status " . $response->status());
            }
        } catch (\Exception $e) {
            $this->line("   ❌ Headers personalizzati: Fallito - " . $e->getMessage());
        }
    }
} 