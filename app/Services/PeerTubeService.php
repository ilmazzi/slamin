<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\UploadedFile;
use Exception;

class PeerTubeService
{
    private $baseUrl;
    private $username;
    private $password;
    private $channelId;
    private $timeout;
    private $maxRetries;
    private $accessToken = null;

    public function __construct()
    {
        $this->baseUrl = config('peertube.base_url');
        $this->username = config('peertube.username');
        $this->password = config('peertube.password');
        $this->channelId = config('peertube.channel_id');
        $this->timeout = config('peertube.timeout', 300);
        $this->maxRetries = config('peertube.max_retries', 3);
    }

    /**
     * Autenticazione OAuth2 con PeerTube
     */
    private function authenticate(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            // 1. Ottieni client OAuth
            $clientResponse = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/oauth-clients/local");

            if (!$clientResponse->successful()) {
                throw new Exception('Impossibile ottenere client OAuth: ' . $clientResponse->body());
            }

            $clientData = $clientResponse->json();

            // 2. Login utente
            $loginResponse = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/users/login", [
                    'username' => $this->username,
                    'password' => $this->password
                ]);

            if (!$loginResponse->successful()) {
                throw new Exception('Login fallito: ' . $loginResponse->body());
            }

            $loginData = $loginResponse->json();

            // 3. Ottieni access token
            $tokenResponse = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/oauth-clients/local/token", [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $this->username,
                    'password' => $this->password,
                    'response_type' => 'token'
                ]);

            if (!$tokenResponse->successful()) {
                throw new Exception('Impossibile ottenere access token: ' . $tokenResponse->body());
            }

            $tokenData = $tokenResponse->json();
            $this->accessToken = $tokenData['access_token'];

            // Cache del token per 1 ora
            Cache::put('peertube_access_token', $this->accessToken, 3600);

            return $this->accessToken;

        } catch (Exception $e) {
            Log::error('PeerTube authentication error: ' . $e->getMessage());
            throw new Exception('Errore di autenticazione PeerTube: ' . $e->getMessage());
        }
    }

    /**
     * Ottieni token da cache o autenticati
     */
    private function getAccessToken(): string
    {
        // Prova a ottenere da cache
        $cachedToken = Cache::get('peertube_access_token');
        if ($cachedToken) {
            $this->accessToken = $cachedToken;
            return $cachedToken;
        }

        // Autenticati
        return $this->authenticate();
    }

    /**
     * Upload di un video su PeerTube
     */
    public function uploadVideo(UploadedFile $file, array $metadata): array
    {
        try {
            // 1. Validazione file
            $this->validateFile($file);

            // 2. Preparazione metadata
            $videoData = $this->prepareVideoData($metadata);

            // 3. Upload file con retry
            $uploadResponse = $this->uploadFileWithRetry($file);

            // 4. Creazione video entry
            $videoResponse = $this->createVideoEntry($uploadResponse['videoId'], $videoData);

            // 5. Attesa transcoding
            $this->waitForTranscoding($videoResponse['id']);

            // 6. Ottieni informazioni finali
            $finalVideo = $this->getVideo($videoResponse['id']);

            return [
                'id' => $finalVideo['id'],
                'url' => $finalVideo['url'],
                'embedUrl' => $finalVideo['embedUrl'],
                'thumbnailPath' => $finalVideo['thumbnailPath'] ?? null,
                'duration' => $finalVideo['duration'] ?? 0,
                'resolution' => $finalVideo['resolution'] ?? 'unknown',
                'success' => true
            ];

        } catch (Exception $e) {
            Log::error('PeerTube upload error: ' . $e->getMessage());
            throw new Exception('Errore durante l\'upload su PeerTube: ' . $e->getMessage());
        }
    }

    /**
     * Ottieni informazioni di un video
     */
    public function getVideo(int $videoId): array
    {
        $cacheKey = "peertube_video_{$videoId}";

        return Cache::remember($cacheKey, config('peertube.cache_ttl', 3600), function () use ($videoId) {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/videos/{$videoId}");

            if (!$response->successful()) {
                throw new Exception('Impossibile ottenere informazioni del video: ' . $response->body());
            }

            return $response->json();
        });
    }

    /**
     * Aggiorna metadata di un video
     */
    public function updateVideo(int $videoId, array $metadata): array
    {
        $response = Http::timeout($this->timeout)
            ->withHeaders($this->getAuthHeaders())
            ->put("{$this->baseUrl}/api/v1/videos/{$videoId}", $metadata);

        if (!$response->successful()) {
            throw new Exception('Impossibile aggiornare il video: ' . $response->body());
        }

        // Invalida cache
        Cache::forget("peertube_video_{$videoId}");

        return $response->json();
    }

    /**
     * Elimina un video
     */
    public function deleteVideo(int $videoId): bool
    {
        $response = Http::timeout($this->timeout)
            ->withHeaders($this->getAuthHeaders())
            ->delete("{$this->baseUrl}/api/v1/videos/{$videoId}");

        if (!$response->successful()) {
            throw new Exception('Impossibile eliminare il video: ' . $response->body());
        }

        // Invalida cache
        Cache::forget("peertube_video_{$videoId}");

        return true;
    }

    /**
     * Ottieni statistiche di un video
     */
    public function getVideoStats(int $videoId): array
    {
        $response = Http::timeout($this->timeout)
            ->withHeaders($this->getAuthHeaders())
            ->get("{$this->baseUrl}/api/v1/videos/{$videoId}/stats");

        if (!$response->successful()) {
            throw new Exception('Impossibile ottenere statistiche del video: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Test connessione PeerTube
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/v1/config");
            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Test autenticazione
     */
    public function testAuthentication(): bool
    {
        try {
            $token = $this->getAccessToken();
            return !empty($token);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Validazione file
     */
    private function validateFile(UploadedFile $file): void
    {
        $maxSize = config('peertube.upload_limit_mb', 500) * 1024 * 1024; // MB to bytes
        $allowedFormats = config('peertube.allowed_formats', ['mp4', 'avi', 'mov']);

        if ($file->getSize() > $maxSize) {
            throw new Exception('File troppo grande. Massimo ' . config('peertube.upload_limit_mb', 500) . 'MB');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedFormats)) {
            throw new Exception('Formato non supportato. Formati ammessi: ' . implode(', ', $allowedFormats));
        }
    }

    /**
     * Preparazione metadata video
     */
    private function prepareVideoData(array $metadata): array
    {
        return [
            'name' => $metadata['title'] ?? 'Video senza titolo',
            'description' => $metadata['description'] ?? '',
            'category' => config('peertube.default_category', 15),
            'language' => config('peertube.default_language', 'it'),
            'privacy' => config('peertube.default_privacy', 1),
            'channelId' => $this->channelId,
            'tags' => array_merge(
                config('peertube.default_tags', []),
                $metadata['tags'] ?? []
            ),
            'thumbnailfile' => $metadata['thumbnail'] ?? null,
            'previewfile' => $metadata['preview'] ?? null,
        ];
    }

    /**
     * Upload file con retry
     */
    private function uploadFileWithRetry(UploadedFile $file): array
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->maxRetries) {
            try {
                $response = Http::timeout($this->timeout)
                    ->withHeaders($this->getAuthHeaders())
                    ->attach('videofile', $file->get(), $file->getClientOriginalName())
                    ->post("{$this->baseUrl}/api/v1/videos/upload");

                if ($response->successful()) {
                    return $response->json();
                }

                throw new Exception('Upload fallito: ' . $response->body());

            } catch (Exception $e) {
                $lastException = $e;
                $attempts++;

                if ($attempts < $this->maxRetries) {
                    sleep(config('peertube.retry_delay', 5));
                }
            }
        }

        throw new Exception('Upload fallito dopo ' . $this->maxRetries . ' tentativi: ' . $lastException->getMessage());
    }

    /**
     * Creazione entry video
     */
    private function createVideoEntry(string $videoId, array $videoData): array
    {
        $response = Http::timeout($this->timeout)
            ->withHeaders($this->getAuthHeaders())
            ->post("{$this->baseUrl}/api/v1/videos", array_merge($videoData, [
                'videoId' => $videoId
            ]));

        if (!$response->successful()) {
            throw new Exception('Impossibile creare entry video: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Attesa transcoding
     */
    private function waitForTranscoding(int $videoId): void
    {
        $maxWait = 300; // 5 minuti
        $waitTime = 0;
        $checkInterval = 10; // secondi

        while ($waitTime < $maxWait) {
            $video = $this->getVideo($videoId);

            if (isset($video['state']) && $video['state']['id'] === 1) { // Published
                return;
            }

            sleep($checkInterval);
            $waitTime += $checkInterval;
        }

        throw new Exception('Timeout transcoding per video ' . $videoId);
    }

    /**
     * Headers di autenticazione
     */
    private function getAuthHeaders(): array
    {
        $token = $this->getAccessToken();

        return [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ];
    }
}
