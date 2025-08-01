<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;

class DebugVideoThumbnail extends Command
{
    protected $signature = 'debug:video-thumbnail {video_id?}';
    protected $description = 'Debug video thumbnail information';

    public function handle()
    {
        $videoId = $this->argument('video_id');

        if ($videoId) {
            $video = Video::find($videoId);
            if (!$video) {
                $this->error("Video con ID {$videoId} non trovato!");
                return 1;
            }
            $this->debugVideo($video);
        } else {
            // Trova il video più popolare come nella homepage
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

            $this->info("=== VIDEO PIÙ POPOLARE ===");
            $this->debugVideo($mostPopularVideo);
        }

        return 0;
    }

    private function debugVideo(Video $video)
    {
        $this->line("Video ID: {$video->id}");
        $this->line("Titolo: {$video->title}");
        $this->line("Status: {$video->moderation_status}");
        $this->line("Pubblico: " . ($video->is_public ? 'Sì' : 'No'));
        $this->line("");

        $this->line("=== CAMPI DATABASE ===");
        $this->line("thumbnail_path: " . ($video->thumbnail_path ?: 'NULL'));
        $this->line("peertube_thumbnail_url: " . ($video->peertube_thumbnail_url ?: 'NULL'));
        $this->line("peertube_id: " . ($video->peertube_id ?: 'NULL'));
        $this->line("peertube_video_id: " . ($video->peertube_video_id ?: 'NULL'));
        $this->line("peertube_uuid: " . ($video->peertube_uuid ?: 'NULL'));
        $this->line("");

        $this->line("=== ACCESSOR ===");
        $this->line("thumbnail_url: " . $video->thumbnail_url);
        $this->line("");

        $this->line("=== TEST CONDIZIONI ===");
        $placeholderUrl = asset('assets/images/placeholder/placholder-1.jpg');
        $this->line("Placeholder URL: " . $placeholderUrl);
        $this->line("thumbnail_url !== placeholder: " . ($video->thumbnail_url !== $placeholderUrl ? 'TRUE' : 'FALSE'));
        $this->line("Condizione homepage: " . ($video->thumbnail_url && $video->thumbnail_url !== $placeholderUrl ? 'TRUE' : 'FALSE'));
        $this->line("");

        $this->line("=== VERIFICA FILE ===");
        if ($video->thumbnail_path && !filter_var($video->thumbnail_path, FILTER_VALIDATE_URL)) {
            $fullPath = storage_path('app/public/' . $video->thumbnail_path);
            $this->line("File locale esiste: " . (file_exists($fullPath) ? 'SÌ' : 'NO'));
            $this->line("Percorso completo: " . $fullPath);
        }
    }
}
