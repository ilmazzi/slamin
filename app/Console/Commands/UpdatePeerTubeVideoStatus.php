<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class UpdatePeerTubeVideoStatus extends Command
{
    protected $signature = 'peertube:update-status {--video-id=} {--all}';
    protected $description = 'Aggiorna lo stato dei video PeerTube controllando l\'API';

    public function handle()
    {
        $videoId = $this->option('video-id');
        $updateAll = $this->option('all');

        if ($videoId) {
            $this->updateSingleVideo($videoId);
        } elseif ($updateAll) {
            $this->updateAllVideos();
        } else {
            $this->error('Specifica --video-id=N o --all');
            return 1;
        }

        return 0;
    }

    private function updateSingleVideo($videoId)
    {
        $video = Video::find($videoId);
        if (!$video) {
            $this->error("Video non trovato con ID: {$videoId}");
            return;
        }

        $this->info("🔄 Aggiornamento stato video ID: {$videoId} - {$video->title}");
        $this->updateVideoStatus($video);
    }

    private function updateAllVideos()
    {
        $videos = Video::where('peertube_status', 'processing')
            ->whereNotNull('peertube_uuid')
            ->get();

        $this->info("🔄 Trovati {$videos->count()} video in elaborazione");

        foreach ($videos as $video) {
            $this->info("📹 Controllo video: {$video->title} (ID: {$video->id})");
            $this->updateVideoStatus($video);
        }
    }

    private function updateVideoStatus(Video $video)
    {
        try {
            $peerTubeService = new PeerTubeService();
            $baseUrl = $peerTubeService->getBaseUrl();

            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->get($baseUrl . '/api/v1/videos/' . $video->peertube_uuid);

            if ($response->successful()) {
                $data = $response->json();

                // Controlla se il video è pronto
                $hasFiles = !empty($data['files']);
                $hasStreamingPlaylists = !empty($data['streamingPlaylists']);

                if ($hasFiles || $hasStreamingPlaylists) {
                    $oldStatus = $video->peertube_status;
                    $video->update(['peertube_status' => 'ready']);

                    $this->info("✅ Video {$video->id} aggiornato: {$oldStatus} → ready");
                    Log::info("Video PeerTube aggiornato automaticamente", [
                        'video_id' => $video->id,
                        'old_status' => $oldStatus,
                        'new_status' => 'ready'
                    ]);
                } else {
                    $this->info("⏳ Video {$video->id} ancora in elaborazione");
                }
            } else {
                $this->warn("⚠️ Errore API per video {$video->id}: {$response->status()}");
            }
        } catch (\Exception $e) {
            $this->error("❌ Errore per video {$video->id}: {$e->getMessage()}");
            Log::error("Errore aggiornamento stato video PeerTube", [
                'video_id' => $video->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
