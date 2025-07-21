<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Video;
use App\Services\PeerTubeService;
use Exception;

class TestPeerTubeIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:peertube-integration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa l\'integrazione con PeerTube video.slamin.it';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TEST INTEGRAZIONE PEERTUBE SLAMIN');
        $this->info('=====================================');
        $this->newLine();

        // 1. Test Configurazione
        $this->info('1. Test Configurazione:');
        $this->line('✅ URL Base: ' . config('peertube.base_url'));
        $this->line('✅ Username: ' . (config('peertube.username') ?: 'Non configurato'));
        $this->line('✅ Password: ' . (config('peertube.password') ? 'Configurata' : 'Non configurata'));
        $this->line('✅ Channel ID: ' . (config('peertube.channel_id') ?: 'Non configurato'));
        $this->newLine();

        // 2. Test Connessione PeerTube
        $this->info('2. Test Connessione PeerTube:');
        $peertubeService = new PeerTubeService();

        if ($peertubeService->testConnection()) {
            $this->line('✅ Connessione PeerTube: OK');
        } else {
            $this->line('❌ Connessione PeerTube: FALLITA');
            $this->error('Impossibile connettersi a PeerTube. Verifica l\'URL.');
            return 1;
        }

        // 3. Test Autenticazione OAuth2
        $this->info('3. Test Autenticazione OAuth2:');
        if (config('peertube.username') && config('peertube.password')) {
            try {
                if ($peertubeService->testAuthentication()) {
                    $this->line('✅ Autenticazione OAuth2: OK');
                } else {
                    $this->line('❌ Autenticazione OAuth2: FALLITA');
                    $this->error('Credenziali non valide o utente non admin.');
                }
            } catch (Exception $e) {
                $this->line('❌ Autenticazione OAuth2: ERRORE');
                $this->error('Errore: ' . $e->getMessage());
            }
        } else {
            $this->line('⚠️  Autenticazione: Credenziali non configurate');
        }
        $this->newLine();

        // 4. Test Utente
        $this->info('4. Test Utente:');
        $user = User::where('email', 'organizer@poetryslam.it')->first();

        if ($user) {
            $this->info("✅ Utente trovato: {$user->name}");
            $this->info("   - Video attuali: {$user->current_video_count}");
            $this->info("   - Limite video: {$user->current_video_limit}");
            $this->info("   - Può caricare altri video: " . ($user->canUploadMoreVideos() ? 'Sì' : 'No'));
            $this->info("   - Video rimanenti: {$user->remaining_video_uploads}");
        } else {
            $this->error('❌ Utente non trovato');
        }
        $this->newLine();

        // 5. Test Video Esistenti
        $this->info('5. Test Video Esistenti:');
        $videos = Video::all();
        $this->info("✅ Video nel database: {$videos->count()}");

        foreach ($videos as $video) {
            $this->info("   - {$video->title}");
            $this->info("     • PeerTube ID: " . ($video->peertube_id ?: 'Non configurato'));
            $this->info("     • Moderazione: {$video->moderation_status}");
            $this->info("     • Visualizzazioni: {$video->view_count}");
        }
        $this->newLine();

        // 6. Test Service Methods
        $this->info('6. Test Service Methods:');

        // Test getVideo (se abbiamo un video con peertube_id)
        $peerTubeVideo = Video::whereNotNull('peertube_id')->first();
        if ($peerTubeVideo) {
            try {
                $videoInfo = $peertubeService->getVideo($peerTubeVideo->peertube_id);
                $this->info("✅ getVideo(): OK - Video ID {$peerTubeVideo->peertube_id}");
            } catch (\Exception $e) {
                $this->error("❌ getVideo(): ERRORE - " . $e->getMessage());
            }
        } else {
            $this->info("ℹ️  getVideo(): Nessun video PeerTube per testare");
        }

        // Test getVideoStats
        if ($peerTubeVideo) {
            try {
                $stats = $peertubeService->getVideoStats($peerTubeVideo->peertube_id);
                $this->info("✅ getVideoStats(): OK");
            } catch (\Exception $e) {
                $this->error("❌ getVideoStats(): ERRORE - " . $e->getMessage());
            }
        } else {
            $this->info("ℹ️  getVideoStats(): Nessun video PeerTube per testare");
        }
        $this->newLine();

        // 7. Test Configurazione Ambiente
        $this->info('7. Test Configurazione Ambiente:');
        $this->info("✅ Ambiente: " . app()->environment());
        $this->info("✅ Debug: " . (config('app.debug') ? 'Attivo' : 'Disattivo'));
        $this->info("✅ Cache: " . (config('cache.default')));
        $this->newLine();

        // 8. Raccomandazioni
        $this->info('8. Raccomandazioni:');

        if (!config('peertube.username') || !config('peertube.password')) {
            $this->warn("⚠️  Configura PEERTUBE_USERNAME e PEERTUBE_PASSWORD nel file .env");
        }

        if (!config('peertube.channel_id')) {
            $this->warn("⚠️  Configura PEERTUBE_CHANNEL_ID nel file .env");
        }

        if (!$peertubeService->testConnection()) {
            $this->warn("⚠️  Verifica la connessione a " . config('peertube.base_url'));
        }

        $this->newLine();
        $this->info('🎉 Test completato!');
        $this->info('Per testare l\'upload, vai su: http://127.0.0.1:8000/videos/upload');
    }
}
