<?php

namespace App\Observers;

use App\Models\Video;
use App\Services\ThumbnailService;
use App\Jobs\GenerateVideoThumbnailJob;
use Illuminate\Support\Facades\Log;

class VideoObserver
{
    protected $thumbnailService;

    public function __construct(ThumbnailService $thumbnailService)
    {
        $this->thumbnailService = $thumbnailService;
    }

        /**
     * Handle the Video "created" event.
     */
    public function created(Video $video): void
    {
        Log::info("Video created: {$video->id} - {$video->title}");

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
            $files = \Illuminate\Support\Facades\Storage::disk('public')->glob($pattern);

            foreach ($files as $file) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
                Log::info("Additional thumbnail deleted: {$file}");
            }

        } catch (\Exception $e) {
            Log::error("Error deleting thumbnails for video {$video->id}: " . $e->getMessage());
        }
    }
}
