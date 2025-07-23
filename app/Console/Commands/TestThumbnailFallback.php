<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;

class TestThumbnailFallback extends Command
{
    protected $signature = 'thumbnails:test-fallback {--video-id= : ID specifico del video} {--all : Testa tutti i video}';
    protected $description = 'Testa il sistema di fallback delle thumbnail';

    public function handle(ThumbnailService $thumbnailService)
    {
        $this->info('=== TEST THUMBNAIL FALLBACK ===');

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
            $this->line("Thumbnail attuale: " . ($video->thumbnail_path ?: 'NULL'));

            try {
                // Test del metodo con fallback
                $thumbnailPath = $thumbnailService->generateThumbnailWithFallback($video);

                $this->line("✅ Thumbnail generata: {$thumbnailPath}");
                $success++;

                // Aggiorna il video nel database
                $video->update(['thumbnail_path' => $thumbnailPath]);

            } catch (\Exception $e) {
                $this->error("❌ Errore: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n=== RISULTATI TEST FALLBACK ===");
        $this->info("Successi: {$success}");
        $this->info("Errori: {$errors}");
        $this->info("=== FINE TEST ===");
    }
}
