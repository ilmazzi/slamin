<?php

namespace App\Services;

use App\Models\User;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Exception;
use Illuminate\Support\Str;

class PeerTubeService
{
    private $baseUrl;
    private $adminUsername;
    private $adminPassword;
    private $timeout;
    private $maxRetries;
    private $accessToken = null;

    public function __construct()
    {
        // Usa le configurazioni dal database
        $this->baseUrl = PeerTubeConfig::getValue('peertube_url');
        $this->adminUsername = PeerTubeConfig::getValue('peertube_admin_username');
        $this->adminPassword = PeerTubeConfig::getValue('peertube_admin_password');
        $this->timeout = 300; // 5 minuti
        $this->maxRetries = 3;
    }

    /**
     * Verifica se il servizio è configurato
     */
    public function isConfigured(): bool
    {
        return !empty($this->baseUrl) && 
               !empty($this->adminUsername) && 
               !empty($this->adminPassword);
    }

    /**
     * Testa la connessione a PeerTube
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/v1/config");
            return $response->successful();
        } catch (Exception $e) {
            Log::error('PeerTube connection error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Autenticazione con PeerTube
     */
    private function authenticate(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        try {
            Log::info('PeerTube: Iniziando autenticazione', [
                'url' => $this->baseUrl,
                'username' => $this->adminUsername,
                'timeout' => $this->timeout
            ]);
            
            // 1. Ottieni client OAuth
            $clientResponse = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/oauth-clients/local");

            if (!$clientResponse->successful()) {
                throw new Exception('Impossibile ottenere client OAuth: ' . $clientResponse->body());
            }

            $clientData = $clientResponse->json();
            
            // Verifica che la risposta client contenga i dati attesi
            if (!$clientData || !is_array($clientData)) {
                throw new Exception('Risposta client OAuth non valida: ' . $clientResponse->body());
            }
            
            if (!isset($clientData['client_id']) || !isset($clientData['client_secret'])) {
                throw new Exception('Client ID o Secret mancanti nella risposta: ' . json_encode($clientData));
            }

            // 2. Ottieni access token - Usa endpoint che funziona
            $tokenResponse = Http::timeout($this->timeout)
                ->asForm()
                ->post("{$this->baseUrl}/api/v1/users/token", [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $this->adminUsername,
                    'password' => $this->adminPassword
                ]);

            if (!$tokenResponse->successful()) {
                throw new Exception('Impossibile ottenere access token: ' . $tokenResponse->body());
            }

            $tokenData = $tokenResponse->json();
            
            // Verifica che la risposta contenga i dati attesi
            if (!$tokenData || !is_array($tokenData)) {
                throw new Exception('Risposta token non valida: ' . $tokenResponse->body());
            }
            
            if (!isset($tokenData['access_token']) || empty($tokenData['access_token'])) {
                throw new Exception('Token di accesso mancante nella risposta: ' . json_encode($tokenData));
            }
            
            $this->accessToken = $tokenData['access_token'];
            
            Log::info('PeerTube: Autenticazione riuscita', [
                'token_length' => strlen($tokenData['access_token']),
                'expires_in' => $tokenData['expires_in'] ?? 'N/A'
            ]);

            return $this->accessToken;

        } catch (Exception $e) {
            Log::error('PeerTube authentication error: ' . $e->getMessage());
            throw new Exception('Errore di autenticazione PeerTube: ' . $e->getMessage());
        }
    }

