<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestVideoUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:test-upload {--user-id=} {--video-id=} {--file=} {--title=Test Video} {--description=Test upload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa l\'upload di un video su PeerTube';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎬 TEST UPLOAD VIDEO PEERTUBE');
        $this->info('=============================');
        $this->newLine();

        $userId = $this->option('user-id');
        $videoId = $this->option('video-id');
        $filePath = $this->option('file');
        $title = $this->option('title');
        $description = $this->option('description');

        try {
            $peerTubeService = new PeerTubeService();

            // Verifica configurazione
            if (!$peerTubeService->isConfigured()) {
                $this->error('❌ PeerTube non è configurato!');
                return 1;
            }

            // Test connessione
            if (!$peerTubeService->testConnection()) {
                $this->error('❌ Connessione PeerTube fallita!');
                return 1;
            }

            // Test autenticazione
            if (!$peerTubeService->testAuthentication()) {
                $this->error('❌ Autenticazione PeerTube fallita!');
                return 1;
            }

            $this->info('✅ Configurazione, connessione e autenticazione OK');
            $this->newLine();

            // Se abbiamo un video ID, testiamo quello
            if ($videoId) {
                $this->testExistingVideo($videoId, $peerTubeService);
            }
            // Se abbiamo un user ID e un file, testiamo l'upload
            elseif ($userId && $filePath) {
                $this->testNewUpload($userId, $filePath, $title, $description, $peerTubeService);
            }
            // Altrimenti mostriamo le opzioni
            else {
                $this->showUsageOptions();
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante il test: ' . $e->getMessage());
            Log::error('Test video upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    private function testExistingVideo($videoId, $peerTubeService)
    {
        $this->info('📋 TEST VIDEO ESISTENTE');
        $this->info('=======================');

        $video = Video::find($videoId);
        if (!$video) {
            $this->error('❌ Video non trovato con ID: ' . $videoId);
            return;
        }

        $this->info('🎬 Video: ' . $video->title);
        $this->info('👤 Proprietario: ' . $video->user->name);
        $this->info('🔄 Stato: ' . $video->status);
        $this->info('📁 File: ' . $video->file_path);
        $this->info('📊 Dimensione: ' . ($video->file_size ? number_format($video->file_size / 1024 / 1024, 2) . ' MB' : 'N/A'));

        if ($video->status === 'pending') {
            $this->info('⏳ Video in attesa di upload...');
            $this->info('🔍 Verificando se il file esiste...');

            if (Storage::exists($video->file_path)) {
                $this->info('✅ File trovato nel storage');
                
                // Verifica che l'utente abbia un account PeerTube
                if (!$video->user->peertube_user_id) {
                    $this->error('❌ L\'utente non ha un account PeerTube!');
                    return;
                }

                $this->info('🚀 Tentativo di upload...');
                $this->attemptUpload($video, $peerTubeService);

            } else {
                $this->error('❌ File non trovato nel storage: ' . $video->file_path);
            }
        } elseif ($video->status === 'uploaded') {
            $this->info('✅ Video già caricato');
            if ($video->peertube_url) {
                $this->info('🔗 URL PeerTube: ' . $video->peertube_url);
            }
        } else {
            $this->info('⚠️ Stato video: ' . $video->status);
        }
    }

    private function testNewUpload($userId, $filePath, $title, $description, $peerTubeService)
    {
        $this->info('📤 TEST NUOVO UPLOAD');
        $this->info('===================');

        $user = User::find($userId);
        if (!$user) {
            $this->error('❌ Utente non trovato con ID: ' . $userId);
            return;
        }

        $this->info('👤 Utente: ' . $user->name . ' (' . $user->email . ')');

        if (!$user->peertube_user_id) {
            $this->error('❌ L\'utente non ha un account PeerTube!');
            return;
        }

        $this->info('✅ PeerTube User ID: ' . $user->peertube_user_id);
        $this->info('✅ PeerTube Username: ' . $user->peertube_username);

        // Verifica che il file esista
        if (!file_exists($filePath)) {
            $this->error('❌ File non trovato: ' . $filePath);
            return;
        }

        $fileSize = filesize($filePath);
        $this->info('📁 File: ' . basename($filePath));
        $this->info('📊 Dimensione: ' . number_format($fileSize / 1024 / 1024, 2) . ' MB');

        // Crea un UploadedFile per il test
        $uploadedFile = new UploadedFile(
            $filePath,
            basename($filePath),
            mime_content_type($filePath),
            null,
            true
        );

        $this->info('🚀 Iniziando upload...');
        
        $metadata = [
            'name' => $title,
            'description' => $description,
            'tags' => ['test', 'slamin', 'poetry'],
            'privacy' => 1, // Public
        ];

        try {
            $result = $peerTubeService->uploadVideo($user, $uploadedFile, $metadata);
            
            $this->info('✅ Upload completato con successo!');
            $this->newLine();
            
            $this->info('📋 Dettagli video caricato:');
            $this->table(
                ['Campo', 'Valore'],
                [
                    ['Video ID', $result['video_id'] ?? 'N/A'],
                    ['UUID', $result['uuid'] ?? 'N/A'],
                    ['Nome', $result['name'] ?? 'N/A'],
                    ['Descrizione', $result['description'] ?? 'N/A'],
                    ['Durata', $result['duration'] ?? 'N/A'],
                    ['URL', $result['url'] ?? 'N/A'],
                    ['Embed URL', $result['embedUrl'] ?? 'N/A'],
                ]
            );

        } catch (\Exception $e) {
            $this->error('❌ Errore durante l\'upload: ' . $e->getMessage());
            Log::error('Video upload error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'file_path' => $filePath,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function attemptUpload($video, $peerTubeService)
    {
        try {
            // Crea un UploadedFile dal file esistente
            $filePath = Storage::path($video->file_path);
            
            if (!file_exists($filePath)) {
                $this->error('❌ File non trovato: ' . $filePath);
                return;
            }

            $uploadedFile = new UploadedFile(
                $filePath,
                basename($filePath),
                mime_content_type($filePath),
                null,
                true
            );

            $metadata = [
                'name' => $video->title,
                'description' => $video->description ?? '',
                'tags' => ['poetry', 'slam', 'slamin'],
                'privacy' => 1, // Public
            ];

            $this->info('📤 Upload in corso...');
            $result = $peerTubeService->uploadVideo($video->user, $uploadedFile, $metadata);

            // Aggiorna il video nel database
            $video->update([
                'status' => 'uploaded',
                'peertube_id' => $result['video_id'] ?? null,
                'peertube_url' => $result['url'] ?? null,
                'peertube_embed_url' => $result['embedUrl'] ?? null,
            ]);

            $this->info('✅ Upload completato!');
            $this->info('🆔 Video ID: ' . ($result['video_id'] ?? 'N/A'));
            $this->info('🔗 URL: ' . ($result['url'] ?? 'N/A'));

        } catch (\Exception $e) {
            $this->error('❌ Errore upload: ' . $e->getMessage());
            
            // Aggiorna lo stato del video come fallito
            $video->update(['status' => 'failed']);
            
            Log::error('Video upload failed: ' . $e->getMessage(), [
                'video_id' => $video->id,
                'user_id' => $video->user_id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function showUsageOptions()
    {
        $this->info('💡 Opzioni di utilizzo:');
        $this->info('   - php artisan peertube:test-upload --video-id=N (testa upload di un video esistente)');
        $this->info('   - php artisan peertube:test-upload --user-id=N --file=/path/to/video.mp4 (testa nuovo upload)');
        $this->newLine();
        
        $this->info('📋 Video disponibili per il test:');
        $videos = Video::where('status', 'pending')->with('user')->get();
        
        if ($videos->count() > 0) {
            foreach ($videos as $video) {
                $this->info('   - ID: ' . $video->id . ' | ' . $video->title . ' | User: ' . $video->user->name);
            }
        } else {
            $this->info('   Nessun video in attesa di upload');
        }
    }
} 