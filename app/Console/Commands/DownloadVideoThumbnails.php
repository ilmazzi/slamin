<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadVideoThumbnails extends Command
{
    protected $signature = 'videos:download-thumbnails {--video-id= : ID specifico del video} {--all : Scarica tutte le thumbnail}';
    protected $description = 'Scarica le thumbnail dei video e le salva localmente';

    public function handle()
    {
        $this->info('=== DOWNLOAD THUMBNAIL VIDEO ===');

        if ($videoId = $this->option('video-id')) {
            $videos = Video::where('id', $videoId)->get();
        } elseif ($this->option('all')) {
            $videos = Video::where('moderation_status', 'approved')->get();
        } else {
            $this->error('Specifica --video-id=X o --all');
            return 1;
        }

        $this->info("Trovati {$videos->count()} video da processare");

        $success = 0;
        $errors = 0;

        foreach ($videos as $video) {
            $this->line("\n--- Video ID: {$video->id} ---");
            $this->line("Titolo: {$video->title}");

            try {
                $thumbnailPath = $this->downloadThumbnail($video);
                if ($thumbnailPath) {
                    $video->update(['thumbnail_path' => $thumbnailPath]);
                    $this->line("✅ Thumbnail scaricata: {$thumbnailPath}");
                    $success++;
                } else {
                    $this->warn("⚠️ Nessuna thumbnail disponibile");
                    $errors++;
                }
            } catch (\Exception $e) {
                $this->error("❌ Errore: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n=== RISULTATI ===");
        $this->info("Successi: {$success}");
        $this->info("Errori: {$errors}");
        $this->info("=== FINE DOWNLOAD ===");
    }

    protected function downloadThumbnail(Video $video): ?string
    {
        // 1. Prova PeerTube thumbnail
        if ($video->peertube_thumbnail_url) {
            return $this->downloadFromUrl($video->peertube_thumbnail_url, $video->id, 'peertube');
        }

        // 2. Prova YouTube thumbnail
        if ($video->video_url && strpos($video->video_url, 'youtube') !== false) {
            $videoId = $video->video_id;
            if ($videoId) {
                $youtubeThumbnail = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
                return $this->downloadFromUrl($youtubeThumbnail, $video->id, 'youtube');
            }
        }

        // 3. Prova Vimeo thumbnail
        if ($video->video_url && strpos($video->video_url, 'vimeo') !== false) {
            $videoId = $video->video_id;
            if ($videoId) {
                // Vimeo richiede API call per thumbnail
                return $this->downloadVimeoThumbnail($videoId, $video->id);
            }
        }

        return null;
    }

    protected function downloadFromUrl(string $url, int $videoId, string $source): ?string
    {
        try {
            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                $extension = $this->getExtensionFromUrl($url) ?: 'jpg';
                $filename = "videos/thumbnails/{$videoId}_{$source}.{$extension}";

                Storage::disk('public')->put($filename, $response->body());

                return $filename;
            }
        } catch (\Exception $e) {
            $this->warn("Errore nel download da {$url}: " . $e->getMessage());
        }

        return null;
    }

    protected function downloadVimeoThumbnail(string $videoId, int $dbVideoId): ?string
    {
        // Per Vimeo servirebbe l'API, per ora restituiamo null
        $this->warn("Download Vimeo thumbnail non implementato per video {$videoId}");
        return null;
    }

    protected function getExtensionFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            return in_array($extension, ['jpg', 'jpeg', 'png', 'webp']) ? $extension : null;
        }
        return null;
    }
}
