<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ComparePeerTubeEnvironments extends Command
{
    protected $signature = 'peertube:compare-environments {--local-url=} {--prod-url=}';
    protected $description = 'Confronta configurazioni PeerTube tra ambiente locale e produzione';

    public function handle()
    {
        $this->info('🔍 CONFRONTO AMBIENTI PEERTUBE');
        $this->info('==============================');
        $this->newLine();

        // Configurazioni locali
        $this->info('1. CONFIGURAZIONI LOCALI');
        $this->info('-------------------------');
        
        $localConfig = $this->getLocalConfig();
        $this->displayConfig($localConfig, 'LOCALE');
        
        $this->newLine();

        // Test connessione locale
        $this->info('2. TEST CONNESSIONE LOCALE');
        $this->info('---------------------------');
        
        $localConnection = $this->testConnection($localConfig['url']);
        if ($localConnection) {
            $this->info('✅ Connessione locale OK');
        } else {
            $this->error('❌ Connessione locale FALLITA');
        }
        
        $this->newLine();

        // Test autenticazione locale
        $this->info('3. TEST AUTENTICAZIONE LOCALE');
        $this->info('--------------------------------');
        
        $localAuth = $this->testAuthentication($localConfig);
        if ($localAuth) {
            $this->info('✅ Autenticazione locale OK');
        } else {
            $this->error('❌ Autenticazione locale FALLITA');
        }
        
        $this->newLine();

        // Configurazioni produzione (se fornite)
        if ($this->option('prod-url')) {
            $this->info('4. CONFIGURAZIONI PRODUZIONE');
            $this->info('-----------------------------');
            
            $prodConfig = $this->getProductionConfig();
            $this->displayConfig($prodConfig, 'PRODUZIONE');
            
            $this->newLine();

            // Test connessione produzione
            $this->info('5. TEST CONNESSIONE PRODUZIONE');
            $this->info('--------------------------------');
            
            $prodConnection = $this->testConnection($prodConfig['url']);
            if ($prodConnection) {
                $this->info('✅ Connessione produzione OK');
            } else {
                $this->error('❌ Connessione produzione FALLITA');
            }
            
            $this->newLine();

            // Test autenticazione produzione
            $this->info('6. TEST AUTENTICAZIONE PRODUZIONE');
            $this->info('-----------------------------------');
            
            $prodAuth = $this->testAuthentication($prodConfig);
            if ($prodAuth) {
                $this->info('✅ Autenticazione produzione OK');
            } else {
                $this->error('❌ Autenticazione produzione FALLITA');
            }
            
            $this->newLine();

            // Confronto
            $this->info('7. CONFRONTO AMBIENTI');
            $this->info('---------------------');
            
            $this->compareEnvironments($localConfig, $prodConfig, $localAuth, $prodAuth);
        }
        
        $this->newLine();
        $this->info('✅ Confronto completato!');
        return 0;
    }
    
    private function getLocalConfig()
    {
        return [
            'url' => PeerTubeConfig::getValue('peertube_url'),
            'username' => PeerTubeConfig::getValue('peertube_admin_username'),
            'password' => PeerTubeConfig::getValue('peertube_admin_password'),
        ];
    }
    
    private function getProductionConfig()
    {
        $prodUrl = $this->option('prod-url');
        
        // Per il test, usa le stesse credenziali ma URL diverso
        return [
            'url' => $prodUrl,
            'username' => PeerTubeConfig::getValue('peertube_admin_username'),
            'password' => PeerTubeConfig::getValue('peertube_admin_password'),
        ];
    }
    
    private function displayConfig($config, $env)
    {
        $this->line("URL ({$env}): " . ($config['url'] ?: '❌ NON CONFIGURATO'));
        $this->line("Username ({$env}): " . ($config['username'] ?: '❌ NON CONFIGURATO'));
        $this->line("Password ({$env}): " . ($config['password'] ? str_repeat('*', strlen($config['password'])) : '❌ NON CONFIGURATO'));
    }
    
    private function testConnection($url)
    {
        if (!$url) {
            return false;
        }
        
        try {
            $response = Http::timeout(10)->get("{$url}/api/v1/config");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function testAuthentication($config)
    {
        if (!$config['url'] || !$config['username'] || !$config['password']) {
            return false;
        }
        
        try {
            // 1. Ottieni client OAuth
            $clientResponse = Http::timeout(30)->get("{$config['url']}/api/v1/oauth-clients/local");
            
            if (!$clientResponse->successful()) {
                return false;
            }
            
            $clientData = $clientResponse->json();
            
            // 2. Test autenticazione
            $tokenResponse = Http::timeout(30)
                ->asForm()
                ->post("{$config['url']}/api/v1/users/token", [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $config['username'],
                    'password' => $config['password']
                ]);
            
            return $tokenResponse->successful();
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function compareEnvironments($localConfig, $prodConfig, $localAuth, $prodAuth)
    {
        $this->line("🔍 ANALISI DIFFERENZE:");
        $this->newLine();
        
        // Confronto URL
        if ($localConfig['url'] === $prodConfig['url']) {
            $this->line("❌ URL identici - Questo potrebbe essere il problema!");
            $this->line("   Se stai testando in produzione, l'URL dovrebbe essere diverso.");
        } else {
            $this->info("✅ URL diversi - Corretto");
        }
        
        // Confronto username
        if ($localConfig['username'] === $prodConfig['username']) {
            $this->info("✅ Username identici");
        } else {
            $this->line("⚠️  Username diversi - Potrebbe causare problemi");
        }
        
        // Confronto password
        if ($localConfig['password'] === $prodConfig['password']) {
            $this->info("✅ Password identiche");
        } else {
            $this->line("⚠️  Password diverse - Potrebbe causare problemi");
        }
        
        $this->newLine();
        
        // Confronto autenticazione
        if ($localAuth && !$prodAuth) {
            $this->error("❌ PROBLEMA IDENTIFICATO:");
            $this->line("   - Autenticazione locale: FUNZIONA");
            $this->line("   - Autenticazione produzione: FALLITA");
            $this->newLine();
            $this->line("🔧 POSSIBILI CAUSE:");
            $this->line("   1. Credenziali diverse in produzione");
            $this->line("   2. Configurazione OAuth diversa in produzione");
            $this->line("   3. Firewall o restrizioni di rete");
            $this->line("   4. Versione PeerTube diversa");
            $this->line("   5. Configurazione SSL/TLS diversa");
        } elseif (!$localAuth && $prodAuth) {
            $this->error("❌ PROBLEMA IDENTIFICATO:");
            $this->line("   - Autenticazione locale: FALLITA");
            $this->line("   - Autenticazione produzione: FUNZIONA");
            $this->newLine();
            $this->line("🔧 POSSIBILI CAUSE:");
            $this->line("   1. Configurazione locale errata");
            $this->line("   2. PeerTube locale non configurato correttamente");
        } elseif (!$localAuth && !$prodAuth) {
            $this->error("❌ PROBLEMA IDENTIFICATO:");
            $this->line("   - Autenticazione locale: FALLITA");
            $this->line("   - Autenticazione produzione: FALLITA");
            $this->newLine();
            $this->line("🔧 POSSIBILI CAUSE:");
            $this->line("   1. Credenziali errate in entrambi gli ambienti");
            $this->line("   2. Configurazione OAuth errata");
            $this->line("   3. Problemi di rete");
        } else {
            $this->info("✅ Entrambi gli ambienti funzionano correttamente");
        }
    }
} 