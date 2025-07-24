<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class TestUploadWithoutTranscoding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:test-upload-no-transcoding {--user-id=} {--file=} {--title=Test Video No Transcoding}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa l\'upload video senza transcoding per verificare che funzioni';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎬 TEST UPLOAD SENZA TRANCODING');
        $this->info('===============================');
        $this->newLine();

        $userId = $this->option('user-id');
        $filePath = $this->option('file');
        $title = $this->option('title');

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

            // Se non è specificato un utente, usa il primo utente con PeerTube
            if (!$userId) {
                $user = User::whereNotNull('peertube_user_id')->first();
                if (!$user) {
                    $this->error('❌ Nessun utente con account PeerTube trovato!');
                    return 1;
                }
                $userId = $user->id;
            } else {
                $user = User::find($userId);
                if (!$user) {
                    $this->error('❌ Utente non trovato con ID: ' . $userId);
                    return 1;
                }
            }

            $this->info('👤 Utente: ' . $user->name . ' (' . $user->email . ')');
            $this->info('✅ PeerTube User ID: ' . $user->peertube_user_id);
            $this->info('✅ PeerTube Username: ' . $user->peertube_username);

            // Se non è specificato un file, crea un file di test
            if (!$filePath) {
                $this->info('📁 Creazione file di test...');
                $filePath = $this->createTestVideoFile();
                $this->info('✅ File di test creato: ' . $filePath);
            } else {
                if (!file_exists($filePath)) {
                    $this->error('❌ File non trovato: ' . $filePath);
                    return 1;
                }
            }

            $fileSize = filesize($filePath);
            $this->info('📊 Dimensione file: ' . number_format($fileSize / 1024 / 1024, 2) . ' MB');

            // Crea un UploadedFile per il test
            $uploadedFile = new UploadedFile(
                $filePath,
                basename($filePath),
                mime_content_type($filePath),
                null,
                true
            );

            $this->info('🚀 Iniziando upload SENZA transcoding...');
            
            $metadata = [
                'name' => $title,
                'description' => 'Test upload senza transcoding - ' . date('Y-m-d H:i:s'),
                'tags' => ['test', 'no-transcoding', 'slamin'],
                'privacy' => 1, // Public
            ];

            try {
                $startTime = microtime(true);
                $result = $peerTubeService->uploadVideo($user, $uploadedFile, $metadata);
                $endTime = microtime(true);
                $uploadTime = ($endTime - $startTime) * 1000;
                
                $this->info('✅ Upload completato con successo!');
                $this->info('⏱️ Tempo di upload: ' . number_format($uploadTime, 2) . ' ms');
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

                $this->newLine();
                $this->info('🎉 Test completato con successo!');
                $this->info('✅ L\'upload senza transcoding funziona correttamente');
                $this->info('💡 Il video sarà disponibile immediatamente senza attese');

                // Cleanup file di test se creato da noi
                if (!$this->option('file') && file_exists($filePath)) {
                    unlink($filePath);
                    $this->info('🧹 File di test eliminato');
                }

            } catch (\Exception $e) {
                $this->error('❌ Errore durante l\'upload: ' . $e->getMessage());
                Log::error('Test upload without transcoding error: ' . $e->getMessage(), [
                    'user_id' => $userId,
                    'file_path' => $filePath,
                    'trace' => $e->getTraceAsString()
                ]);
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante il test: ' . $e->getMessage());
            Log::error('Test upload without transcoding error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    private function createTestVideoFile(): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_video_');
        
        // Crea un file MP4 minimo (header falso ma valido)
        $mp4Header = "\x00\x00\x00\x20ftypmp41\x00\x00\x00\x00mp41isom";
        $videoData = str_repeat("\x00", 1024 * 10); // 10KB di dati
        
        file_put_contents($tempFile, $mp4Header . $videoData);
        
        return $tempFile;
    }
} 