<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class CleanupInvalidPeerTubeVideos extends Command
{
    protected $signature = 'peertube:cleanup-invalid {--dry-run}';
    protected $description = 'Pulisce i video PeerTube con UUID non validi';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 Modalità dry-run: nessuna modifica verrà applicata');
        }

        $videos = Video::where('peertube_status', 'processing')
            ->whereNotNull('peertube_uuid')
            ->get();

        $this->info("🔄 Controllando {$videos->count()} video PeerTube in elaborazione");

        $invalidVideos = [];
        $validVideos = [];

        foreach ($videos as $video) {
            $this->info("📹 Controllo video: {$video->title} (ID: {$video->id}, UUID: {$video->peertube_uuid})");
            
            if ($this->isVideoValid($video)) {
                $validVideos[] = $video;
                $this->info("✅ Video {$video->id} valido");
            } else {
                $invalidVideos[] = $video;
                $this->warn("❌ Video {$video->id} non valido (404)");
            }
        }

        $this->info("📊 Risultati:");
        $this->info("   ✅ Video validi: " . count($validVideos));
        $this->info("   ❌ Video non validi: " . count($invalidVideos));

        if (count($invalidVideos) > 0) {
            if ($dryRun) {
                $this->warn("🔄 In modalità dry-run, questi video verrebbero marcati come 'deleted':");
                foreach ($invalidVideos as $video) {
                    $this->line("   - {$video->title} (ID: {$video->id})");
                }
            } else {
                if ($this->confirm('Vuoi marcare questi video come eliminati?')) {
                    foreach ($invalidVideos as $video) {
                        $video->update(['peertube_status' => 'deleted']);
                        $this->info("🗑️ Video {$video->id} marcato come eliminato");
                        Log::info("Video PeerTube marcato come eliminato", [
                            'video_id' => $video->id,
                            'title' => $video->title,
                            'peertube_uuid' => $video->peertube_uuid
                        ]);
                    }
                }
            }
        }

        return 0;
    }

    private function isVideoValid(Video $video): bool
    {
        try {
            $peerTubeService = new PeerTubeService();
            $baseUrl = $peerTubeService->getBaseUrl();

            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->get($baseUrl . '/api/v1/videos/' . $video->peertube_uuid);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
} 
