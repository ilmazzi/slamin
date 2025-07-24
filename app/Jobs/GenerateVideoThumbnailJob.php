<?php

namespace App\Jobs;

use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateVideoThumbnailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;
    public $timeout = 300; // 5 minuti
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

        /**
     * Execute the job.
     */
    public function handle(ThumbnailService $thumbnailService): void
    {
        Log::info("Starting thumbnail generation job for video {$this->video->id}");

        try {
            // Se il video ha già un URL PeerTube valido, non sovrascriverlo
            if ($this->video->thumbnail_path && filter_var($this->video->thumbnail_path, FILTER_VALIDATE_URL)) {
                Log::info("Video {$this->video->id} ha già un URL thumbnail valido: {$this->video->thumbnail_path}");
                return;
            }

            // Usa il metodo con fallback che garantisce sempre una thumbnail
            $thumbnailPath = $thumbnailService->generateThumbnailWithFallback($this->video);

            $this->video->update(['thumbnail_path' => $thumbnailPath]);
            Log::info("Thumbnail generated successfully for video {$this->video->id}: {$thumbnailPath}");

        } catch (\Exception $e) {
            Log::error("Error in thumbnail generation job for video {$this->video->id}: " . $e->getMessage());
            throw $e; // Rilancia l'eccezione per permettere i retry
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Thumbnail generation job failed for video {$this->video->id}: " . $exception->getMessage());
    }
}
