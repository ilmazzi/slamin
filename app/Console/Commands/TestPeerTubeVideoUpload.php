<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class TestPeerTubeVideoUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:test-video-upload {email} {video_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa l\'upload di un video su PeerTube per un utente specifico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $videoPath = $this->argument('video_path');

        $this->info("Test upload video PeerTube per utente: {$email}");
        $this->info("Percorso video: {$videoPath}");

        // Verifica che il file esista
        if (!file_exists($videoPath)) {
            $this->error("File video non trovato: {$videoPath}");
            return 1;
        }

        // Trova l'utente
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("Utente non trovato: {$email}");
            return 1;
        }

        $this->info("Utente trovato: {$user->name} (ID: {$user->id})");

        // Verifica account PeerTube
        if (!$user->hasPeerTubeAccount()) {
            $this->error("L'utente non ha un account PeerTube!");
            $this->info("PeerTube User ID: " . ($user->peertube_user_id ?? 'NULL'));
            $this->info("PeerTube Channel ID: " . ($user->peertube_channel_id ?? 'NULL'));
            return 1;
        }

        $this->info("Account PeerTube trovato:");
        $this->info("- User ID: {$user->peertube_user_id}");
        $this->info("- Channel ID: {$user->peertube_channel_id}");
        $this->info("- Username: {$user->peertube_username}");

        // Prepara i dati del video
        $videoData = [
            'name' => 'Test Video Upload Guzzle - ' . date('Y-m-d H:i:s'),
            'description' => 'Video di test per verificare l\'upload su PeerTube con Guzzle',
            'privacy' => 1, // Public
            'category' => 1, // Music
            'licence' => 1, // Attribution
            'language' => 'it',
            'downloadEnabled' => true,
            'commentsPolicy' => 1, // Enabled
            'nsfw' => false,
            'tags' => ['test', 'peertube', 'guzzle', 'slamin']
        ];

        $this->info("Dati video preparati:");
        $this->info("- Nome: {$videoData['name']}");
        $this->info("- Privacy: " . ($videoData['privacy'] == 1 ? 'Pubblico' : 'Privato'));
        $this->info("- Tags: " . implode(', ', $videoData['tags']));

        // Conferma prima di procedere
        if (!$this->confirm('Procedere con l\'upload del video?')) {
            $this->info('Upload annullato.');
            return 0;
        }

        try {
            $this->info("Inizio upload su PeerTube...");

            $peerTubeService = new PeerTubeService();
            $peerTubeVideo = $peerTubeService->uploadVideo($user, $videoPath, $videoData);

            if ($peerTubeVideo) {
                $this->info("✅ Upload completato con successo!");
                $this->info("Video ID: {$peerTubeVideo['id']}");
                $this->info("Video UUID: {$peerTubeVideo['uuid']}");
                $this->info("Short UUID: " . ($peerTubeVideo['shortUUID'] ?? 'N/A'));

                // Costruisci l'URL del video
                $peerTubeUrl = config('services.peertube.url', 'https://video.slamin.it');
                $videoUrl = $peerTubeUrl . '/videos/watch/' . $peerTubeVideo['uuid'];
                $this->info("URL Video: {$videoUrl}");

                return 0;
            } else {
                $this->error("❌ Upload fallito!");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Errore durante l'upload: " . $e->getMessage());
            Log::error('Errore test upload video PeerTube', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
