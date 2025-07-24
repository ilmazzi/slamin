<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Http\UploadedFile;
use Exception;

class TestVideoUploadReal extends Command
{
    protected $signature = 'test:video-upload-real {--user-id=} {--file-path=} {--title=} {--description=}';
    protected $description = 'Testa l\'upload video con file reale';

    public function handle()
    {
        $this->info('🎬 TEST UPLOAD VIDEO CON FILE REALE');
        $this->info('====================================');
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
        $this->info('   Channel ID: ' . $user->peertube_channel_id);

        $this->newLine();

        // Test file video
        $filePath = $this->option('file-path');
        if (!$filePath) {
            $this->error('❌ Specifica --file-path (es: C:\\path\\to\\video.mp4)');
            return 1;
        }

        if (!file_exists($filePath)) {
            $this->error('❌ File non trovato: ' . $filePath);
            return 1;
        }

        $this->info('3. Test file video:');
        $this->info('   Path: ' . $filePath);
        $this->info('   Size: ' . number_format(filesize($filePath) / 1024 / 1024, 2) . ' MB');
        $this->info('   Mime: ' . mime_content_type($filePath));

        $this->newLine();

        // Test upload
        $this->info('4. Test upload video:');
        
        $title = $this->option('title') ?: 'Test Video ' . time();
        $description = $this->option('description') ?: 'Video di test per Poetry Slam';
        
        $this->info('   Titolo: ' . $title);
        $this->info('   Descrizione: ' . $description);
        
        try {
            // Crea UploadedFile dal file reale
            $uploadedFile = new UploadedFile(
                $filePath,
                basename($filePath),
                mime_content_type($filePath),
                null,
                true
            );

            // Metadata per PeerTube
            $metadata = [
                'title' => $title,
                'description' => $description,
                'tags' => ['poetry', 'slam', 'test'],
                'privacy' => 1, // Public
            ];

            $this->info('   Uploading to PeerTube...');
            $result = $service->uploadVideo($user, $uploadedFile, $metadata);
            
            $this->info('   ✅ Upload completato!');
            $this->info('   Video ID: ' . ($result['video_id'] ?? 'N/A'));
            $this->info('   UUID: ' . ($result['uuid'] ?? 'N/A'));
            $this->info('   Name: ' . ($result['name'] ?? 'N/A'));
            $this->info('   Duration: ' . ($result['duration'] ?? 'N/A'));
            $this->info('   URL: ' . ($result['url'] ?? 'N/A'));

            // Crea record nel database
            $video = Video::create([
                'user_id' => $user->id,
                'title' => $title,
                'description' => $description,
                'video_url' => $result['url'] ?? '', // Stringa vuota invece di null
                'thumbnail_path' => $result['thumbnailPath'] ?? '',
                'duration' => $result['duration'] ?? 0,
                'file_size' => filesize($filePath),
                'is_public' => true,
                'status' => 'uploaded',
                'moderation_status' => 'approved',
                'tags' => json_encode(['poetry', 'slam', 'test']),
                'peertube_id' => $result['video_id'] ?? null,
                'peertube_uuid' => $result['uuid'] ?? null,
                'peertube_url' => $result['url'] ?? '',
                'peertube_embed_url' => $result['embedUrl'] ?? '',
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

            $this->newLine();
            $this->info('✅ Test completato con successo!');
            $this->info('🎉 Il video è ora disponibile su PeerTube!');
            return 0;

        } catch (Exception $e) {
            $this->error('   ❌ Errore durante l\'upload: ' . $e->getMessage());
            return 1;
        }
    }
} 