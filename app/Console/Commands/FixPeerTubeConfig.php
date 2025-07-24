<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeerTubeConfig;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FixPeerTubeConfig extends Command
{
    protected $signature = 'peertube:fix-config {--force} {--reset}';
    protected $description = 'Verifica e correggi le configurazioni PeerTube';

    public function handle()
    {
        $this->info('🔧 VERIFICA E CORREZIONE CONFIGURAZIONI PEERTUBE');
        $this->info('================================================');
        $this->newLine();

        // Reset se richiesto
        if ($this->option('reset')) {
            $this->resetConfig();
            return 0;
        }

        // 1. Verifica configurazione attuale
        $this->info('1. VERIFICA CONFIGURAZIONE ATTUALE');
        $this->info('----------------------------------');
        
        $config = $this->getCurrentConfig();
        $this->displayConfig($config);
        
        if (!$this->isConfigComplete($config)) {
            $this->error('❌ Configurazione incompleta!');
            
            if ($this->option('force')) {
                $this->info('🔧 Modalità force attiva - procedo con la correzione...');
                $this->fixIncompleteConfig($config);
            } else {
                $this->line('💡 Usa --force per correggere automaticamente la configurazione');
                return 1;
            }
        } else {
            $this->info('✅ Configurazione completa');
        }
        
        $this->newLine();

        // 2. Test connessione
        $this->info('2. TEST CONNESSIONE');
        $this->info('------------------');
        
        $connectionOk = $this->testConnection($config['url']);
        if (!$connectionOk) {
            $this->error('❌ Connessione fallita!');
            $this->suggestConnectionFixes($config['url']);
            return 1;
        }
        
        $this->info('✅ Connessione OK');
        $this->newLine();

        // 3. Test autenticazione
        $this->info('3. TEST AUTENTICAZIONE');
        $this->info('----------------------');
        
        $authOk = $this->testAuthentication($config);
        if (!$authOk) {
            $this->error('❌ Autenticazione fallita!');
            $this->suggestAuthFixes($config);
            return 1;
        }
        
        $this->info('✅ Autenticazione OK');
        $this->newLine();

        // 4. Verifica token
        $this->info('4. VERIFICA TOKEN');
        $this->info('-----------------');
        
        $tokenOk = $this->verifyToken();
        if (!$tokenOk) {
            $this->info('🔄 Token non valido - rigenerazione...');
            $this->regenerateToken($config);
        } else {
            $this->info('✅ Token valido');
        }
        
        $this->newLine();
        $this->info('✅ Configurazione PeerTube verificata e corretta!');
        return 0;
    }
    
    private function getCurrentConfig()
    {
        return [
            'url' => PeerTubeConfig::getValue('peertube_url'),
            'username' => PeerTubeConfig::getValue('peertube_admin_username'),
            'password' => PeerTubeConfig::getValue('peertube_admin_password'),
            'channel_id' => PeerTubeConfig::getValue('peertube_channel_id'),
            'account_id' => PeerTubeConfig::getValue('peertube_account_id'),
        ];
    }
    
    private function displayConfig($config)
    {
        $this->line("URL: " . ($config['url'] ?: '❌ NON CONFIGURATO'));
        $this->line("Username: " . ($config['username'] ?: '❌ NON CONFIGURATO'));
        $this->line("Password: " . ($config['password'] ? str_repeat('*', strlen($config['password'])) : '❌ NON CONFIGURATO'));
        $this->line("Channel ID: " . ($config['channel_id'] ?: '❌ NON CONFIGURATO'));
        $this->line("Account ID: " . ($config['account_id'] ?: '❌ NON CONFIGURATO'));
    }
    
    private function isConfigComplete($config)
    {
        return !empty($config['url']) && 
               !empty($config['username']) && 
               !empty($config['password']);
    }
    
    private function fixIncompleteConfig($config)
    {
        $this->info('🔧 Correzione configurazione...');
        
        // Se manca l'URL, usa quello di default
        if (empty($config['url'])) {
            $defaultUrl = 'https://video.slamin.it';
            $this->line("   Imposto URL di default: {$defaultUrl}");
            PeerTubeConfig::setValue('peertube_url', $defaultUrl);
            $config['url'] = $defaultUrl;
        }
        
        // Se manca username, chiedi all'utente
        if (empty($config['username'])) {
            $username = $this->ask('Inserisci username admin PeerTube:');
            if ($username) {
                PeerTubeConfig::setValue('peertube_admin_username', $username);
                $config['username'] = $username;
            }
        }
        
        // Se manca password, chiedi all'utente
        if (empty($config['password'])) {
            $password = $this->secret('Inserisci password admin PeerTube:');
            if ($password) {
                PeerTubeConfig::setValue('peertube_admin_password', $password, 'string', 'Password admin PeerTube', true);
                $config['password'] = $password;
            }
        }
        
        $this->info('✅ Configurazione corretta');
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
        if (!$this->isConfigComplete($config)) {
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
    
    private function verifyToken()
    {
        $token = PeerTubeConfig::getValue('peertube_access_token');
        $expiresAt = PeerTubeConfig::getValue('peertube_token_expires_at');
        
        if (empty($token)) {
            return false;
        }
        
        if ($expiresAt && $expiresAt->isPast()) {
            return false;
        }
        
        return true;
    }
    
    private function regenerateToken($config)
    {
        try {
            // Rigenera il token chiamando il metodo di autenticazione
            $this->testAuthentication($config);
            
            $this->info('✅ Token rigenerato con successo');
            
        } catch (\Exception $e) {
            $this->error('❌ Errore rigenerazione token: ' . $e->getMessage());
        }
    }
    
    private function resetConfig()
    {
        $this->info('🔄 Reset configurazioni PeerTube...');
        
        // Rimuovi tutte le configurazioni
        PeerTubeConfig::setValue('peertube_url', '');
        PeerTubeConfig::setValue('peertube_admin_username', '');
        PeerTubeConfig::setValue('peertube_admin_password', '', 'string', 'Password admin PeerTube', true);
        PeerTubeConfig::setValue('peertube_access_token', '', 'string', 'Token di accesso admin PeerTube', true);
        PeerTubeConfig::setValue('peertube_token_expires_at', null, 'datetime');
        PeerTubeConfig::setValue('peertube_channel_id', '');
        PeerTubeConfig::setValue('peertube_account_id', '');
        
        $this->info('✅ Configurazioni resettate');
    }
    
    private function suggestConnectionFixes($url)
    {
        $this->line('💡 SUGGERIMENTI PER LA CONNESSIONE:');
        $this->line('   1. Verifica che l\'URL sia corretto: ' . ($url ?: 'NON CONFIGURATO'));
        $this->line('   2. Verifica che il server PeerTube sia raggiungibile');
        $this->line('   3. Verifica firewall e restrizioni di rete');
        $this->line('   4. Verifica che il server risponda su HTTPS');
        $this->line('   5. Prova a fare ping o curl manualmente');
    }
    
    private function suggestAuthFixes($config)
    {
        $this->line('💡 SUGGERIMENTI PER L\'AUTENTICAZIONE:');
        $this->line('   1. Verifica username e password admin PeerTube');
        $this->line('   2. Verifica che l\'utente abbia i permessi admin');
        $this->line('   3. Verifica che OAuth sia abilitato su PeerTube');
        $this->line('   4. Verifica la configurazione OAuth in PeerTube');
        $this->line('   5. Prova a fare login manualmente su PeerTube');
        $this->line('   6. Verifica che non ci siano restrizioni IP');
    }
} 