<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;

class TestThumbnails extends Command
{
    protected $signature = 'test:thumbnails {video-id?}';
    protected $description = 'Testa le thumbnail dei video';

    public function handle()
    {
        $videoId = $this->argument('video-id');

        $this->info('🖼️  TEST THUMBNAIL VIDEO');
        $this->info('==========================');
        $this->newLine();

        $query = Video::query();
        
        if ($videoId) {
            $query->where('id', $videoId);
            $this->info("Testando video ID: {$videoId}");
        }

        $videos = $query->latest()->get();

        if ($videos->isEmpty()) {
            $this->info('Nessun video trovato');
            return 0;
        }

        foreach ($videos as $video) {
            $this->info("Video ID: {$video->id}");
            $this->info("  Titolo: {$video->title}");
            $this->info("  thumbnail_path: " . ($video->thumbnail_path ?: 'NULL'));
            $this->info("  peertube_thumbnail_url: " . ($video->peertube_thumbnail_url ?: 'NULL'));
            $this->info("  thumbnail_url (accessor): " . $video->thumbnail_url);
            $this->info("  PeerTube UUID: " . ($video->peertube_uuid ?: 'NULL'));
            $this->newLine();
        }

        return 0;
    }
} 