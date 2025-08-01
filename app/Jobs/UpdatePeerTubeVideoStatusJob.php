<?php

namespace App\Jobs;

use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdatePeerTubeVideoStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;
    public $timeout = 60; // 1 minuto
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video = null)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->video) {
            $this->updateSingleVideo($this->video);
        } else {
            $this->updateAllProcessingVideos();
        }
    }

    /**
     * Aggiorna un singolo video
     */
    private function updateSingleVideo(Video $video): void
    {
        Log::info("🔄 Job: Aggiornamento stato video PeerTube", [
            'video_id' => $video->id,
            'title' => $video->title,
            'peertube_status' => $video->peertube_status
        ]);

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
                    
                    // Aggiorna status, durata e thumbnail
                    $updateData = ['peertube_status' => 'ready'];
                    
                    // Aggiorna durata se disponibile
                    if (isset($data['duration']) && $data['duration'] > 0) {
                        $updateData['duration'] = $data['duration'];
                        Log::info("⏱️ Durata aggiornata per video {$video->id}: {$data['duration']} secondi");
                    }
                    
                    // Aggiorna thumbnail se disponibile
                    if (isset($data['thumbnailPath']) && !$video->peertube_thumbnail_url) {
                        $thumbnailUrl = rtrim($baseUrl, '/') . $data['thumbnailPath'];
                        $updateData['peertube_thumbnail_url'] = $thumbnailUrl;
                        Log::info("🖼️ Thumbnail URL aggiornata per video {$video->id}: {$thumbnailUrl}");
                    }
                    
                    $video->update($updateData);

                    Log::info("✅ Video PeerTube aggiornato automaticamente", [
                        'video_id' => $video->id,
                        'old_status' => $oldStatus,
                        'new_status' => 'ready',
                        'duration_updated' => isset($data['duration']),
                        'thumbnail_updated' => isset($data['thumbnailPath'])
                    ]);
                    
                    // Lancia il job per generare thumbnail locale se necessario
                    if (isset($data['thumbnailPath']) && !$video->thumbnail_path) {
                        GenerateVideoThumbnailJob::dispatch($video)->delay(now()->addSeconds(5));
                        Log::info("🔄 Job thumbnail lanciato per video {$video->id}");
                    }
                } else {
                    Log::info("⏳ Video {$video->id} ancora in elaborazione PeerTube");
                    
                    // Rilancia il job tra 2 minuti se ancora in elaborazione
                    if ($video->peertube_status === 'processing') {
                        self::dispatch($video)->delay(now()->addMinutes(2));
                    }
                }
            } else {
                Log::warning("⚠️ Errore API PeerTube per video {$video->id}: {$response->status()}");
            }
        } catch (\Exception $e) {
            Log::error("❌ Errore aggiornamento stato video PeerTube", [
                'video_id' => $video->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Aggiorna tutti i video in elaborazione
     */
    private function updateAllProcessingVideos(): void
    {
        $videos = Video::where('peertube_status', 'processing')
            ->whereNotNull('peertube_uuid')
            ->get();

        Log::info("🔄 Job: Trovati {$videos->count()} video PeerTube in elaborazione");

        foreach ($videos as $video) {
            $this->updateSingleVideo($video);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("❌ Job aggiornamento stato video PeerTube fallito", [
            'video_id' => $this->video?->id,
            'error' => $exception->getMessage()
        ]);
    }
} 