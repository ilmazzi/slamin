<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Http\UploadedFile;
use Exception;

class TestVideoUpload extends Command
{
    protected $signature = 'test:video-upload {--user-id=} {--title=} {--description=}';
    protected $description = 'Testa l\'upload video con utente loggato';

    public function handle()
    {
        $this->info('🎬 TEST UPLOAD VIDEO CON UTENTE LOGGATO');
        $this->info('========================================');
        $this->newLine();

        // Test configurazione
        $this->info('1. Test configurazione PeerTube:');
        $service = new PeerTubeService();
        
        if (!$service->isConfigured()) {
            $this->error('   ❌ PeerTube NON configurato');
            return 1;
        }
        $this->info('   ✅ PeerTube configurato correttamente');
        
        if (!$service->testConnection()) {
            $this->error('   ❌ Connessione PeerTube FALLITA');
            return 1;
        }
        $this->info('   ✅ Connessione PeerTube OK');

        $this->newLine();

        // Test utente
        $userId = $this->option('user-id');
        if (!$userId) {
            $this->error('❌ Specifica --user-id');
            return 1;
        }
        
        $user = User::find($userId);
        if (!$user) {
            $this->error('❌ Utente non trovato');
            return 1;
        }
        
        $this->info('2. Test utente:');
        $this->info('   ID: ' . $user->id);
        $this->info('   Nome: ' . $user->name);
        $this->info('   Email: ' . $user->email);

        // Verifica account PeerTube
        if (!$user->hasPeerTubeAccount()) {
            $this->error('   ❌ Utente NON ha account PeerTube');
            return 1;
        }
        $this->info('   ✅ Utente ha account PeerTube');
        $this->info('   Username: ' . $user->peertube_username);
        $this->info('   User ID: ' . $user->peertube_user_id);

        $this->newLine();

        // Test upload simulato
        $this->info('3. Test upload video (simulato):');
        
        $title = $this->option('title') ?: 'Test Video ' . time();
        $description = $this->option('description') ?: 'Video di test per Poetry Slam';
        
        $this->info('   Titolo: ' . $title);
        $this->info('   Descrizione: ' . $description);
        
        try {
            // Simula file video con contenuto più realistico
            $tempFile = tempnam(sys_get_temp_dir(), 'test_video_');
            // Crea un file MP4 minimo (header falso)
            $mp4Header = "\x00\x00\x00\x20ftypmp41\x00\x00\x00\x00mp41isom";
            file_put_contents($tempFile, $mp4Header . str_repeat("\x00", 1024)); // 1KB di dati
            
            $uploadedFile = new UploadedFile(
                $tempFile,
                'test_video.mp4',
                'video/mp4',
                filesize($tempFile),
                true
            );

            // Metadata per PeerTube
            $metadata = [
                'name' => $title,
                'description' => $description,
                'tags' => ['poetry', 'slam', 'test'],
                'privacy' => 1, // Public
                'channelId' => $user->peertube_channel_id,
            ];

            $this->info('   Uploading to PeerTube...');
            $result = $service->uploadVideo($user, $uploadedFile, $metadata);
            
            $this->info('   ✅ Upload completato!');
            $this->info('   Video ID: ' . ($result['video_id'] ?? 'N/A'));
            $this->info('   UUID: ' . ($result['uuid'] ?? 'N/A'));
            $this->info('   Duration: ' . ($result['duration'] ?? 'N/A'));

            // Crea record nel database
            $video = Video::create([
                'user_id' => $user->id,
                'title' => $title,
                'description' => $description,
                'video_url' => $result['url'] ?? null,
                'thumbnail_path' => null,
                'duration' => $result['duration'] ?? null,
                'file_size' => 1024, // Simulato
                'is_public' => true,
                'status' => 'uploaded',
                'moderation_status' => 'approved',
                'tags' => json_encode(['poetry', 'slam', 'test']),
                'peertube_id' => $result['video_id'] ?? null,
                'peertube_uuid' => $result['uuid'] ?? null,
                'peertube_url' => $result['url'] ?? null,
                'peertube_embed_url' => $result['embedUrl'] ?? null,
                'upload_status' => 'completed',
                'uploaded_at' => now(),
                'peertube_privacy' => 'public',
                'peertube_tags' => ['poetry', 'slam', 'test'],
                'peertube_description' => $description,
                'view_count' => 0,
                'like_count' => 0,
                'dislike_count' => 0,
                'comment_count' => 0,
            ]);

            $this->info('   ✅ Record video creato nel database');
            $this->info('   Video ID locale: ' . $video->id);

            // Cleanup
            unlink($tempFile);

            $this->newLine();
            $this->info('✅ Test completato con successo!');
            return 0;

        } catch (Exception $e) {
            $this->error('   ❌ Errore durante l\'upload: ' . $e->getMessage());
            return 1;
        }
    }
} 