<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FixPeerTubeThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:peertube-thumbnails {--video-id= : ID specifico del video da correggere}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregge le thumbnail PeerTube che sono state salvate come percorsi locali invece di URL';

    /**
     * Execute the console command.
     */
    public function handle(ThumbnailService $thumbnailService)
    {
        $this->info('🔧 CORREZIONE THUMBNAIL PEERTUBE');
        $this->info('================================');

        $videoId = $this->option('video-id');
        
        if ($videoId) {
            $videos = Video::where('id', $videoId)->get();
        } else {
            $videos = Video::whereNotNull('peertube_id')
                          ->orWhereNotNull('peertube_uuid')
                          ->get();
        }

        $this->info("Trovati {$videos->count()} video PeerTube da controllare");

        $fixed = 0;
        $skipped = 0;

        foreach ($videos as $video) {
            $this->line("📹 Video ID: {$video->id} - {$video->title}");
            
            // Controlla se ha già un URL PeerTube valido
            if ($video->thumbnail_path && filter_var($video->thumbnail_path, FILTER_VALIDATE_URL)) {
                $this->line("  ✅ Già corretto: {$video->thumbnail_path}");
                $skipped++;
                continue;
            }

            // Controlla se ha un URL PeerTube specifico
            if ($video->peertube_thumbnail_url) {
                $this->line("  🔄 Aggiornando con URL PeerTube: {$video->peertube_thumbnail_url}");
                $video->update(['thumbnail_path' => $video->peertube_thumbnail_url]);
                $fixed++;
                continue;
            }

            // Prova a recuperare l'URL dalla API PeerTube
            if ($video->peertube_id) {
                $this->line("  🔍 Recuperando URL da PeerTube API...");
                
                try {
                    $thumbnailUrl = $thumbnailService->getPeerTubeThumbnailUrl($video);
                    if ($thumbnailUrl) {
                        $this->line("  ✅ URL recuperato: {$thumbnailUrl}");
                        $video->update(['thumbnail_path' => $thumbnailUrl]);
                        $fixed++;
                    } else {
                        $this->line("  ❌ Impossibile recuperare URL PeerTube");
                        $skipped++;
                    }
                } catch (\Exception $e) {
                    $this->line("  ❌ Errore: " . $e->getMessage());
                    $skipped++;
                }
            } else {
                $this->line("  ⚠️  Nessun ID PeerTube trovato");
                $skipped++;
            }
            
            $this->line('');
        }

        $this->info("🎉 CORREZIONE COMPLETATA!");
        $this->info("✅ Corretti: {$fixed}");
        $this->info("⏭️  Saltati: {$skipped}");
        $this->info("📊 Totale: " . ($fixed + $skipped));
    }
}
