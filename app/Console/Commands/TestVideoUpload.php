<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestVideoUpload extends Command
{
    protected $signature = 'test:video-upload {user_email} {video_path}';
    protected $description = 'Testa l\'upload video per un utente specifico';

    public function handle()
    {
        $userEmail = $this->argument('user_email');
        $videoPath = $this->argument('video_path');

        $this->info("🎬 Test upload video per utente: {$userEmail}");
        $this->info("📁 File video: {$videoPath}");

        // Trova l'utente
        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("❌ Utente non trovato: {$userEmail}");
            return 1;
        }

        $this->info("✅ Utente trovato: {$user->name} (ID: {$user->id})");

        // Verifica account PeerTube
        if (!$user->hasPeerTubeAccount()) {
            $this->error("❌ Utente senza account PeerTube");
            return 1;
        }

        $this->info("✅ Account PeerTube verificato");

        // Verifica che il file esista
        if (!file_exists($videoPath)) {
            $this->error("❌ File video non trovato: {$videoPath}");
            return 1;
        }

        $this->info("✅ File video trovato");

        // Test PeerTubeService
        $peerTubeService = new PeerTubeService();

        // Test configurazione
        $this->info("🔧 Test configurazione PeerTube...");
        if (!$peerTubeService->isConfigured()) {
            $this->error("❌ PeerTube non configurato");
            return 1;
        }
        $this->info("✅ PeerTube configurato");

        // Test connessione
        $this->info("🌐 Test connessione PeerTube...");
        if (!$peerTubeService->testConnection()) {
            $this->error("❌ Connessione PeerTube fallita");
            return 1;
        }
        $this->info("✅ Connessione PeerTube OK");

        // Test token admin
        $this->info("🔑 Test token admin...");
        $adminToken = $peerTubeService->getAdminToken();
        if (!$adminToken) {
            $this->error("❌ Impossibile ottenere token admin");
            return 1;
        }
        $this->info("✅ Token admin ottenuto");

        // Prepara dati video di test
        $videoData = [
            'name' => 'Test Video Upload - ' . date('Y-m-d H:i:s'),
            'description' => 'Video di test per debug upload',
            'privacy' => 1, // Public
            'category' => 1, // Music
            'licence' => 1, // Attribution
            'language' => 'it',
            'downloadEnabled' => true,
            'commentsPolicy' => 1, // Enabled
            'nsfw' => false,
        ];

        $this->info("📋 Dati video preparati");

        // Simula upload (senza salvare nel DB)
        $this->info("🚀 Inizio upload simulato...");

        try {
            $peerTubeVideo = $peerTubeService->uploadVideo($user, $videoPath, $videoData);

            if ($peerTubeVideo) {
                $this->info("✅ Upload completato con successo!");
                $this->info("📊 Dati video:");
                $this->table(
                    ['Campo', 'Valore'],
                    [
                        ['ID', $peerTubeVideo['id'] ?? 'N/A'],
                        ['UUID', $peerTubeVideo['uuid'] ?? 'N/A'],
                        ['Short UUID', $peerTubeVideo['shortUUID'] ?? 'N/A'],
                        ['Nome', $peerTubeVideo['name'] ?? 'N/A'],
                        ['URL', $peerTubeVideo['url'] ?? 'N/A'],
                    ]
                );
            } else {
                $this->error("❌ Upload fallito");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("💥 Errore durante upload: " . $e->getMessage());
            Log::error('Test upload video fallito', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        $this->info("🎉 Test completato con successo!");
        return 0;
    }
}
