<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;

class GenerateVideoThumbnails extends Command
{
    protected $signature = 'videos:generate-thumbnails {--video-id= : ID specifico del video} {--all : Genera tutte le thumbnail} {--time=10 : Secondo da cui estrarre la thumbnail}';
    protected $description = 'Genera thumbnail dai video usando FFmpeg';

    public function handle()
    {
        $this->info('=== GENERAZIONE THUMBNAIL VIDEO ===');

        // Verifica se FFmpeg è installato
        if (!$this->checkFFmpeg()) {
            $this->error('FFmpeg non è installato o non è disponibile nel PATH');
            return 1;
        }

        if ($videoId = $this->option('video-id')) {
            $videos = Video::where('id', $videoId)->get();
        } elseif ($this->option('all')) {
            $videos = Video::where('moderation_status', 'approved')->get();
        } else {
            $this->error('Specifica --video-id=X o --all');
            return 1;
        }

        $this->info("Trovati {$videos->count()} video da processare");
        $time = $this->option('time');

        $success = 0;
        $errors = 0;

        foreach ($videos as $video) {
            $this->line("\n--- Video ID: {$video->id} ---");
            $this->line("Titolo: {$video->title}");

            try {
                $thumbnailPath = $this->generateThumbnail($video, $time);
                if ($thumbnailPath) {
                    $video->update(['thumbnail_path' => $thumbnailPath]);
                    $this->line("✅ Thumbnail generata: {$thumbnailPath}");
                    $success++;
                } else {
                    $this->warn("⚠️ Impossibile generare thumbnail");
                    $errors++;
                }
            } catch (\Exception $e) {
                $this->error("❌ Errore: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n=== RISULTATI ===");
        $this->info("Successi: {$success}");
        $this->info("Errori: {$errors}");
        $this->info("=== FINE GENERAZIONE ===");
    }

    protected function checkFFmpeg(): bool
    {
        $result = Process::run('ffmpeg -version');
        return $result->successful();
    }

    protected function generateThumbnail(Video $video, int $time): ?string
    {
        // Determina il percorso del video
        $videoPath = $this->getVideoPath($video);
        if (!$videoPath) {
            $this->warn("Video file non trovato per video ID: {$video->id}");
            return null;
        }

        // Genera il nome del file thumbnail
        $thumbnailFilename = "videos/thumbnails/{$video->id}_generated.jpg";
        $thumbnailPath = Storage::disk('public')->path($thumbnailFilename);

        // Assicurati che la directory esista
        Storage::disk('public')->makeDirectory('videos/thumbnails');

        // Comando FFmpeg per generare thumbnail
        $command = "ffmpeg -i \"{$videoPath}\" -ss {$time} -vframes 1 -q:v 2 \"{$thumbnailPath}\" -y";

        $result = Process::run($command);

        if ($result->successful() && file_exists($thumbnailPath)) {
            return $thumbnailFilename;
        }

        $this->warn("FFmpeg output: " . $result->output());
        return null;
    }

    protected function getVideoPath(Video $video): ?string
    {
        // Prova diversi percorsi possibili
        $paths = [
            Storage::disk('public')->path($video->video_path),
            Storage::disk('local')->path($video->video_path),
            $video->video_path, // Percorso assoluto
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
