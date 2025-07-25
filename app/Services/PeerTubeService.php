<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PeerTubeService
{
    protected $baseUrl;
    protected $adminUsername;
    protected $adminPassword;
    protected $accessToken;

    public function __construct()
    {
        $this->loadConfiguration();
    }

        /**
     * Carica le configurazioni dal database
     */
    protected function loadConfiguration()
    {
        try {
            $settings = \App\Models\SystemSetting::where('group', 'peertube')->pluck('value', 'key');

            Log::info('PeerTubeService - Caricamento configurazioni', [
                'settings_found' => $settings->count(),
                'settings_keys' => $settings->keys()->toArray()
            ]);

            $this->baseUrl = $settings['peertube_url'] ?? 'https://video.slamin.it';
            $this->adminUsername = $settings['peertube_admin_username'] ?? null;
            $this->adminPassword = $settings['peertube_admin_password'] ?? null;

            Log::info('PeerTubeService - Configurazioni caricate', [
                'baseUrl' => $this->baseUrl,
                'has_username' => !empty($this->adminUsername),
                'has_password' => !empty($this->adminPassword)
            ]);

        } catch (\Exception $e) {
            Log::error('Errore caricamento configurazioni PeerTube', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ottiene il token di accesso admin
     */
    public function getAdminToken(): ?string
    {
        try {
            Log::info('PeerTubeService - Tentativo ottenimento token', [
                'baseUrl' => $this->baseUrl,
                'has_username' => !empty($this->adminUsername),
                'has_password' => !empty($this->adminPassword),
            ]);

            // Step 1: Ottieni il client OAuth2
            Log::info('PeerTubeService - Step 1: Ottenimento client OAuth2');
            $clientResponse = Http::get($this->baseUrl . '/api/v1/oauth-clients/local');

            Log::info('PeerTubeService - Risposta client OAuth2', [
                'status' => $clientResponse->status(),
                'successful' => $clientResponse->successful(),
                'body' => $clientResponse->body()
            ]);

            if (!$clientResponse->successful()) {
                Log::error('Errore ottenimento client OAuth2', [
                    'status' => $clientResponse->status(),
                    'response' => $clientResponse->body()
                ]);
                return null;
            }

            $clientData = $clientResponse->json();
            $clientId = $clientData['client_id'];
            $clientSecret = $clientData['client_secret'];

            Log::info('PeerTubeService - Client OAuth2 ottenuto', [
                'client_id' => $clientId,
                'has_client_secret' => !empty($clientSecret)
            ]);

            // Step 2: Ottieni il token utente
            Log::info('PeerTubeService - Step 2: Ottenimento token utente', [
                'client_id' => $clientId,
                'grant_type' => 'password',
                'response_type' => 'code',
                'username' => $this->adminUsername,
                'password_length' => strlen($this->adminPassword),
                'url' => $this->baseUrl . '/api/v1/users/token'
            ]);

            $formData = [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'password',
                'username' => $this->adminUsername,
                'password' => $this->adminPassword,
            ];

            Log::info('PeerTubeService - Dati form inviati', [
                'form_data' => array_merge($formData, ['password' => '***HIDDEN***'])
            ]);

            $response = Http::asForm()->post($this->baseUrl . '/api/v1/users/token', $formData);

            Log::info('PeerTubeService - Risposta API token', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->accessToken = $data['access_token'];
                Log::info('PeerTubeService - Token ottenuto con successo', [
                    'token_length' => strlen($this->accessToken),
                    'token_type' => $data['token_type'] ?? 'unknown',
                    'expires_in' => $data['expires_in'] ?? 'unknown'
                ]);
                return $this->accessToken;
            }

            Log::error('Errore ottenimento token admin PeerTube', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Eccezione ottenimento token admin PeerTube', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Crea un nuovo utente su PeerTube
     */
    public function createUser(User $user, string $password): ?array
    {
        try {
            // Ottieni token admin se non presente
            if (!$this->accessToken) {
                $this->getAdminToken();
            }

            if (!$this->accessToken) {
                Log::error('Impossibile ottenere token admin per creazione utente PeerTube');
                return null;
            }

            // Prepara i dati per la creazione utente
            $username = $user->peertube_username ?? $this->generatePeerTubeUsername($user);

            // Genera un nome canale semplice e valido
            $baseChannelName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $user->name ?? $user->nickname ?? 'channel'));

            // Se il nome base è vuoto o troppo corto, usa un nome di default
            if (strlen($baseChannelName) < 3) {
                $baseChannelName = 'channel';
            }

            // Assicurati che il nome del canale sia diverso dall'username
            $channelName = $baseChannelName;
            if (strtolower($channelName) === strtolower($username)) {
                $channelName = $baseChannelName . 'ch';
            }

            // Limita la lunghezza del nome del canale
            if (strlen($channelName) > 20) {
                $channelName = substr($channelName, 0, 20);
            }

            $userData = [
                'username' => $username,
                'password' => $password,
                'email' => $user->email,
                'role' => 2, // User role
                'videoQuota' => -1, // Unlimited
                'videoQuotaDaily' => -1, // Unlimited
                'channelName' => $channelName,
            ];

            Log::info('Creazione utente PeerTube - Dati preparati', [
                'user_id' => $user->id,
                'username' => $username,
                'channelName' => $channelName,
                'email' => $user->email
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/v1/users', $userData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Utente PeerTube creato con successo', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $data['user']['id'],
                    'peertube_account_id' => $data['user']['account']['id']
                ]);

                return $data['user'];
            }

            Log::error('Errore creazione utente PeerTube', [
                'user_id' => $user->id,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Eccezione creazione utente PeerTube', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Ottiene i dettagli completi di un utente PeerTube
     */
    public function getUserDetails(int $peerTubeUserId): ?array
    {
        try {
            if (!$this->accessToken) {
                $this->getAdminToken();
            }

            if (!$this->accessToken) {
                return null;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($this->baseUrl . '/api/v1/users/' . $peerTubeUserId);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Errore ottenimento dettagli utente PeerTube', [
                'peertube_user_id' => $peerTubeUserId,
                'status' => $response->status()
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Eccezione ottenimento dettagli utente PeerTube', [
                'peertube_user_id' => $peerTubeUserId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Aggiorna i dati PeerTube di un utente nel nostro DB
     */
    public function updateUserPeerTubeData(User $user, array $peerTubeUserData): bool
    {
        try {
            Log::info('Inizio aggiornamento dati PeerTube nel DB', [
                'user_id' => $user->id,
                'peertube_user_data' => $peerTubeUserData
            ]);

            $updateData = [
                'peertube_user_id' => $peerTubeUserData['id'],
                'peertube_username' => $peerTubeUserData['username'],
                'peertube_display_name' => $peerTubeUserData['account']['displayName'] ?? null,
                'peertube_account_id' => $peerTubeUserData['account']['id'],
                'peertube_email' => $peerTubeUserData['email'],
                'peertube_role' => $peerTubeUserData['role']['id'],
                'peertube_video_quota' => $peerTubeUserData['videoQuota'],
                'peertube_video_quota_daily' => $peerTubeUserData['videoQuotaDaily'],
                'peertube_created_at' => $peerTubeUserData['createdAt'],
                'peertube_channel_id' => $peerTubeUserData['videoChannels'][0]['id'] ?? null,
            ];

            Log::info('Dati da aggiornare', [
                'user_id' => $user->id,
                'update_data' => $updateData
            ]);

            $result = $user->update($updateData);

            Log::info('Risultato aggiornamento', [
                'user_id' => $user->id,
                'update_result' => $result,
                'peertube_user_id' => $peerTubeUserData['id']
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('Errore aggiornamento dati PeerTube utente', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Genera un username PeerTube univoco
     */
    protected function generatePeerTubeUsername(User $user): string
    {
        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9._]/', '', $user->name));
        $username = $baseUsername;
        $counter = 1;

        // Verifica se l'username esiste già
        while (User::where('peertube_username', $username)->exists()) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Salva la password PeerTube criptata
     */
    public function savePeerTubePassword(User $user, string $password): bool
    {
        try {
            $user->update([
                'peertube_password' => Hash::make($password)
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Errore salvataggio password PeerTube', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Processo completo di creazione utente PeerTube
     */
    public function createPeerTubeUser(User $user, string $password): bool
    {
        try {
            Log::info('Inizio creazione utente PeerTube', ['user_id' => $user->id]);

            // 1. Crea utente su PeerTube
            $peerTubeUser = $this->createUser($user, $password);

            if (!$peerTubeUser) {
                // L'utente potrebbe esistere già, prova a cercarlo
                Log::info('Utente non creato, potrebbe esistere già. Cerco utente esistente...', ['user_id' => $user->id]);

                // Cerca l'utente per username o email
                $existingUser = $this->findExistingPeerTubeUser($user);
                if ($existingUser) {
                    $peerTubeUser = $existingUser;
                    Log::info('Utente PeerTube esistente trovato', [
                        'user_id' => $user->id,
                        'peertube_user_id' => $peerTubeUser['id']
                    ]);
                } else {
                    Log::error('Utente PeerTube non trovato e non creato', ['user_id' => $user->id]);
                    return false;
                }
            }

            // 2. Ottieni dettagli completi
            $userDetails = $this->getUserDetails($peerTubeUser['id']);
            if (!$userDetails) {
                Log::error('Impossibile ottenere dettagli utente PeerTube', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $peerTubeUser['id']
                ]);
                return false;
            }

            // 3. Aggiorna dati nel nostro DB
            if (!$this->updateUserPeerTubeData($user, $userDetails)) {
                Log::error('Impossibile aggiornare dati PeerTube nel DB', ['user_id' => $user->id]);
                return false;
            }

            // 4. Salva password criptata
            if (!$this->savePeerTubePassword($user, $password)) {
                Log::error('Impossibile salvare password PeerTube', ['user_id' => $user->id]);
                return false;
            }

            Log::info('Utente PeerTube creato/aggiornato con successo', [
                'user_id' => $user->id,
                'peertube_user_id' => $peerTubeUser['id']
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Errore processo creazione utente PeerTube', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verifica se le configurazioni PeerTube sono valide
     */
    public function validateConfiguration(): bool
    {
        return !empty($this->baseUrl) &&
               !empty($this->adminUsername) &&
               !empty($this->adminPassword);
    }

    /**
     * Verifica se PeerTube è configurato
     */
    public function isConfigured(): bool
    {
        return $this->validateConfiguration();
    }

    /**
     * Cerca un utente PeerTube esistente per username o email
     */
    public function findExistingPeerTubeUser(User $user): ?array
    {
        try {
            if (!$this->accessToken) {
                $this->getAdminToken();
            }

            if (!$this->accessToken) {
                return null;
            }

            // Cerca per email
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($this->baseUrl . '/api/v1/users', [
                'search' => $user->email
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'])) {
                    foreach ($data['data'] as $peerTubeUser) {
                        if ($peerTubeUser['email'] === $user->email) {
                            Log::info('Utente PeerTube trovato per email', [
                                'user_id' => $user->id,
                                'peertube_user_id' => $peerTubeUser['id'],
                                'email' => $user->email
                            ]);
                            return $peerTubeUser;
                        }
                    }
                }
            }

            // Cerca per username
            $username = $user->peertube_username ?? $this->generatePeerTubeUsername($user);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($this->baseUrl . '/api/v1/users', [
                'search' => $username
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'])) {
                    foreach ($data['data'] as $peerTubeUser) {
                        if ($peerTubeUser['username'] === $username) {
                            Log::info('Utente PeerTube trovato per username', [
                                'user_id' => $user->id,
                                'peertube_user_id' => $peerTubeUser['id'],
                                'username' => $username
                            ]);
                            return $peerTubeUser;
                        }
                    }
                }
            }

            Log::info('Utente PeerTube non trovato', [
                'user_id' => $user->id,
                'email' => $user->email,
                'username' => $username
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Errore ricerca utente PeerTube esistente', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Ottiene l'URL base del server PeerTube
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Testa la connessione a PeerTube
     */
    public function testConnection(): bool
    {
        try {
            $token = $this->getAdminToken();
            return !empty($token);
        } catch (Exception $e) {
            Log::error('Test connessione PeerTube fallito', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Upload di un video su PeerTube usando Guzzle
     */
    public function uploadVideo(User $user, string $videoPath, array $videoData): ?array
    {
        try {
            Log::info('Inizio upload video PeerTube con Guzzle', [
                'user_id' => $user->id,
                'video_path' => $videoPath,
                'video_name' => $videoData['name'] ?? 'Unknown'
            ]);

            // Verifica che l'utente abbia un account PeerTube
            if (!$user->hasPeerTubeAccount()) {
                Log::error('Utente senza account PeerTube', ['user_id' => $user->id]);
                return null;
            }

            // Ottieni token admin se non presente
            if (!$this->accessToken) {
                $this->getAdminToken();
            }

            if (!$this->accessToken) {
                Log::error('Impossibile ottenere token admin per upload video PeerTube');
                return null;
            }

            // Verifica che il file esista
            if (!file_exists($videoPath)) {
                Log::error('File video non trovato', ['path' => $videoPath]);
                return null;
            }

            // Prepara i dati per l'upload seguendo la documentazione
            $multipartData = [
                [
                    'name' => 'videofile',
                    'contents' => fopen($videoPath, 'r'),
                    'filename' => basename($videoPath)
                ],
                [
                    'name' => 'channelId',
                    'contents' => $user->peertube_channel_id
                ],
                [
                    'name' => 'name',
                    'contents' => $videoData['name'] ?? 'Video senza titolo'
                ]
            ];

            // Aggiungi campi opzionali se presenti
            if (!empty($videoData['description'])) {
                $multipartData[] = [
                    'name' => 'description',
                    'contents' => $videoData['description']
                ];
            }

            if (!empty($videoData['privacy'])) {
                $multipartData[] = [
                    'name' => 'privacy',
                    'contents' => $videoData['privacy']
                ];
            }

            if (!empty($videoData['category'])) {
                $multipartData[] = [
                    'name' => 'category',
                    'contents' => $videoData['category']
                ];
            }

            if (!empty($videoData['licence'])) {
                $multipartData[] = [
                    'name' => 'licence',
                    'contents' => $videoData['licence']
                ];
            }

            if (!empty($videoData['language'])) {
                $multipartData[] = [
                    'name' => 'language',
                    'contents' => $videoData['language']
                ];
            }

            if (isset($videoData['downloadEnabled'])) {
                $multipartData[] = [
                    'name' => 'downloadEnabled',
                    'contents' => $videoData['downloadEnabled'] ? 'true' : 'false'
                ];
            }

            if (isset($videoData['commentsPolicy'])) {
                $multipartData[] = [
                    'name' => 'commentsPolicy',
                    'contents' => $videoData['commentsPolicy']
                ];
            }

            if (isset($videoData['nsfw'])) {
                $multipartData[] = [
                    'name' => 'nsfw',
                    'contents' => $videoData['nsfw'] ? 'true' : 'false'
                ];
            }

            // Aggiungi tags se presenti
            if (!empty($videoData['tags']) && is_array($videoData['tags'])) {
                foreach ($videoData['tags'] as $tag) {
                    $multipartData[] = [
                        'name' => 'tags[]',
                        'contents' => $tag
                    ];
                }
            }

            // Aggiungi thumbnail se presente
            if (!empty($videoData['thumbnail_path']) && file_exists($videoData['thumbnail_path'])) {
                $multipartData[] = [
                    'name' => 'thumbnailfile',
                    'contents' => fopen($videoData['thumbnail_path'], 'r'),
                    'filename' => basename($videoData['thumbnail_path'])
                ];
            }

            Log::info('Upload video PeerTube - Dati preparati con Guzzle', [
                'user_id' => $user->id,
                'channel_id' => $user->peertube_channel_id,
                'video_name' => $videoData['name'] ?? 'Unknown',
                'multipart_fields' => count($multipartData)
            ]);

            // Crea client Guzzle con timeout esteso
            $client = new Client([
                'timeout' => 300, // 5 minuti
                'connect_timeout' => 30,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'User-Agent' => 'Slamin-PeerTube-Integration/1.0'
                ]
            ]);

            // Esegui l'upload seguendo esattamente la documentazione
            $response = $client->post($this->baseUrl . '/api/v1/videos/upload', [
                'multipart' => $multipartData
            ]);

            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);

            Log::info('Video PeerTube caricato con successo via Guzzle', [
                'user_id' => $user->id,
                'status_code' => $response->getStatusCode(),
                'response_data' => $responseData
            ]);

            if (isset($responseData['video'])) {
                return $responseData['video'];
            }

            Log::error('Risposta PeerTube non contiene dati video', [
                'user_id' => $user->id,
                'response' => $responseData
            ]);

            return null;

        } catch (RequestException $e) {
            Log::error('Errore Guzzle upload video PeerTube', [
                'user_id' => $user->id,
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown',
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'no response',
                'error' => $e->getMessage()
            ]);
            return null;
        } catch (Exception $e) {
            Log::error('Eccezione upload video PeerTube', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}
