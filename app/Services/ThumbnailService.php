<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class ThumbnailService
{
    /**
     * Genera thumbnail automaticamente durante l'upload
     */
    public function generateThumbnailFromUpload(UploadedFile $videoFile, Video $video, int $time = 10): ?string
    {
        try {
            // Verifica se FFmpeg è disponibile
            if (!$this->checkFFmpeg()) {
                Log::warning('FFmpeg non disponibile per generazione thumbnail automatica');
                return null;
            }

            // Crea directory se non esiste
            Storage::disk('public')->makeDirectory('videos/thumbnails');

            // Genera nome file thumbnail
            $thumbnailFilename = "videos/thumbnails/{$video->id}_auto.jpg";
            $thumbnailPath = Storage::disk('public')->path($thumbnailFilename);

            // Genera thumbnail usando FFmpeg
            $command = "ffmpeg -i \"{$videoFile->getPathname()}\" -ss {$time} -vframes 1 -q:v 2 \"{$thumbnailPath}\" -y";

            $result = Process::run($command);

            if ($result->successful() && file_exists($thumbnailPath)) {
                Log::info("Thumbnail generata automaticamente per video {$video->id}: {$thumbnailFilename}");
                return $thumbnailFilename;
            }

            Log::warning("Fallimento generazione thumbnail per video {$video->id}: " . $result->output());
            return null;

        } catch (\Exception $e) {
            Log::error("Errore generazione thumbnail per video {$video->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Genera thumbnail da file video esistente
     */
    public function generateThumbnailFromFile(Video $video, int $time = 10): ?string
    {
        try {
            if (!$this->checkFFmpeg()) {
                return null;
            }

            // Trova il file video
            $videoPath = $this->getVideoPath($video);
            if (!$videoPath) {
                Log::warning("File video non trovato per generazione thumbnail: video {$video->id}");
                return null;
            }

            // Crea directory se non esiste
            Storage::disk('public')->makeDirectory('videos/thumbnails');

            // Genera nome file thumbnail
            $thumbnailFilename = "videos/thumbnails/{$video->id}_generated.jpg";
            $thumbnailPath = Storage::disk('public')->path($thumbnailFilename);

            // Genera thumbnail
            $command = "ffmpeg -i \"{$videoPath}\" -ss {$time} -vframes 1 -q:v 2 \"{$thumbnailPath}\" -y";

            $result = Process::run($command);

            if ($result->successful() && file_exists($thumbnailPath)) {
                Log::info("Thumbnail generata da file per video {$video->id}: {$thumbnailFilename}");
                return $thumbnailFilename;
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Errore generazione thumbnail da file per video {$video->id}: " . $e->getMessage());
            return null;
        }
    }

        /**
     * Scarica thumbnail da URL esterna
     */
    public function downloadThumbnailFromUrl(string $url, Video $video, string $source = 'external'): ?string
    {
        try {
            Log::info("Tentativo download da URL: {$url}");

            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($url);

            if ($response->successful()) {
                // Crea directory se non esiste
                Storage::disk('public')->makeDirectory('videos/thumbnails');

                // Determina estensione
                $extension = $this->getExtensionFromUrl($url) ?: 'jpg';
                $filename = "videos/thumbnails/{$video->id}_{$source}.{$extension}";

                Storage::disk('public')->put($filename, $response->body());

                Log::info("✅ Thumbnail scaricata per video {$video->id}: {$filename}");
                return $filename;
            } else {
                Log::warning("❌ Download fallito per video {$video->id}. Status: {$response->status()}, URL: {$url}");
                return null;
            }

        } catch (\Exception $e) {
            Log::error("❌ Errore download thumbnail per video {$video->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Genera thumbnail per video PeerTube
     */
    public function generatePeerTubeThumbnail(Video $video): ?string
    {
        // Se c'è già un URL thumbnail PeerTube, scaricalo
        if ($video->peertube_thumbnail_url) {
            return $this->downloadThumbnailFromUrl($video->peertube_thumbnail_url, $video, 'peertube');
        }

        // Se non c'è URL thumbnail ma c'è ID PeerTube, prova a recuperarlo
        if ($video->peertube_id && !$video->peertube_thumbnail_url) {
            $thumbnailUrl = $this->getPeerTubeThumbnailUrl($video);
            if ($thumbnailUrl) {
                return $this->downloadThumbnailFromUrl($thumbnailUrl, $video, 'peertube');
            }
        }

        // Se c'è un file video locale, genera thumbnail
        if ($video->file_path) {
            return $this->generateThumbnailFromFile($video);
        }

        return null;
    }

    /**
     * Recupera l'URL della thumbnail da PeerTube
     */
    protected function getPeerTubeThumbnailUrl(Video $video): ?string
    {
        try {
            if (!$video->peertube_id) {
                return null;
            }

            // Costruisci l'URL dell'API PeerTube
            $peerTubeUrl = config('services.peertube.url', 'https://video.slamin.it');
            $apiUrl = rtrim($peerTubeUrl, '/') . '/api/v1/videos/' . $video->peertube_id;

            Log::info("Tentativo recupero thumbnail PeerTube per video {$video->id}: {$apiUrl}");

            $response = \Illuminate\Support\Facades\Http::timeout(30)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['thumbnailPath'])) {
                    $thumbnailUrl = rtrim($peerTubeUrl, '/') . $data['thumbnailPath'];
                    Log::info("✅ Thumbnail URL recuperata per video {$video->id}: {$thumbnailUrl}");

                    // Aggiorna il video con l'URL della thumbnail
                    $video->update(['peertube_thumbnail_url' => $thumbnailUrl]);

                    return $thumbnailUrl;
                } else {
                    Log::warning("❌ Thumbnail path non trovato nella risposta PeerTube per video {$video->id}");
                }
            } else {
                Log::warning("❌ API PeerTube non raggiungibile per video {$video->id}. Status: {$response->status()}");
            }

            return null;

        } catch (\Exception $e) {
            Log::error("❌ Errore recupero thumbnail PeerTube per video {$video->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Genera thumbnail per video YouTube/Vimeo
     */
    public function generateExternalVideoThumbnail(Video $video): ?string
    {
        // YouTube
        if (strpos($video->video_url, 'youtube') !== false || strpos($video->video_url, 'youtu.be') !== false) {
            $videoId = $video->video_id;
            if ($videoId) {
                $youtubeThumbnail = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
                return $this->downloadThumbnailFromUrl($youtubeThumbnail, $video, 'youtube');
            }
        }

        // Vimeo (richiederebbe API)
        if (strpos($video->video_url, 'vimeo') !== false) {
            // Per ora restituiamo null, potrebbe essere implementato con API Vimeo
            return null;
        }

        return null;
    }

    /**
     * Verifica se FFmpeg è disponibile
     */
    protected function checkFFmpeg(): bool
    {
        try {
            $result = Process::run('ffmpeg -version');
            $isAvailable = $result->successful();

            if (!$isAvailable) {
                Log::warning('FFmpeg non disponibile: ' . $result->output());
            }

            return $isAvailable;
        } catch (\Exception $e) {
            Log::warning('FFmpeg non trovato nel PATH: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Trova il percorso del file video
     */
    protected function getVideoPath(Video $video): ?string
    {
        $paths = [
            Storage::disk('public')->path($video->file_path),
            Storage::disk('local')->path($video->file_path),
            $video->file_path, // Percorso assoluto
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Estrae estensione da URL
     */
    protected function getExtensionFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            return in_array($extension, ['jpg', 'jpeg', 'png', 'webp']) ? $extension : null;
        }
        return null;
    }

    /**
     * Crea una thumbnail placeholder quando non è possibile generarne una
     */
    public function createPlaceholderThumbnail(Video $video): string
    {
        try {
            // Crea directory se non esiste
            Storage::disk('public')->makeDirectory('videos/thumbnails');

            // Usa un placeholder esistente dal progetto
            $placeholderPath = 'assets/images/placeholder/placeholder-1.jpg';
            $placeholderFullPath = public_path($placeholderPath);

            if (file_exists($placeholderFullPath)) {
                // Copia il placeholder
                $thumbnailFilename = "videos/thumbnails/{$video->id}_placeholder.jpg";
                Storage::disk('public')->put($thumbnailFilename, file_get_contents($placeholderFullPath));

                Log::info("Placeholder thumbnail creata per video {$video->id}: {$thumbnailFilename}");
                return $thumbnailFilename;
            } else {
                // Se il placeholder non esiste, crea un file vuoto con percorso placeholder
                $thumbnailFilename = "videos/thumbnails/{$video->id}_placeholder.jpg";
                Log::info("Placeholder thumbnail fallback per video {$video->id}: {$thumbnailFilename}");
                return $thumbnailFilename;
            }

        } catch (\Exception $e) {
            Log::error("Errore creazione placeholder per video {$video->id}: " . $e->getMessage());
            return 'placeholder/placeholder-1.jpg'; // Fallback assoluto
        }
    }

    /**
     * Genera thumbnail con fallback a placeholder
     */
    public function generateThumbnailWithFallback(Video $video): string
    {
        $thumbnailPath = null;
        $attempts = [];

        // 1. Prova PeerTube (incluso recupero URL)
        if ($video->peertube_id || $video->peertube_thumbnail_url) {
            Log::info("Tentativo PeerTube per video {$video->id}");
            $thumbnailPath = $this->generatePeerTubeThumbnail($video);
            if ($thumbnailPath) {
                Log::info("✅ Thumbnail PeerTube generata per video {$video->id}: {$thumbnailPath}");
                return $thumbnailPath;
            } else {
                $attempts[] = 'PeerTube fallito';
            }
        } else {
            $attempts[] = 'PeerTube ID/URL mancante';
        }

        // 2. Prova generazione da file locale
        if ($video->file_path) {
            Log::info("Tentativo file locale per video {$video->id}: {$video->file_path}");
            $thumbnailPath = $this->generateThumbnailFromFile($video);
            if ($thumbnailPath) {
                Log::info("✅ Thumbnail file locale generata per video {$video->id}: {$thumbnailPath}");
                return $thumbnailPath;
            } else {
                $attempts[] = 'File locale fallito';
            }
        } else {
            $attempts[] = 'File locale mancante';
        }

        // 3. Prova video esterni (YouTube/Vimeo)
        if ($video->video_url) {
            Log::info("Tentativo video esterno per video {$video->id}: {$video->video_url}");
            $thumbnailPath = $this->generateExternalVideoThumbnail($video);
            if ($thumbnailPath) {
                Log::info("✅ Thumbnail video esterno generata per video {$video->id}: {$thumbnailPath}");
                return $thumbnailPath;
            } else {
                $attempts[] = 'Video esterno fallito';
            }
        } else {
            $attempts[] = 'Video URL mancante';
        }

        // 4. Se tutto fallisce, usa placeholder
        Log::warning("Tutti i tentativi falliti per video {$video->id}. Tentativi: " . implode(', ', $attempts));
        $thumbnailPath = $this->createPlaceholderThumbnail($video);
        Log::info("🔄 Placeholder creato per video {$video->id}: {$thumbnailPath}");

        return $thumbnailPath;
    }
}
