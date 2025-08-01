<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;

class ForceGenerateThumbnail extends Command
{
    protected $signature = 'thumbnail:force-generate {video_id?}';
    protected $description = 'Force thumbnail generation for a specific video or the most popular video';

    public function handle()
    {
        $videoId = $this->argument('video_id');
        $thumbnailService = new ThumbnailService();

        if ($videoId) {
            $video = Video::find($videoId);
            if (!$video) {
                $this->error("Video con ID {$videoId} non trovato!");
                return 1;
            }
            $this->generateThumbnail($video, $thumbnailService);
        } else {
            // Trova il video più popolare
            $mostPopularVideo = Video::where('moderation_status', 'approved')
                ->where('is_public', true)
                ->with('user')
                ->get()
                ->sortByDesc(function($video) {
                    return $video->view_count + $video->like_count + $video->comment_count + $video->snaps()->count();
                })
                ->first();

            if (!$mostPopularVideo) {
                $this->error("Nessun video popolare trovato!");
                return 1;
            }

            $this->info("=== GENERAZIONE THUMBNAIL PER VIDEO PIÙ POPOLARE ===");
            $this->generateThumbnail($mostPopularVideo, $thumbnailService);
        }

        return 0;
    }

    private function generateThumbnail(Video $video, ThumbnailService $thumbnailService)
    {
        $this->line("Video ID: {$video->id}");
        $this->line("Titolo: {$video->title}");
        $this->line("Status: {$video->moderation_status}");
        $this->line("Pubblico: " . ($video->is_public ? 'Sì' : 'No'));
        $this->line("");

        $this->line("=== STATO ATTUALE ===");
        $this->line("thumbnail_path: " . ($video->thumbnail_path ?: 'NULL'));
        $this->line("peertube_thumbnail_url: " . ($video->peertube_thumbnail_url ?: 'NULL'));
        $this->line("peertube_id: " . ($video->peertube_id ?: 'NULL'));
        $this->line("peertube_video_id: " . ($video->peertube_video_id ?: 'NULL'));
        $this->line("peertube_uuid: " . ($video->peertube_uuid ?: 'NULL'));
        $this->line("thumbnail_url (accessor): " . $video->thumbnail_url);
        $this->line("");

        $this->info("Generando thumbnail...");

        try {
            $result = $thumbnailService->generateThumbnailWithFallback($video);
            $this->info("✅ Thumbnail generata: {$result}");

            // Refresh del video per ottenere i dati aggiornati
            $video->refresh();

            $this->line("");
            $this->line("=== STATO DOPO GENERAZIONE ===");
            $this->line("thumbnail_path: " . ($video->thumbnail_path ?: 'NULL'));
            $this->line("peertube_thumbnail_url: " . ($video->peertube_thumbnail_url ?: 'NULL'));
            $this->line("thumbnail_url (accessor): " . $video->thumbnail_url);

            // Test della condizione homepage
            $placeholderUrl = asset('assets/images/placeholder/placholder-1.jpg');
            $condition = $video->thumbnail_url && $video->thumbnail_url !== $placeholderUrl;
            $this->line("Condizione homepage: " . ($condition ? 'TRUE' : 'FALSE'));

        } catch (\Exception $e) {
            $this->error("❌ Errore: " . $e->getMessage());
            return 1;
        }
    }
}
