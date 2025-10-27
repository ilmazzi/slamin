<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncPeerTubeThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:sync-thumbnails {--force : Force sync even if thumbnail exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync thumbnails from PeerTube API for all videos with peertube_uuid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎬 Sincronizzazione thumbnail PeerTube...');
        
        $peertubeUrl = config('services.peertube.url', 'https://video.slamin.it');
        
        if (empty($peertubeUrl)) {
            $this->error('❌ URL PeerTube non configurato in config/services.php!');
            return 1;
        }
        
        $this->info("📡 URL PeerTube: {$peertubeUrl}");
        
        // Trova tutti i video con peertube_uuid
        $videos = \App\Models\Video::whereNotNull('peertube_uuid')->get();
        
        if ($videos->isEmpty()) {
            $this->warn('⚠️  Nessun video con peertube_uuid trovato.');
            return 0;
        }
        
        $this->info("📊 Trovati {$videos->count()} video PeerTube da processare.");
        
        $progressBar = $this->output->createProgressBar($videos->count());
        $progressBar->start();
        
        $synced = 0;
        $skipped = 0;
        $errors = 0;
        
        foreach ($videos as $video) {
            // Skip se ha già thumbnail e non è --force
            if (!$this->option('force') && $video->thumbnail_path && !str_contains($video->thumbnail_path, 'placeholder')) {
                $skipped++;
                $progressBar->advance();
                continue;
            }
            
            try {
                $apiUrl = rtrim($peertubeUrl, '/') . '/api/v1/videos/' . $video->peertube_uuid;
                $response = \Illuminate\Support\Facades\Http::timeout(10)->get($apiUrl);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['thumbnailPath'])) {
                        $thumbnailUrl = rtrim($peertubeUrl, '/') . $data['thumbnailPath'];
                        $video->thumbnail_path = $thumbnailUrl;
                        $video->save();
                        $synced++;
                    } else {
                        $this->newLine();
                        $this->warn("  ⚠️  Video ID {$video->id} ({$video->title}): thumbnailPath non presente nella risposta API");
                        $errors++;
                    }
                } else {
                    $this->newLine();
                    $this->warn("  ❌ Video ID {$video->id}: HTTP {$response->status()}");
                    $errors++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("  ❌ Video ID {$video->id}: {$e->getMessage()}");
                $errors++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ Sincronizzazione completata!");
        $this->info("📊 Statistiche:");
        $this->info("   - Sincronizzati: {$synced}");
        $this->info("   - Saltati: {$skipped}");
        $this->info("   - Errori: {$errors}");
        
        return 0;
    }
}
