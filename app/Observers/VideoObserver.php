<?php

namespace App\Observers;

use App\Models\Video;
use App\Services\ThumbnailService;
use App\Services\ActivityService;
use App\Services\BadgeService;
use App\Jobs\GenerateVideoThumbnailJob;
use App\Jobs\UpdatePeerTubeVideoStatusJob;
use Illuminate\Support\Facades\Log;

class VideoObserver
{
    protected $thumbnailService;
    protected $badgeService;

    public function __construct(ThumbnailService $thumbnailService, BadgeService $badgeService)
    {
        $this->thumbnailService = $thumbnailService;
        $this->badgeService = $badgeService;
    }

        /**
     * Handle the Video "created" event.
     */
    public function created(Video $video): void
    {
        Log::info("Video created: {$video->id} - {$video->title}");

        // Log activity
        if ($video->user) {
            ActivityService::logCreate($video->user, $video, request());
            
            // Check and award badges for videos
            $this->badgeService->checkAndAwardBadge($video->user, 'videos', $video);
        }

        // Se è un video PeerTube in elaborazione, lancia il job di controllo
        if ($video->peertube_uuid && $video->peertube_status === 'processing') {
            Log::info("🔄 Lanciando job controllo stato PeerTube per video {$video->id}");
            UpdatePeerTubeVideoStatusJob::dispatch($video)->delay(now()->addMinutes(1));
        }

        // Genera thumbnail automaticamente se non esiste (in background)
        if (!$video->thumbnail_path) {
            GenerateVideoThumbnailJob::dispatch($video)->delay(now()->addSeconds(5));
        }
    }

        /**
     * Handle the Video "updated" event.
     */
    public function updated(Video $video): void
    {
        Log::info("Video updated: {$video->id} - {$video->title}");

        // Log activity for important changes
        if ($video->user && $video->wasChanged(['title', 'description', 'status', 'moderation_status'])) {
            ActivityService::logUpdate($video->user, $video, request());
        }

        // Se il video è stato approvato e non ha thumbnail, generane una
        if ($video->wasChanged('moderation_status') &&
            $video->moderation_status === 'approved' &&
            !$video->thumbnail_path) {
            GenerateVideoThumbnailJob::dispatch($video)->delay(now()->addSeconds(5));
        }
    }

    /**
     * Handle the Video "deleted" event.
     */
    public function deleted(Video $video): void
    {
        Log::info("Video deleted: {$video->id} - {$video->title}");

        // Log activity before deletion
        if ($video->user) {
            ActivityService::logDelete(
                $video->user,
                'App\\Models\\Video',
                $video->id,
                $video->title ?? 'Video',
                request()
            );
        }

        // Elimina le thumbnail associate
        $this->deleteThumbnails($video);
    }

        /**
     * Genera thumbnail per il video
     */
    protected function generateThumbnail(Video $video): void
    {
        try {
            // Usa il metodo con fallback che garantisce sempre una thumbnail
            $thumbnailPath = $this->thumbnailService->generateThumbnailWithFallback($video);

            $video->update(['thumbnail_path' => $thumbnailPath]);
            Log::info("Thumbnail generated for video {$video->id}: {$thumbnailPath}");

        } catch (\Exception $e) {
            Log::error("Error generating thumbnail for video {$video->id}: " . $e->getMessage());
        }
    }

    /**
     * Elimina le thumbnail associate al video
     */
    protected function deleteThumbnails(Video $video): void
    {
        try {
            if ($video->thumbnail_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($video->thumbnail_path);
                Log::info("Thumbnail deleted for video {$video->id}: {$video->thumbnail_path}");
            }

            // Elimina anche eventuali thumbnail con pattern matching
            $pattern = "videos/thumbnails/{$video->id}_*";
            $files = \Illuminate\Support\Facades\Storage::disk('public')->files('videos/thumbnails');

            // Filtra i file che corrispondono al pattern
            $matchingFiles = array_filter($files, function($file) use ($video) {
                return strpos($file, "videos/thumbnails/{$video->id}_") === 0;
            });

            foreach ($matchingFiles as $file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
                Log::info("Additional thumbnail deleted: {$file}");
            }

        } catch (\Exception $e) {
            Log::error("Error deleting thumbnails for video {$video->id}: " . $e->getMessage());
        }
    }
}
