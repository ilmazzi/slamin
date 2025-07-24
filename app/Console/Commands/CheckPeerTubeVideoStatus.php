<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Services\PeerTubeService;
use Exception;

class CheckPeerTubeVideoStatus extends Command
{
    protected $signature = 'peertube:check-video-status {video-id}';
    protected $description = 'Verifica lo stato di un video su PeerTube e aggiorna l\'URL';

    public function handle()
    {
        $videoId = $this->argument('video-id');

        $this->info('🔍 VERIFICA STATO VIDEO PEERTUBE');
        $this->info('==================================');
        $this->newLine();

        // Trova video
        $video = Video::find($videoId);
        if (!$video) {
            $this->error('❌ Video non trovato');
            return 1;
        }

        $this->info('1. Video locale:');
        $this->info('   ID: ' . $video->id);
        $this->info('   Titolo: ' . $video->title);
        $this->info('   PeerTube ID: ' . ($video->peertube_id ?: 'N/A'));
        $this->info('   PeerTube UUID: ' . ($video->peertube_uuid ?: 'N/A'));
        $this->info('   URL attuale: ' . ($video->peertube_url ?: 'N/A'));

        if (!$video->peertube_id) {
            $this->error('   ❌ Video non ha PeerTube ID');
            return 1;
        }

        $this->newLine();

        try {
            $service = new PeerTubeService();

            // Ottieni informazioni video da PeerTube
            $this->info('2. Verifica stato su PeerTube:');
            
            $client = new \GuzzleHttp\Client([
                'timeout' => 30,
            ]);

            $response = $client->get("https://video.slamin.it/api/v1/videos/{$video->peertube_uuid}");
            
            if ($response->getStatusCode() === 200) {
                $videoInfo = json_decode($response->getBody()->getContents(), true);
                
                $this->info('   ✅ Video trovato su PeerTube');
                $this->info('   ID: ' . ($videoInfo['id'] ?? 'N/A'));
                $this->info('   UUID: ' . ($videoInfo['uuid'] ?? 'N/A'));
                $this->info('   Nome: ' . ($videoInfo['name'] ?? 'N/A'));
                $this->info('   Durata: ' . ($videoInfo['duration'] ?? 'N/A') . ' secondi');
                $this->info('   Stato: ' . ($videoInfo['state']['label'] ?? 'N/A'));
                $this->info('   Privacy: ' . ($videoInfo['privacy']['label'] ?? 'N/A'));
                
                // URL del video
                if (isset($videoInfo['embedPath'])) {
                    $videoUrl = "https://video.slamin.it" . $videoInfo['embedPath'];
                    $this->info('   URL Video: ' . $videoUrl);
                    
                    // Aggiorna il database se l'URL è cambiato
                    if ($video->peertube_url !== $videoUrl) {
                        $video->update([
                            'peertube_url' => $videoUrl,
                            'duration' => $videoInfo['duration'] ?? 0,
                            'peertube_embed_url' => $videoUrl,
                        ]);
                        $this->info('   ✅ URL aggiornato nel database');
                    } else {
                        $this->info('   ℹ️  URL già aggiornato');
                    }
                }
                
                // Thumbnail
                if (isset($videoInfo['thumbnailPath'])) {
                    $thumbnailUrl = "https://video.slamin.it" . $videoInfo['thumbnailPath'];
                    $this->info('   Thumbnail: ' . $thumbnailUrl);
                    
                    if ($video->thumbnail_path !== $thumbnailUrl) {
                        $video->update(['thumbnail_path' => $thumbnailUrl]);
                        $this->info('   ✅ Thumbnail aggiornato nel database');
                    }
                }
                
                // Statistiche
                if (isset($videoInfo['views'])) {
                    $this->info('   Visualizzazioni: ' . $videoInfo['views']);
                    $video->update(['view_count' => $videoInfo['views']]);
                }
                
                if (isset($videoInfo['likes'])) {
                    $this->info('   Mi piace: ' . $videoInfo['likes']);
                    $video->update(['like_count' => $videoInfo['likes']]);
                }
                
                if (isset($videoInfo['dislikes'])) {
                    $this->info('   Non mi piace: ' . $videoInfo['dislikes']);
                    $video->update(['dislike_count' => $videoInfo['dislikes']]);
                }
                
            } else {
                $this->error('   ❌ Errore nel recupero video: ' . $response->getStatusCode());
                return 1;
            }

            $this->newLine();
            $this->info('✅ Verifica completata!');
            return 0;

        } catch (Exception $e) {
            $this->error('   ❌ Errore durante la verifica: ' . $e->getMessage());
            return 1;
        }
    }
} 