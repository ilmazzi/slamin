<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\SystemSetting;

class TestPeerTubeConfig extends Command
{
    protected $signature = 'peertube:test-config';
    protected $description = 'Test PeerTube server configuration';

    public function handle()
    {
        $this->info('Testing PeerTube configuration...');

        $baseUrl = SystemSetting::where('key', 'peertube_url')->value('value');
        if (!$baseUrl) {
            $this->error('URL PeerTube non configurata!');
            return 1;
        }

        $this->info("URL: $baseUrl");

        try {
            $response = Http::timeout(10)->get($baseUrl . '/api/v1/config');
            $this->info("Status: " . $response->status());

            if ($response->successful()) {
                $config = $response->json();
                $this->info("✅ Configurazione ottenuta");
                $this->info("Signup enabled: " . ($config['signup']['enabled'] ?? 'unknown'));
                $this->info("OAuth2 enabled: " . ($config['oauth2']['enabled'] ?? 'unknown'));
                $this->info("Server version: " . ($config['serverVersion'] ?? 'unknown'));

                // Mostra altre informazioni utili
                if (isset($config['signup'])) {
                    $this->info("Signup limit: " . ($config['signup']['limit'] ?? 'unknown'));
                }

                if (isset($config['contactForm'])) {
                    $this->info("Contact form enabled: " . ($config['contactForm']['enabled'] ?? 'unknown'));
                }

                $this->info("\nConfigurazione completa:");
                $this->line(json_encode($config, JSON_PRETTY_PRINT));

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
