<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;

class TestVideoList extends Command
{
    protected $signature = 'test:video-list {user-id?}';
    protected $description = 'Lista i video nel database';

    public function handle()
    {
        $userId = $this->argument('user-id');

        $this->info('📹 LISTA VIDEO NEL DATABASE');
        $this->info('============================');
        $this->newLine();

        $query = Video::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
            $this->info("Filtrando per utente ID: {$userId}");
        }

        $videos = $query->latest()->get();

        if ($videos->isEmpty()) {
            $this->info('Nessun video trovato');
            return 0;
        }

        $this->info("Trovati {$videos->count()} video:");
        $this->newLine();

        foreach ($videos as $video) {
            $this->info("ID: {$video->id}");
            $this->info("  Titolo: {$video->title}");
            $this->info("  Utente: {$video->user_id}");
            $this->info("  PeerTube ID: " . ($video->peertube_id ?: 'N/A'));
            $this->info("  PeerTube UUID: " . ($video->peertube_uuid ?: 'N/A'));
            $this->info("  URL: " . ($video->peertube_url ?: 'N/A'));
            $this->info("  Upload Status: {$video->upload_status}");
            $this->info("  Creato: {$video->created_at}");
            $this->newLine();
        }

        return 0;
    }
} 