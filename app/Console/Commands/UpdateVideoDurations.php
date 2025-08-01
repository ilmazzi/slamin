<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;

class UpdateVideoDurations extends Command
{
    protected $signature = 'videos:update-durations {video_id?}';
    protected $description = 'Update video durations from PeerTube API';

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
            $this->updateVideoDuration($video, $thumbnailService);
        } else {
            $videos = Video::whereNull('duration')
                          ->where(function($query) {
                              $query->whereNotNull('peertube_id')
                                    ->orWhereNotNull('peertube_video_id')
                                    ->orWhereNotNull('peertube_uuid');
                          })
                          ->get();

            if ($videos->isEmpty()) {
                $this->info("Nessun video senza durata trovato!");
                return 0;
            }

            $this->info("Trovati {$videos->count()} video senza durata");
            
            $bar = $this->output->createProgressBar($videos->count());
            $bar->start();

            foreach ($videos as $video) {
                $this->updateVideoDuration($video, $thumbnailService);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
        }

        return 0;
    }

    private function updateVideoDuration(Video $video, ThumbnailService $thumbnailService)
    {
        $this->line("Updating duration for video ID: {$video->id} - {$video->title}");
        
        try {
            $duration = $thumbnailService->getPeerTubeVideoDuration($video);
            if ($duration) {
                $this->info("✅ Duration updated: {$duration} seconds");
            } else {
                $this->warn("⚠️ Could not retrieve duration");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }
} 