<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\SystemSetting;

class TestPeerTubeUsers extends Command
{
    protected $signature = 'peertube:test-users';
    protected $description = 'Test PeerTube users list';

    public function handle()
    {
        $this->info('Testing PeerTube users...');

        $baseUrl = SystemSetting::where('key', 'peertube_url')->value('value');
        if (!$baseUrl) {
            $this->error('URL PeerTube non configurata!');
            return 1;
        }

        $this->info("URL: $baseUrl");

        try {
            // Prova a ottenere la lista utenti (pubblica)
            $response = Http::timeout(10)->get($baseUrl . '/api/v1/users');
            $this->info("Status: " . $response->status());

            if ($response->successful()) {
                $users = $response->json();
                $this->info("✅ Lista utenti ottenuta");
                $this->info("Total users: " . ($users['total'] ?? 'unknown'));

                if (isset($users['data']) && is_array($users['data'])) {
                    $this->info("\nUtenti trovati:");
                    foreach ($users['data'] as $user) {
                        $this->info("- " . $user['username'] . " (Role: " . $user['role']['label'] . ")");
                    }
                }

            } else {
                $this->error("❌ Errore ottenimento utenti: " . $response->body());

                // Prova a ottenere informazioni sull'istanza
                $this->info("\nTrying to get instance info...");
                $instanceResponse = Http::timeout(10)->get($baseUrl . '/api/v1/server');

                if ($instanceResponse->successful()) {
                    $instance = $instanceResponse->json();
                    $this->info("✅ Informazioni istanza ottenute");
                    $this->info("Instance name: " . ($instance['name'] ?? 'unknown'));
                    $this->info("Total users: " . ($instance['totalUsers'] ?? 'unknown'));
                    $this->info("Total videos: " . ($instance['totalVideos'] ?? 'unknown'));
                }

                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Errore connessione: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