    /**
     * Crea un utente su PeerTube
     */
    public function createUser(array $userData): array
    {
        try {
            // Genera una password sicura per PeerTube se non fornita
            $peerTubePassword = $userData['peertube_password'] ?? Str::random(12);
            
            // Cripta la password per il database locale
            $encryptedPassword = encrypt($peerTubePassword);
            
            // Valida e pulisci username secondo la documentazione PeerTube
            // Username: [1..50] caratteri, solo a-z, 0-9, punti e underscore
            $username = preg_replace('/[^a-z0-9._]/', '', strtolower($userData['peertube_username']));
            
            // Se l'username è vuoto o troppo corto, usa un fallback
            if (strlen($username) < 1) {
                $username = 'user_' . time();
            }
            if (strlen($username) > 50) {
                $username = substr($username, 0, 50);
            }
            
            // Prepara il payload secondo la documentazione ufficiale PeerTube
            $payload = [
                'username' => $username,
                'email' => $userData['email'],
                'password' => $peerTubePassword,
                'role' => 2, // User role (0=Admin, 1=Moderator, 2=User)
                'videoQuota' => -1, // Quota illimitata
                'videoQuotaDaily' => -1, // Quota giornaliera illimitata
            ];
            
            // Aggiungi channelName se fornito
            if (!empty($userData['peertube_channel_name'])) {
                $channelName = preg_replace('/[^a-zA-Z0-9\-_.:]/', '', $userData['peertube_channel_name']);
                
                // Assicurati che il nome del canale non sia uguale all'username
                if (strtolower($channelName) === strtolower($username)) {
                    $channelName = $channelName . '_channel';
                }
                
                if (strlen($channelName) >= 1 && strlen($channelName) <= 50) {
                    $payload['channelName'] = $channelName;
                } else {
                    Log::warning('PeerTube: Nome canale non valido, saltando', [
                        'original_channel_name' => $userData['peertube_channel_name'],
                        'cleaned_channel_name' => $channelName,
                        'username' => $username
                    ]);
                }
            }
            
            Log::info('PeerTube: Tentativo creazione utente', [
                'original_username' => $userData['peertube_username'],
                'cleaned_username' => $username,
                'email' => $userData['email'],
                'payload' => array_merge($payload, ['password' => '***HIDDEN***'])
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->authenticate(),
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/v1/users', $payload);

            if ($response->successful()) {
                $userInfo = $response->json();
                
                // Determina l'ID utente dalla risposta
                $userId = $userInfo['user']['id'] ?? $userInfo['id'] ?? null;
                
                if (!$userId) {
                    throw new Exception('Impossibile determinare l\'ID utente dalla risposta PeerTube');
                }

                // Recupera le informazioni complete dell'utente appena creato
                $completeUserInfo = $this->getUserInfo($userId);
                
                if (!$completeUserInfo) {
                    Log::warning('Impossibile recuperare informazioni complete utente PeerTube', [
                        'user_id' => $userId,
                        'username' => $username
                    ]);
                }

                // Estrai informazioni del canale se disponibili
                $channelId = null;
                $channelName = null;
                if ($completeUserInfo && isset($completeUserInfo['videoChannels']) && !empty($completeUserInfo['videoChannels'])) {
                    $channel = $completeUserInfo['videoChannels'][0];
                    $channelId = $channel['id'] ?? null;
                    $channelName = $channel['name'] ?? null;
                }

                Log::info('PeerTube: Utente creato con successo', [
                    'user_id' => $userId,
                    'username' => $username,
                    'channel_id' => $channelId,
                    'channel_name' => $channelName
                ]);

                return [
                    'success' => true,
                    'peertube_user_id' => $userId,
                    'peertube_username' => $username,
                    'peertube_display_name' => $userData['peertube_display_name'] ?? $userData['name'],
                    'peertube_password' => $encryptedPassword, // Password criptata per il database
                    'peertube_password_plain' => $peerTubePassword, // Password in chiaro solo per uso immediato
                    'peertube_channel_id' => $channelId,
                    'peertube_channel_name' => $channelName,
                    'peertube_account_id' => $completeUserInfo['account']['id'] ?? null,
                    'complete_user_info' => $completeUserInfo,
                ];
            } else {
                $errorResponse = $response->json();
                Log::error('Errore creazione utente PeerTube', [
                    'status' => $response->status(),
                    'response' => $errorResponse,
                    'body' => $response->body(),
                    'username' => $username
                ]);
                
                $errorMessage = 'Errore nella creazione dell\'utente PeerTube: ' . $response->status();
                if ($errorResponse && isset($errorResponse['detail'])) {
                    $errorMessage .= ' - ' . $errorResponse['detail'];
                } elseif ($errorResponse && isset($errorResponse['message'])) {
                    $errorMessage .= ' - ' . $errorResponse['message'];
                } elseif ($errorResponse) {
                    $errorMessage .= ' - ' . json_encode($errorResponse);
                } else {
                    $errorMessage .= ' - ' . $response->body();
                }
                
                throw new Exception($errorMessage);
            }
        } catch (Exception $e) {
            Log::error('Eccezione durante la creazione utente PeerTube', [
                'error' => $e->getMessage(),
                'username' => $userData['peertube_username'] ?? 'unknown'
            ]);
            throw $e;
        }
    }

    /**
     * Ottieni informazioni utente (inclusi canali)
     */
    public function getUserInfo(int $userId): ?array
    {
        try {
            $token = $this->authenticate();

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->get("{$this->baseUrl}/api/v1/users/{$userId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            Log::error('PeerTube getUserInfo error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ottieni informazioni account per username (legacy)
     */
    public function getAccountInfoByUsername(string $username): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/accounts/{$username}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            Log::error('PeerTube getAccountInfoByUsername error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ottieni informazioni dell'account configurato
     */
    public function getAccountInfo(): ?array
    {
        try {
            $accountId = PeerTubeConfig::getValue('peertube_account_id');
            if (!$accountId) {
                return null;
            }

            $token = $this->authenticate();
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->get("{$this->baseUrl}/api/v1/accounts/{$accountId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            Log::error('PeerTube getAccountInfo error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ottieni token per un utente specifico
     */
    public function getUserToken(User $user): array
    {
        try {
            // Decripta la password se è criptata
            $password = $user->peertube_password;
            if (str_starts_with($password, 'eyJpdiI6')) { // Controlla se è criptata (Laravel encrypt)
                $password = decrypt($password);
            }
            
            // 1. Ottieni client OAuth
            $clientResponse = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/oauth-clients/local");

            if (!$clientResponse->successful()) {
                throw new Exception('Impossibile ottenere client OAuth');
            }

            $clientData = $clientResponse->json();

            // 2. Login utente
            $loginResponse = Http::timeout($this->timeout)
                ->asForm()
                ->post("{$this->baseUrl}/api/v1/users/token", [
                    'client_id' => $clientData['client_id'],
                    'client_secret' => $clientData['client_secret'],
                    'grant_type' => 'password',
                    'username' => $user->peertube_username,
                    'password' => $password, // Password decriptata per PeerTube
                ]);

            if (!$loginResponse->successful()) {
                throw new Exception('Login utente fallito: ' . $loginResponse->body());
            }

            $tokenData = $loginResponse->json();
            
            return [
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? null,
                'expires_in' => $tokenData['expires_in'] ?? 3600,
            ];

        } catch (Exception $e) {
            Log::error('PeerTube getUserToken error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Carica un video per un utente specifico
     */
    public function uploadVideo(User $user, UploadedFile $file, array $metadata): array
    {
        try {
            // Ottieni token utente
            $tokenData = $this->getUserToken($user);
            
            // Preparazione dati video secondo la documentazione API
            $videoData = [
                'channelId' => $user->peertube_channel_id,
                'name' => $metadata['name'] ?? $metadata['title'] ?? 'Untitled Video',
                'description' => $metadata['description'] ?? '',
                'tags' => $metadata['tags'] ?? [],
                'privacy' => $metadata['privacy'] ?? 1, // 1 = Public
                'commentsPolicy' => 1, // Enabled
                'downloadEnabled' => true,
                'nsfw' => false,
                'waitTranscoding' => false,
            ];

            // Upload file usando Guzzle direttamente
            $client = new \GuzzleHttp\Client([
                'timeout' => $this->timeout,
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokenData['access_token'],
                ]
            ]);

            $multipart = [
                [
                    'name' => 'videofile',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                    'headers' => [
                        'Content-Type' => $file->getMimeType()
                    ]
                ]
            ];

            // Aggiungi altri campi al multipart
            foreach ($videoData as $key => $value) {
                if ($key === 'tags' && is_array($value)) {
                    // Per i tag, invia ogni tag come campo separato
                    foreach ($value as $tag) {
                        $multipart[] = [
                            'name' => 'tags[]',
                            'contents' => $tag
                        ];
                    }
                } elseif (is_array($value)) {
                    $value = json_encode($value);
                    $multipart[] = [
                        'name' => $key,
                        'contents' => (string) $value
                    ];
                } else {
                    $multipart[] = [
                        'name' => $key,
                        'contents' => (string) $value
                    ];
                }
            }

            $response = $client->post("{$this->baseUrl}/api/v1/videos/upload", [
                'multipart' => $multipart
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                $errorBody = $response->getBody()->getContents();
                Log::error('PeerTube uploadVideo error response:', [
                    'status' => $statusCode, 
                    'body' => $errorBody
                ]);
                
                if ($statusCode === 403) {
                    throw new Exception('Video non ha superato i filtri di upload');
                } elseif ($statusCode === 408) {
                    throw new Exception('Upload scaduto per timeout');
                } elseif ($statusCode === 413) {
                    throw new Exception('File video troppo grande');
                } elseif ($statusCode === 415) {
                    throw new Exception('Tipo di video non supportato');
                } elseif ($statusCode === 422) {
                    throw new Exception('Video non leggibile');
                } else {
                    throw new Exception('Errore upload video: ' . $errorBody);
                }
            }

            $videoInfo = json_decode($response->getBody()->getContents(), true);
            Log::info('PeerTube uploadVideo success response:', $videoInfo);
            
            return [
                'video_id' => $videoInfo['video']['id'] ?? null,
                'uuid' => $videoInfo['video']['uuid'] ?? null,
                'name' => $videoInfo['video']['name'] ?? null,
                'description' => $videoInfo['video']['description'] ?? null,
                'duration' => $videoInfo['video']['duration'] ?? null,
                'thumbnailPath' => $videoInfo['video']['thumbnailPath'] ?? null,
                'embedPath' => $videoInfo['video']['embedPath'] ?? null,
                'url' => $videoInfo['video']['url'] ?? null,
                'embedUrl' => $videoInfo['video']['embedUrl'] ?? null,
            ];

        } catch (Exception $e) {
            Log::error('PeerTube uploadVideo error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Crea un canale per un utente
     */
    public function createChannel(User $user, array $channelData): array
    {
        try {
            // Ottieni token utente
            $tokenData = $this->getUserToken($user);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $tokenData['access_token'],
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/api/v1/video-channels", [
                    'name' => $channelData['name'] ?? $user->peertube_username . '_channel',
                    'displayName' => $channelData['display_name'] ?? $user->name . ' Channel',
                    'description' => $channelData['description'] ?? 'Canale di ' . $user->name,
                    'privacy' => $channelData['privacy'] ?? 1, // 1 = Public
                ]);

            if (!$response->successful()) {
                throw new Exception('Errore creazione canale: ' . $response->body());
            }

            $channelInfo = $response->json();
            
            return [
                'channel_id' => $channelInfo['videoChannel']['id'],
                'name' => $channelInfo['videoChannel']['name'],
                'display_name' => $channelInfo['videoChannel']['displayName'],
                'description' => $channelInfo['videoChannel']['description'],
            ];

        } catch (Exception $e) {
            Log::error('PeerTube createChannel error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Crea una diretta per un utente
     */
    public function createLive(User $user, array $liveData): array
    {
        try {
            // Ottieni token utente
            $tokenData = $this->getUserToken($user);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $tokenData['access_token'],
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/api/v1/videos/live", [
                    'name' => $liveData['title'],
                    'description' => $liveData['description'] ?? '',
                    'tags' => $liveData['tags'] ?? [],
                    'privacy' => $liveData['privacy'] ?? 1,
                    'channelId' => $user->peertube_channel_id,
                    'saveReplay' => $liveData['save_replay'] ?? false,
                ]);

            if (!$response->successful()) {
                throw new Exception('Errore creazione diretta: ' . $response->body());
            }

            $liveInfo = $response->json();
            
            return [
                'live_id' => $liveInfo['video']['id'],
                'uuid' => $liveInfo['video']['uuid'],
                'name' => $liveInfo['video']['name'],
                'streamKey' => $liveInfo['streamKey'],
                'rtmpUrl' => $liveInfo['rtmpUrl'],
                'liveUrl' => $liveInfo['liveUrl'],
                'embedUrl' => $liveInfo['embedUrl'],
            ];

        } catch (Exception $e) {
            Log::error('PeerTube createLive error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Elimina un utente da PeerTube
     */
    public function deleteUser(int $userId): bool
    {
        try {
            $token = $this->authenticate();

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->delete("{$this->baseUrl}/api/v1/users/{$userId}");

            if ($response->successful()) {
                Log::info('PeerTube: Utente eliminato con successo', ['user_id' => $userId]);
                return true;
            } else {
                Log::warning('PeerTube: Errore eliminazione utente', [
                    'user_id' => $userId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }

        } catch (Exception $e) {
            Log::error('PeerTube deleteUser error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina utente di test per pulizia
     */
    public function deleteTestUser(string $username): bool
    {
        try {
            // Prima trova l'utente per username
            $token = $this->authenticate();
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->get("{$this->baseUrl}/api/v1/users", [
                    'search' => $username,
                    'count' => 10
                ]);

            if ($response->successful()) {
                $users = $response->json();
                
                if (isset($users['data']) && !empty($users['data'])) {
                    foreach ($users['data'] as $user) {
                        if ($user['username'] === $username) {
                            return $this->deleteUser($user['id']);
                        }
                    }
                }
            }

            Log::warning('PeerTube: Utente di test non trovato', ['username' => $username]);
            return false;

        } catch (Exception $e) {
            Log::error('PeerTube deleteTestUser error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ottieni statistiche video
     */
    public function getVideoStats(int $videoId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/videos/{$videoId}/stats");

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (Exception $e) {
            Log::error('PeerTube getVideoStats error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Testa l'autenticazione PeerTube
     */
    public function testAuthentication(): bool
    {
        try {
            Log::info('PeerTube: Test autenticazione iniziato');
            $token = $this->authenticate();
            Log::info('PeerTube: Test autenticazione completato con successo', [
                'token_length' => strlen($token)
            ]);
            return true;
        } catch (Exception $e) {
            Log::error('PeerTube: Test autenticazione fallito', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Ottieni informazioni del canale
     */
    public function getChannelInfo(): ?array
    {
        try {
            $channelId = PeerTubeConfig::getValue('peertube_channel_id');
            if (!$channelId) {
                return null;
            }

            $token = $this->authenticate();
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->get("{$this->baseUrl}/api/v1/video-channels/{$channelId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            Log::error('PeerTube getChannelInfo error: ' . $e->getMessage());
            return null;
        }
    }



    /**
     * Ottieni lista account
     */
    public function getAccounts(int $count = 20): array
    {
        try {
            $token = $this->authenticate();
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->get("{$this->baseUrl}/api/v1/accounts", [
                    'count' => $count,
                    'start' => 0,
                    'sort' => '-createdAt'
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return ['data' => []];
        } catch (Exception $e) {
            Log::error('PeerTube getAccounts error: ' . $e->getMessage());
            return ['data' => []];
        }
    }

    /**
     * Ottieni lista canali
     */
    public function getChannels(int $count = 20): array
    {
        try {
            $token = $this->authenticate();
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->get("{$this->baseUrl}/api/v1/video-channels", [
                    'count' => $count,
                    'start' => 0,
                    'sort' => '-createdAt'
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return ['data' => []];
        } catch (Exception $e) {
            Log::error('PeerTube getChannels error: ' . $e->getMessage());
            return ['data' => []];
        }
    }



    /**
     * Trova canale per nome
     */
    public function getChannelByName(string $name): ?array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/video-channels/{$name}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            Log::error('PeerTube getChannelByName error: ' . $e->getMessage());
            return null;
        }
    }
}

