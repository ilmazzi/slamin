<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class CleanupDeletedPeerTubeVideos extends Command
{
    protected $signature = 'peertube:cleanup-deleted {--dry-run} {--force}';
    protected $description = 'Elimina da PeerTube tutti i video marcati come deleted nel database locale';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        if ($dryRun) {
            $this->info('🔍 Modalità dry-run: nessuna eliminazione verrà eseguita');
        }

        $videos = Video::where('peertube_status', 'deleted')
            ->whereNotNull('peertube_uuid')
            ->get();

        if ($videos->isEmpty()) {
            $this->info('✅ Nessun video marcato come deleted trovato');
            return 0;
        }

        $this->info("🔄 Trovati {$videos->count()} video marcati come deleted");

        if (!$dryRun && !$force && !$this->confirm("Vuoi eliminare {$videos->count()} video da PeerTube?")) {
            $this->info('❌ Operazione annullata');
            return 0;
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($videos as $video) {
            $this->line("📹 Elaborazione video: {$video->title} (ID: {$video->id}, UUID: {$video->peertube_uuid})");

            if ($dryRun) {
                $this->line("   🔍 In modalità dry-run, questo video verrebbe eliminato da PeerTube");
                continue;
            }

            try {
                $peerTubeService = new PeerTubeService();
                $success = $peerTubeService->deleteVideoByUuid($video->peertube_uuid);

                if ($success) {
                    $this->info("   ✅ Video eliminato con successo da PeerTube");
                    $successCount++;
                    
                    Log::info("Video PeerTube eliminato durante cleanup", [
                        'video_id' => $video->id,
                        'title' => $video->title,
                        'peertube_uuid' => $video->peertube_uuid,
                        'command' => 'peertube:cleanup-deleted'
                    ]);
                } else {
                    $this->warn("   ⚠️ Errore durante l'eliminazione del video da PeerTube");
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $this->error("   ❌ Errore: " . $e->getMessage());
                $errorCount++;
                
                Log::error("Errore eliminazione video PeerTube durante cleanup", [
                    'video_id' => $video->id,
                    'peertube_uuid' => $video->peertube_uuid,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("📊 Risultati:");
        $this->info("   ✅ Video eliminati con successo: {$successCount}");
        $this->info("   ❌ Errori: {$errorCount}");

        if ($successCount > 0) {
            $this->info("🎉 Cleanup completato con successo!");
        }

        return 0;
    }
} 
