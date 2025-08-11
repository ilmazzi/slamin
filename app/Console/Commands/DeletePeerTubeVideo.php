<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class DeletePeerTubeVideo extends Command
{
    protected $signature = 'peertube:delete-video {video_id} {--force}';
    protected $description = 'Elimina un video da PeerTube usando l\'ID del video locale';

    public function handle()
    {
        $videoId = $this->argument('video_id');
        $force = $this->option('force');

        $video = Video::find($videoId);
        
        if (!$video) {
            $this->error("❌ Video con ID {$videoId} non trovato nel database locale");
            return 1;
        }

        if (!$video->peertube_uuid) {
            $this->warn("⚠️ Video {$videoId} non ha UUID PeerTube associato");
            return 1;
        }

        $this->info("📹 Video trovato:");
        $this->line("   ID: {$video->id}");
        $this->line("   Titolo: {$video->title}");
        $this->line("   PeerTube UUID: {$video->peertube_uuid}");
        $this->line("   Status: {$video->peertube_status}");

        if (!$force && !$this->confirm('Sei sicuro di voler eliminare questo video da PeerTube?')) {
            $this->info("❌ Operazione annullata");
            return 0;
        }

        try {
            $peerTubeService = new PeerTubeService();
            $success = $peerTubeService->deleteVideoByUuid($video->peertube_uuid);

            if ($success) {
                $this->info("✅ Video eliminato con successo da PeerTube");
                
                // Aggiorna lo status nel database locale
                $video->update(['peertube_status' => 'deleted']);
                $this->info("📝 Status aggiornato a 'deleted' nel database locale");
                
                Log::info("Video PeerTube eliminato manualmente", [
                    'video_id' => $video->id,
                    'title' => $video->title,
                    'peertube_uuid' => $video->peertube_uuid,
                    'command' => 'peertube:delete-video'
                ]);
            } else {
                $this->error("❌ Errore durante l'eliminazione del video da PeerTube");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Errore: " . $e->getMessage());
            Log::error("Errore eliminazione video PeerTube", [
                'video_id' => $video->id,
                'peertube_uuid' => $video->peertube_uuid,
                'error' => $e->getMessage()
            ]);
            return 1;
        }

        return 0;
    }
} 
