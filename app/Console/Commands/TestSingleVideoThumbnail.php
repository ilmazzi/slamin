<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;

class TestSingleVideoThumbnail extends Command
{
    protected $signature = 'thumbnails:test-single {video-id : ID del video da testare}';
    protected $description = 'Testa la generazione di thumbnail per un singolo video con logging dettagliato';

    public function handle(ThumbnailService $thumbnailService)
    {
        $videoId = $this->argument('video-id');

        $this->info("=== TEST SINGOLA THUMBNAIL PER VIDEO {$videoId} ===");

        $video = Video::find($videoId);
        if (!$video) {
            $this->error("Video con ID {$videoId} non trovato!");
            return 1;
        }

        $this->line("\n📋 INFORMAZIONI VIDEO:");
        $this->line("ID: {$video->id}");
        $this->line("Titolo: {$video->title}");
        $this->line("Thumbnail attuale: " . ($video->thumbnail_path ?: 'NULL'));

        $this->line("\n🔍 DATI VIDEO:");
        $this->line("peertube_id: " . ($video->peertube_id ?: 'NULL'));
        $this->line("peertube_uuid: " . ($video->peertube_uuid ?: 'NULL'));
        $this->line("peertube_url: " . ($video->peertube_url ?: 'NULL'));
        $this->line("peertube_thumbnail_url: " . ($video->peertube_thumbnail_url ?: 'NULL'));
        $this->line("file_path: " . ($video->file_path ?: 'NULL'));
        $this->line("video_url: " . ($video->video_url ?: 'NULL'));
        $this->line("status: " . ($video->status ?: 'NULL'));
        $this->line("upload_status: " . ($video->upload_status ?: 'NULL'));

        $this->line("\n🚀 INIZIO GENERAZIONE THUMBNAIL...");

        try {
            $thumbnailPath = $thumbnailService->generateThumbnailWithFallback($video);

            $this->line("\n✅ RISULTATO:");
            $this->line("Thumbnail generata: {$thumbnailPath}");

            // Aggiorna il video
            $video->update(['thumbnail_path' => $thumbnailPath]);
            $this->line("Database aggiornato!");

            // Verifica se il file esiste
            $fullPath = storage_path('app/public/' . $thumbnailPath);
            if (file_exists($fullPath)) {
                $this->line("✅ File thumbnail esiste: {$fullPath}");
                $this->line("Dimensione: " . filesize($fullPath) . " bytes");
            } else {
                $this->line("❌ File thumbnail NON esiste: {$fullPath}");
            }

        } catch (\Exception $e) {
            $this->error("❌ Errore durante la generazione: " . $e->getMessage());
            return 1;
        }

        $this->info("\n=== TEST COMPLETATO ===");
    }
}
