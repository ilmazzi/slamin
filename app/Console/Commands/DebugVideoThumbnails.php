<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;

class DebugVideoThumbnails extends Command
{
    protected $signature = 'debug:video-thumbnails {--video-id= : ID specifico del video}';
    protected $description = 'Debug dei video per capire perché non vengono generate le thumbnail';

    public function handle()
    {
        $this->info('=== DEBUG VIDEO THUMBNAILS ===');

        if ($videoId = $this->option('video-id')) {
            $videos = Video::where('id', $videoId)->get();
        } else {
            $videos = Video::where('moderation_status', 'approved')->get();
        }

        foreach ($videos as $video) {
            $this->line("\n--- Video ID: {$video->id} ---");
            $this->line("Titolo: {$video->title}");
            $this->line("Thumbnail attuale: " . ($video->thumbnail_path ?: 'NULL'));

            // Debug PeerTube
            $this->line("\n🔍 PEERTUBE:");
            $this->line("  peertube_id: " . ($video->peertube_id ?: 'NULL'));
            $this->line("  peertube_uuid: " . ($video->peertube_uuid ?: 'NULL'));
            $this->line("  peertube_url: " . ($video->peertube_url ?: 'NULL'));
            $this->line("  peertube_thumbnail_url: " . ($video->peertube_thumbnail_url ?: 'NULL'));

            // Debug file locale
            $this->line("\n🔍 FILE LOCALE:");
            $this->line("  file_path: " . ($video->file_path ?: 'NULL'));
            $this->line("  video_url: " . ($video->video_url ?: 'NULL'));

            // Debug altri campi
            $this->line("\n🔍 ALTRI CAMPI:");
            $this->line("  thumbnail: " . ($video->thumbnail ?: 'NULL'));
            $this->line("  status: " . ($video->status ?: 'NULL'));
            $this->line("  upload_status: " . ($video->upload_status ?: 'NULL'));

            // Analisi possibilità
            $this->line("\n🔍 POSSIBILITÀ THUMBNAIL:");

            if ($video->peertube_thumbnail_url) {
                $this->line("  ✅ PeerTube thumbnail URL disponibile");
            } else {
                $this->line("  ❌ PeerTube thumbnail URL mancante");
            }

            if ($video->file_path) {
                $this->line("  ✅ File locale disponibile per FFmpeg");
            } else {
                $this->line("  ❌ File locale mancante");
            }

            if ($video->video_url && (strpos($video->video_url, 'youtube') !== false || strpos($video->video_url, 'vimeo') !== false)) {
                $this->line("  ✅ Video esterno (YouTube/Vimeo) disponibile");
            } else {
                $this->line("  ❌ Video esterno non disponibile");
            }

            $this->line("---");
        }
    }
}
