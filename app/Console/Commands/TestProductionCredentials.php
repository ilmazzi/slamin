<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestProductionCredentials extends Command
{
    protected $signature = 'peertube:test-production {--prod-url=} {--username=} {--password=}';
    protected $description = 'Testa le credenziali PeerTube in produzione';

    public function handle()
    {
        $this->info('🧪 TEST CREDENZIALI PRODUZIONE PEERTUBE');
        $this->info('========================================');
        $this->newLine();

        // 1. Configurazioni locali
        $this->info('1. CONFIGURAZIONI LOCALI');
        $this->info('-------------------------');
        
        $localUrl = PeerTubeConfig::getValue('peertube_url');
        $localUsername = PeerTubeConfig::getValue('peertube_admin_username');
        $localPassword = PeerTubeConfig::getValue('peertube_admin_password');
        
        $this->line("URL Locale: {$localUrl}");
        $this->line("Username Locale: {$localUsername}");
        $this->line("Password Locale: " . str_repeat('*', strlen($localPassword)));
        
        $this->newLine();

        // 2. Test locale
        $this->info('2. TEST LOCALE');
        $this->info('--------------');
        
        $localTest = $this->testAuthentication($localUrl, $localUsername, $localPassword);
        if ($localTest) {
            $this->info('✅ Test locale: SUCCESSO');
        } else {
            $this->error('❌ Test locale: FALLITO');
            return 1;
        }
        
        $this->newLine();

        // 3. Test produzione
        $this->info('3. TEST PRODUZIONE');
        $this->info('-------------------');
        
        $prodUrl = $this->option('prod-url') ?: $localUrl;
        $prodUsername = $this->option('username') ?: $localUsername;
        $prodPassword = $this->option('password') ?: $localPassword;
        
        $this->line("URL Produzione: {$prodUrl}");
        $this->line("Username Produzione: {$prodUsername}");
        $this->line("Password Produzione: " . str_repeat('*', strlen($prodPassword)));
        
        $this->newLine();
        
        $prodTest = $this->testAuthentication($prodUrl, $prodUsername, $prodPassword);
        if ($prodTest) {
            $this->info('✅ Test produzione: SUCCESSO');
        } else {
            $this->error('❌ Test produzione: FALLITO');
        }
        
        $this->newLine();

        // 4. Confronto ambienti
        $this->info('4. CONFRONTO AMBIENTI');
        $this->info('---------------------');
        
        $this->compareEnvironments($localUrl, $prodUrl, $localUsername, $prodUsername, $localPassword, $prodPassword, $localTest, $prodTest);
        
        $this->newLine();
        $this->info('✅ Test completato!');
        return 0;
    }
    
    private function testAuthentication($url, $username, $password)
    {
        try {
            // 1. Test connessione
            $this->line("   Testando connessione a {$url}...");
            $configResponse = Http::timeout(10)->get("{$url}/api/v1/config");
            
            if (!$configResponse->successful()) {
                $this->line("   ❌ Connessione fallita: " . $configResponse->status());
                return false;
            }
            
            $this->line("   ✅ Connessione OK");
            
            // 2. Ottieni client OAuth
            $this->line("   Ottenendo client OAuth...");
            $clientResponse = Http::timeout(30)->get("{$url}/api/v1/oauth-clients/local");
            
            if (!$clientResponse->successful()) {
                $this->line("   ❌ Errore client OAuth: " . $clientResponse->status());
                return false;
            }
            
            $clientData = $clientResponse->json();
            $this->line("   ✅ Client OAuth OK");
            
            // 3. Test autenticazione
            $this->line("   Testando autenticazione...");
            $tokenResponse = Http::timeout(30)
                ->asForm()
                ->post("{$url}/api/v1/users/token", [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password
                ]);
            
            if (!$tokenResponse->successful()) {
                $this->line("   ❌ Autenticazione fallita: " . $tokenResponse->status());
                $this->line("   Response: " . $tokenResponse->body());
                return false;
            }
            
            $tokenData = $tokenResponse->json();
            $this->line("   ✅ Autenticazione OK");
            $this->line("   Token: " . substr($tokenData['access_token'], 0, 20) . '...');
            
            // 4. Test API call
            $this->line("   Testando API call...");
            $apiResponse = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $tokenData['access_token']
                ])
                ->get("{$url}/api/v1/users/me");
            
            if ($apiResponse->successful()) {
                $userData = $apiResponse->json();
                $this->line("   ✅ API call OK");
                $this->line("   Username: " . ($userData['username'] ?? 'N/A'));
                $this->line("   Role: " . ($userData['role']['label'] ?? 'N/A'));
                return true;
            } else {
                $this->line("   ❌ API call fallita: " . $apiResponse->status());
                return false;
            }
            
        } catch (\Exception $e) {
            $this->line("   ❌ Errore: " . $e->getMessage());
            return false;
        }
    }
    
    private function compareEnvironments($localUrl, $prodUrl, $localUsername, $prodUsername, $localPassword, $prodPassword, $localTest, $prodTest)
    {
        $this->line("🔍 ANALISI DIFFERENZE:");
        $this->newLine();
        
        // Confronto URL
        if ($localUrl === $prodUrl) {
            $this->info("✅ URL identici");
        } else {
            $this->warn("⚠️  URL diversi:");
            $this->line("   Locale: {$localUrl}");
            $this->line("   Produzione: {$prodUrl}");
        }
        
        // Confronto username
        if ($localUsername === $prodUsername) {
            $this->info("✅ Username identici");
        } else {
            $this->warn("⚠️  Username diversi:");
            $this->line("   Locale: {$localUsername}");
            $this->line("   Produzione: {$prodUsername}");
        }
        
        // Confronto password
        if ($localPassword === $prodPassword) {
            $this->info("✅ Password identiche");
        } else {
            $this->warn("⚠️  Password diverse (lunghezza: " . strlen($localPassword) . " vs " . strlen($prodPassword) . ")");
        }
        
        $this->newLine();
        
        // Analisi risultati
        if ($localTest && !$prodTest) {
            $this->error("❌ PROBLEMA IDENTIFICATO:");
            $this->line("   - Locale: FUNZIONA");
            $this->line("   - Produzione: FALLISCE");
            $this->newLine();
            $this->line("🔧 POSSIBILI CAUSE:");
            $this->line("   1. Problemi di rete/firewall in produzione");
            $this->line("   2. Configurazione SSL/TLS diversa");
            $this->line("   3. Restrizioni IP in produzione");
            $this->line("   4. Proxy o load balancer che interferisce");
            $this->line("   5. Timeout diversi tra ambienti");
            $this->line("   6. Configurazione PHP diversa");
            $this->newLine();
            $this->line("💡 SUGGERIMENTI:");
            $this->line("   - Verifica firewall e restrizioni di rete");
            $this->line("   - Controlla configurazione SSL in produzione");
            $this->line("   - Verifica se ci sono proxy o load balancer");
            $this->line("   - Controlla i log del server web in produzione");
            $this->line("   - Prova a fare curl manuale dal server produzione");
        } elseif (!$localTest && $prodTest) {
            $this->error("❌ PROBLEMA IDENTIFICATO:");
            $this->line("   - Locale: FALLISCE");
            $this->line("   - Produzione: FUNZIONA");
            $this->newLine();
            $this->line("🔧 POSSIBILI CAUSE:");
            $this->line("   1. Configurazione locale errata");
            $this->line("   2. Problemi di rete locale");
            $this->line("   3. Configurazione PHP locale diversa");
        } elseif (!$localTest && !$prodTest) {
            $this->error("❌ PROBLEMA IDENTIFICATO:");
            $this->line("   - Locale: FALLISCE");
            $this->line("   - Produzione: FALLISCE");
            $this->newLine();
            $this->line("🔧 POSSIBILI CAUSE:");
            $this->line("   1. Credenziali errate in entrambi gli ambienti");
            $this->line("   2. Problemi con il server PeerTube");
            $this->line("   3. Configurazione OAuth errata");
        } else {
            $this->info("✅ Entrambi gli ambienti funzionano correttamente");
        }
    }
} 