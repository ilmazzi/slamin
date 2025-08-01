<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestThumbnailGeneration extends Command
{
    protected $signature = 'test:thumbnail {video_id?}';
    protected $description = 'Test thumbnail generation for a specific video or all videos';

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
            $this->testVideo($video, $thumbnailService);
        } else {
            $videos = Video::whereNotNull('peertube_id')
                          ->orWhereNotNull('peertube_video_id')
                          ->orWhereNotNull('peertube_uuid')
                          ->get();

            if ($videos->isEmpty()) {
                $this->error("Nessun video con ID PeerTube trovato!");
                return 1;
            }

            $this->info("Trovati {$videos->count()} video con ID PeerTube");

            foreach ($videos as $video) {
                $this->testVideo($video, $thumbnailService);
                $this->line('---');
            }
        }

        return 0;
    }

    private function testVideo(Video $video, ThumbnailService $thumbnailService)
    {
        $this->info("Testing video ID: {$video->id}");
        $this->line("Title: {$video->title}");
        $this->line("PeerTube ID: {$video->peertube_id}");
        $this->line("PeerTube Video ID: {$video->peertube_video_id}");
        $this->line("PeerTube UUID: {$video->peertube_uuid}");
        $this->line("Current thumbnail: {$video->thumbnail_path}");
        $this->line("PeerTube thumbnail URL: {$video->peertube_thumbnail_url}");

        $this->info("Generating thumbnail...");

        try {
            $result = $thumbnailService->generateThumbnailWithFallback($video);
            $this->info("✅ Result: {$result}");

            // Refresh the video to get updated data
            $video->refresh();
            $this->line("Updated thumbnail: {$video->thumbnail_path}");

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }
}
