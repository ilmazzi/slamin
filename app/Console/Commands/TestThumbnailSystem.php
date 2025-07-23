<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;

class TestThumbnailSystem extends Command
{
    protected $signature = 'thumbnails:test {--video-id= : ID specifico del video} {--all : Testa tutti i video}';
    protected $description = 'Testa il sistema di generazione automatica delle thumbnail';

    public function handle(ThumbnailService $thumbnailService)
    {
        $this->info('=== TEST SISTEMA THUMBNAIL ===');

        if ($videoId = $this->option('video-id')) {
            $videos = Video::where('id', $videoId)->get();
        } elseif ($this->option('all')) {
            $videos = Video::where('moderation_status', 'approved')->get();
        } else {
            $this->error('Specifica --video-id=X o --all');
            return 1;
        }

        $this->info("Trovati {$videos->count()} video da testare");

        $success = 0;
        $errors = 0;

        foreach ($videos as $video) {
            $this->line("\n--- Video ID: {$video->id} ---");
            $this->line("Titolo: {$video->title}");
            $this->line("Thumbnail attuale: " . ($video->thumbnail_path ?: 'Nessuna'));

            try {
                $thumbnailPath = null;

                // Test 1: PeerTube thumbnail
                if ($video->peertube_thumbnail_url) {
                    $this->line("🔍 Testando PeerTube thumbnail...");
                    $thumbnailPath = $thumbnailService->downloadThumbnailFromUrl(
                        $video->peertube_thumbnail_url,
                        $video,
                        'peertube'
                    );
                    if ($thumbnailPath) {
                        $this->line("✅ PeerTube thumbnail scaricata: {$thumbnailPath}");
                    }
                }

                // Test 2: Generazione da file locale
                if (!$thumbnailPath && $video->file_path) {
                    $this->line("🔍 Testando generazione da file locale...");
                    $thumbnailPath = $thumbnailService->generateThumbnailFromFile($video);
                    if ($thumbnailPath) {
                        $this->line("✅ Thumbnail generata da file: {$thumbnailPath}");
                    }
                }

                // Test 3: Video esterni
                if (!$thumbnailPath && $video->video_url) {
                    $this->line("🔍 Testando video esterni...");
                    $thumbnailPath = $thumbnailService->generateExternalVideoThumbnail($video);
                    if ($thumbnailPath) {
                        $this->line("✅ Thumbnail da video esterno: {$thumbnailPath}");
                    }
                }

                if ($thumbnailPath) {
                    $video->update(['thumbnail_path' => $thumbnailPath]);
                    $this->line("✅ Thumbnail salvata nel database");
                    $success++;
                } else {
                    $this->warn("⚠️ Nessuna thumbnail generata");
                    $errors++;
                }

            } catch (\Exception $e) {
                $this->error("❌ Errore: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n=== RISULTATI TEST ===");
        $this->info("Successi: {$success}");
        $this->info("Errori: {$errors}");
        $this->info("=== FINE TEST ===");
    }
}
