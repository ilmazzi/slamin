<?php

namespace App\Services;

use App\Models\PeerTubeConfig;
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
    private $accountId;
    private $timeout;
    private $maxRetries;
    private $accessToken = null;

    public function __construct()
    {
        $this->baseUrl = PeerTubeConfig::getValue('peertube_url');
        $this->username = PeerTubeConfig::getValue('peertube_admin_username');
        $this->password = PeerTubeConfig::getValue('peertube_admin_password');
        $this->channelId = PeerTubeConfig::getValue('peertube_channel_id');
        $this->accountId = PeerTubeConfig::getValue('peertube_account_id');
        $this->timeout = 300; // 5 minuti
        $this->maxRetries = 3;
    }

    /**
     * Autenticazione con PeerTube usando le API ufficiali
     */
    private function authenticate(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            // Verifica se abbiamo già un token valido
            if (PeerTubeConfig::hasValidToken()) {
                $this->accessToken = PeerTubeConfig::getValue('peertube_access_token');
                return $this->accessToken;
            }

            // 1. Ottieni client OAuth
            $clientResponse = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/oauth-clients/local");

            if (!$clientResponse->successful()) {
                throw new Exception('Impossibile ottenere client OAuth: ' . $clientResponse->body());
            }

            $clientData = $clientResponse->json();

            // 2. Login non necessario, andiamo direttamente al token

            // 3. Ottieni access token (endpoint corretto secondo documentazione)
            $tokenResponse = Http::timeout($this->timeout)
                ->asForm()
                ->post("{$this->baseUrl}/api/v1/users/token", [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $this->username,
                    'password' => $this->password
                ]);

            if (!$tokenResponse->successful()) {
                throw new Exception('Impossibile ottenere access token: ' . $tokenResponse->body());
            }

            $tokenData = $tokenResponse->json();
            $this->accessToken = $tokenData['access_token'];

            // Salva token e scadenza nel database
            $expiresAt = now()->addSeconds($tokenData['expires_in'] ?? 3600);
            PeerTubeConfig::setValue('peertube_access_token', $this->accessToken, 'string', 'Token di accesso admin PeerTube', true);
            PeerTubeConfig::setValue('peertube_token_expires_at', $expiresAt, 'datetime', 'Scadenza token PeerTube');

            return $this->accessToken;

        } catch (Exception $e) {
            Log::error('PeerTube authentication error: ' . $e->getMessage());
            throw new Exception('Errore di autenticazione PeerTube: ' . $e->getMessage());
        }
    }

    /**
     * Ottieni token dal database o autenticati
     */
    public function getAccessToken(): string
    {
        // Prova a ottenere dal database
        if (PeerTubeConfig::hasValidToken()) {
            $this->accessToken = PeerTubeConfig::getValue('peertube_access_token');
            return $this->accessToken;
        }

        // Autenticati
        return $this->authenticate();
    }

    /**
     * Upload di un video su PeerTube usando le API ufficiali
     */
    public function uploadVideo(UploadedFile $file, array $metadata): array
    {
        try {
            // 1. Validazione file
            $this->validateFile($file);

            // 2. Ottieni token di accesso
            $token = $this->getAccessToken();

            // 3. Preparazione dati per l'upload secondo documentazione
            $uploadData = [
                'channelId' => $metadata['channelId'] ?? $this->channelId,
                'name' => $metadata['name'] ?? 'Video Poetry Slam',
                'waitTranscoding' => true, // Aspetta il transcoding prima di pubblicare
                'downloadEnabled' => true, // Abilita il download
                'commentsPolicy' => 1, // Commenti abilitati
            ];

            // Aggiungi campi opzionali se presenti
            if (isset($metadata['description']) && !empty($metadata['description'])) {
                $uploadData['description'] = $metadata['description'];
            }

            // Gestione tags come array (max 5 tags, 2-30 caratteri ciascuno)
            if (isset($metadata['tags']) && is_array($metadata['tags'])) {
                $validTags = array_filter($metadata['tags'], function($tag) {
                    $tag = trim($tag);
                    return strlen($tag) >= 2 && strlen($tag) <= 30;
                });
                $validTags = array_slice($validTags, 0, 5); // Max 5 tags
                if (!empty($validTags)) {
                    $uploadData['tags'] = $validTags;
                }
            }

            // Gestione privacy (deve essere un intero secondo documentazione)
            if (isset($metadata['privacy'])) {
                switch ($metadata['privacy']) {
                    case 'public':
                        $uploadData['privacy'] = 1;
                        break;
                    case 'unlisted':
                        $uploadData['privacy'] = 2;
                        break;
                    case 'private':
                        $uploadData['privacy'] = 3;
                        break;
                    default:
                        $uploadData['privacy'] = 1; // Default: public
                }
            } else {
                $uploadData['privacy'] = 1; // Default: public
            }

            // 4. Upload usando Guzzle multipart (più affidabile di Http::attach)
            $client = new \GuzzleHttp\Client();
            $multipart = [
                [
                    'name'     => 'videofile',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ],
                ['name' => 'channelId', 'contents' => $uploadData['channelId']],
                ['name' => 'name', 'contents' => $uploadData['name']],
                ['name' => 'waitTranscoding', 'contents' => 'true'],
                ['name' => 'downloadEnabled', 'contents' => 'true'],
                ['name' => 'commentsPolicy', 'contents' => '1'],
                ['name' => 'privacy', 'contents' => $uploadData['privacy']],
            ];

            // Aggiungi descrizione se presente
            if (isset($uploadData['description'])) {
                $multipart[] = ['name' => 'description', 'contents' => $uploadData['description']];
            }

            // Aggiungi tags se presenti
            if (isset($uploadData['tags']) && is_array($uploadData['tags'])) {
                foreach ($uploadData['tags'] as $tag) {
                    $multipart[] = ['name' => 'tags[]', 'contents' => $tag];
                }
            }

            $response = $client->request('POST', "{$this->baseUrl}/api/v1/videos/upload", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
                'multipart' => $multipart,
                'timeout' => 600, // 10 minuti per il transcoding
            ]);

            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                throw new Exception('Upload fallito: ' . $response->getStatusCode() . ' - ' . $response->getBody()->getContents());
            }

            $uploadResult = json_decode($response->getBody()->getContents(), true);

            // 5. Gestione risposta secondo documentazione
            $videoId = null;
            $videoUuid = null;
            if (isset($uploadResult['video']['id'])) {
                $videoId = $uploadResult['video']['id'];
                $videoUuid = $uploadResult['video']['uuid'] ?? null;
            } elseif (isset($uploadResult['id'])) {
                $videoId = $uploadResult['id'];
                $videoUuid = $uploadResult['uuid'] ?? null;
            }

            // Debug: log della risposta
            Log::info('PeerTube upload response: ' . json_encode($uploadResult));

            // 6. Gestione risposta (il transcoding è già completato grazie a waitTranscoding=true)
            if ($videoId) {
                // Costruisci gli URL del video
                $videoUrl = "{$this->baseUrl}/videos/watch/{$videoUuid}";
                $embedUrl = "{$this->baseUrl}/videos/embed/{$videoUuid}";

                // Recupera informazioni complete del video (inclusa durata)
                $videoInfo = null;
                try {
                    $videoInfo = $this->getVideo($videoId);
                } catch (Exception $e) {
                    Log::warning("Impossibile recuperare informazioni del video {$videoId}: " . $e->getMessage());
                }

                return [
                    'id' => $videoId,
                    'uuid' => $videoUuid,
                    'url' => $videoUrl,
                    'embedUrl' => $embedUrl,
                    'duration' => $videoInfo['duration'] ?? null,
                    'resolution' => $videoInfo['resolution'] ?? null,
                    'thumbnailPath' => $videoInfo['thumbnailPath'] ?? null,
                    'success' => true
                ];
            }

            // Fallback se non riusciamo a ottenere l'ID
            return [
                'id' => $videoId,
                'uuid' => $videoUuid,
                'url' => null,
                'embedUrl' => null,
                'duration' => null,
                'resolution' => null,
                'thumbnailPath' => null,
                'success' => false
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
     * Verifica se PeerTube è configurato
     */
    public function isConfigured(): bool
    {
        return PeerTubeConfig::isConfigured();
    }

    /**
     * Ottieni informazioni del canale
     */
    public function getChannelInfo(): ?array
    {
        if (!$this->channelId) {
            return null;
        }

        try {
            // Prima prova con l'ID numerico
            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getAuthHeaders())
                ->get("{$this->baseUrl}/api/v1/video-channels/{$this->channelId}");

            if ($response->successful()) {
                return $response->json();
            }

            // Se fallisce, prova con il nome del canale "slamin"
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/video-channels/slamin");

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Error getting channel info: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Ottieni informazioni dell'account
     */
    public function getAccountInfo(): ?array
    {
        if (!$this->accountId) {
            return null;
        }

        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/accounts/{$this->accountId}");

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Error getting account info: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Sincronizza statistiche di un video
     */
    public function syncVideoStats(int $videoId): array
    {
        try {
            $video = $this->getVideo($videoId);
            $stats = $this->getVideoStats($videoId);

            return [
                'view_count' => $stats['totalViews'] ?? 0,
                'like_count' => $stats['totalLikes'] ?? 0,
                'dislike_count' => $stats['totalDislikes'] ?? 0,
                'comment_count' => $stats['totalComments'] ?? 0,
                'duration' => $video['duration'] ?? 0,
                'resolution' => $video['resolution'] ?? 'unknown',
                'file_size' => $video['files'][0]['size'] ?? 0,
            ];
        } catch (Exception $e) {
            Log::error('Error syncing video stats: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Ottieni lista video del canale
     */
    public function getChannelVideos(int $count = 20, int $start = 0): array
    {
        if (!$this->channelId) {
            return [];
        }

        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/video-channels/{$this->channelId}/videos", [
                    'count' => $count,
                    'start' => $start
                ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Error getting channel videos: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Ottieni lista di tutti gli account
     */
    public function getAccounts(int $count = 50, int $start = 0): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/accounts", [
                    'count' => $count,
                    'start' => $start
                ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Error getting accounts: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Ottieni lista di tutti i canali
     */
    public function getChannels(int $count = 50, int $start = 0): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/video-channels", [
                    'count' => $count,
                    'start' => $start
                ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Error getting channels: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Ottieni informazioni di un account specifico per username
     */
    public function getAccountByUsername(string $username): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/accounts/{$username}");

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Error getting account by username: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Ottieni informazioni di un canale specifico per nome
     */
    public function getChannelByName(string $channelName): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/video-channels/{$channelName}");

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            Log::error('Error getting channel by name: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Validazione file
     */
    private function validateFile(UploadedFile $file): void
    {
        $maxSize = PeerTubeConfig::getValue('peertube_max_file_size', 1073741824); // 1GB default
        $allowedFormats = PeerTubeConfig::getValue('peertube_allowed_extensions', ['mp4', 'avi', 'mov', 'mkv', 'webm']);

        if ($file->getSize() > $maxSize) {
            $maxSizeMB = round($maxSize / (1024 * 1024));
            throw new Exception("File troppo grande. Massimo {$maxSizeMB}MB");
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
        $defaultTags = PeerTubeConfig::getValue('peertube_default_tags', ['poetry', 'slam', 'poetry-slam']);
        $defaultPrivacy = PeerTubeConfig::getValue('peertube_default_privacy', 'public');

        // Converti privacy in formato PeerTube
        $privacyMap = [
            'public' => 1,
            'unlisted' => 2,
            'private' => 3
        ];

        return [
            'name' => $metadata['title'] ?? 'Video senza titolo',
            'description' => $metadata['description'] ?? '',
            'category' => 15, // Entertainment
            'language' => 'it',
            'privacy' => $privacyMap[$defaultPrivacy] ?? 1,
            'channelId' => $this->channelId,
            'tags' => array_merge($defaultTags, $metadata['tags'] ?? []),
            'thumbnailfile' => $metadata['thumbnail'] ?? null,
            'previewfile' => $metadata['preview'] ?? null,
        ];
    }

    /**
     * Upload file con retry usando l'API ufficiale
     */
    private function uploadFileWithRetry(UploadedFile $file, array $videoData): array
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->maxRetries) {
            try {
                // Preparazione multipart data
                $multipartData = [];

                // Aggiungi il file video
                $multipartData[] = [
                    'name' => 'videofile',
                    'contents' => $file->get(),
                    'filename' => $file->getClientOriginalName()
                ];

                // Aggiungi i metadata
                foreach ($videoData as $key => $value) {
                    if ($value !== null) {
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $multipartData[] = [
                            'name' => $key,
                            'contents' => $value
                        ];
                    }
                }

                $response = Http::timeout($this->timeout)
                    ->withHeaders($this->getAuthHeaders())
                    ->attach('videofile', $file->get(), $file->getClientOriginalName())
                    ->post("{$this->baseUrl}/api/v1/videos/upload", $videoData);

                if ($response->successful()) {
                    return $response->json();
                }

                throw new Exception('Upload fallito: ' . $response->body());

            } catch (Exception $e) {
                $lastException = $e;
                $attempts++;

                if ($attempts < $this->maxRetries) {
                    sleep(5); // 5 secondi di attesa
                }
            }
        }

        throw new Exception('Upload fallito dopo ' . $this->maxRetries . ' tentativi: ' . $lastException->getMessage());
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

