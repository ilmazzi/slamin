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
     * Crea un client HTTP con le giuste configurazioni SSL
     */
    protected function createHttpClient($asForm = false)
    {
        $client = $asForm ? Http::asForm()->timeout(30) : Http::timeout(30);
        
        // Disabilita verifica SSL se in sviluppo o se specificato
        if (config('app.env') === 'local' || config('peertube.verify_ssl', true) === false) {
            $client = $client->withoutVerifying();
            Log::debug('PeerTubeService - SSL verification disabled (env: ' . config('app.env') . ')');
        }
        
        return $client;
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
            
            // Disabilita verifica SSL se in sviluppo o se specificato nel .env
            $httpClient = Http::timeout(30);
            if (config('app.env') === 'local' || config('peertube.verify_ssl', true) === false) {
                $httpClient = $httpClient->withoutVerifying();
                Log::info('PeerTubeService - Verifica SSL disabilitata (ambiente: ' . config('app.env') . ')');
            }
            
            $clientResponse = $httpClient->get($this->baseUrl . '/api/v1/oauth-clients/local');

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

            // Usa lo stesso client con SSL verificato/non verificato
            $tokenClient = Http::asForm()->timeout(30);
            if (config('app.env') === 'local' || config('peertube.verify_ssl', true) === false) {
                $tokenClient = $tokenClient->withoutVerifying();
            }
            
            $response = $tokenClient->post($this->baseUrl . '/api/v1/users/token', $formData);

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
            // Sempre genera un username univoco per evitare conflitti
            $username = $this->generatePeerTubeUsername($user);

            // Genera un nome canale univoco
            $baseChannelName = 'channel_' . $user->id;
            $channelName = $baseChannelName;
            $counter = 1;

            // Verifica se il canale esiste già su PeerTube
            if ($this->accessToken) {
                $maxAttempts = 10;
                $attempts = 0;

                while ($attempts < $maxAttempts) {
                    try {
                        $response = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $this->accessToken,
                        ])->get($this->baseUrl . '/api/v1/video-channels', [
                            'search' => $channelName
                        ]);

                        if ($response->successful()) {
                            $channels = $response->json('data', []);
                            $channelExists = false;

                            foreach ($channels as $channel) {
                                if ($channel['name'] === $channelName) {
                                    $channelExists = true;
                                    break;
                                }
                            }

                            if (!$channelExists) {
                                break; // Nome canale disponibile
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning('Errore verifica canale su PeerTube', [
                            'channel_name' => $channelName,
                            'error' => $e->getMessage()
                        ]);
                        break; // In caso di errore, usa il nome corrente
                    }

                    $channelName = $baseChannelName . '_' . $counter;
                    $counter++;
                    $attempts++;
                }
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

        // Verifica se l'username esiste già nel nostro DB
        while (User::where('peertube_username', $username)->exists()) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
        }

        // Verifica anche su PeerTube se possibile
        if ($this->accessToken) {
            $maxAttempts = 10; // Evita loop infiniti
            $attempts = 0;

            while ($attempts < $maxAttempts) {
                try {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->accessToken,
                    ])->get($this->baseUrl . '/api/v1/users', [
                        'search' => $username
                    ]);

                    if ($response->successful()) {
                        $users = $response->json('data', []);
                        $usernameExists = false;

                        foreach ($users as $peerTubeUser) {
                            if ($peerTubeUser['username'] === $username) {
                                $usernameExists = true;
                                break;
                            }
                        }

                        if (!$usernameExists) {
                            break; // Username disponibile
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Errore verifica username su PeerTube', [
                        'username' => $username,
                        'error' => $e->getMessage()
                    ]);
                    break; // In caso di errore, usa l'username corrente
                }

                $username = $baseUsername . '_' . $counter;
                $counter++;
                $attempts++;
            }
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
            Log::info('Inizio creazione utente PeerTube', [
                'user_id' => $user->id,
                'email' => $user->email,
                'password_length' => strlen($password)
            ]);

            // Controlla se l'utente ha già un account PeerTube
            $isNewUser = !$user->peertube_user_id;

            // 1. Crea utente su PeerTube
            Log::info('Passo 1: Creazione utente su PeerTube', ['user_id' => $user->id]);
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
            } else {
                Log::info('Utente PeerTube creato con successo', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $peerTubeUser['id']
                ]);
            }

            // 2. Ottieni dettagli completi
            Log::info('Passo 2: Ottenimento dettagli utente PeerTube', [
                'user_id' => $user->id,
                'peertube_user_id' => $peerTubeUser['id']
            ]);
            $userDetails = $this->getUserDetails($peerTubeUser['id']);
            if (!$userDetails) {
                Log::error('Impossibile ottenere dettagli utente PeerTube', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $peerTubeUser['id']
                ]);
                return false;
            }
            Log::info('Dettagli utente PeerTube ottenuti', [
                'user_id' => $user->id,
                'peertube_user_id' => $peerTubeUser['id'],
                'username' => $userDetails['username'] ?? 'N/A'
            ]);

            // 3. Aggiorna dati nel nostro DB
            Log::info('Passo 3: Aggiornamento dati nel DB', ['user_id' => $user->id]);
            if (!$this->updateUserPeerTubeData($user, $userDetails)) {
                Log::error('Impossibile aggiornare dati PeerTube nel DB', ['user_id' => $user->id]);
                return false;
            }
            Log::info('Dati PeerTube aggiornati nel DB', ['user_id' => $user->id]);

            // 4. Salva password criptata
            Log::info('Passo 4: Salvataggio password criptata', ['user_id' => $user->id]);
            if (!$this->savePeerTubePassword($user, $password)) {
                Log::error('Impossibile salvare password PeerTube', ['user_id' => $user->id]);
                return false;
            }
            Log::info('Password PeerTube salvata nel DB', ['user_id' => $user->id]);

            Log::info('Utente PeerTube creato/aggiornato con successo', [
                'user_id' => $user->id,
                'peertube_user_id' => $peerTubeUser['id']
            ]);

            // 5. Verifica email se l'utente è appena stato creato
            // NOTA: Temporaneamente disabilitata perché l'API PeerTube richiede una stringa di verifica
            // che non possiamo generare automaticamente. L'utente dovrà verificare manualmente.
            if ($isNewUser) {
                Log::info('Passo 5: Verifica email PeerTube - DISABILITATA', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $peerTubeUser['id'],
                    'reason' => 'API richiede verificationString che non possiamo generare automaticamente'
                ]);

                Log::info('Utente dovrà verificare email manualmente su PeerTube', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $peerTubeUser['id'],
                    'peertube_url' => $this->baseUrl
                ]);
            }

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
     * Verifica l'email di un utente PeerTube appena creato
     */
    public function verifyPeerTubeEmail(int $peerTubeUserId): bool
    {
        try {
            if (!$this->accessToken) {
                $this->getAdminToken();
            }

            if (!$this->accessToken) {
                Log::error('Impossibile ottenere token admin per verifica email PeerTube', [
                    'peertube_user_id' => $peerTubeUserId
                ]);
                return false;
            }

            Log::info('Tentativo verifica email PeerTube', [
                'peertube_user_id' => $peerTubeUserId
            ]);

            // Prima ottieni i dettagli dell'utente per vedere se ha una stringa di verifica
            $userResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->get($this->baseUrl . '/api/v1/users/' . $peerTubeUserId);

            if (!$userResponse->successful()) {
                Log::error('Errore ottenimento dettagli utente per verifica email', [
                    'peertube_user_id' => $peerTubeUserId,
                    'status' => $userResponse->status(),
                    'response' => $userResponse->body()
                ]);
                return false;
            }

            $userData = $userResponse->json();
            Log::info('Dettagli utente per verifica email', [
                'peertube_user_id' => $peerTubeUserId,
                'emailVerified' => $userData['emailVerified'] ?? 'N/A',
                'pendingEmail' => $userData['pendingEmail'] ?? 'N/A'
            ]);

            // Se l'email è già verificata, non serve fare nulla
            if ($userData['emailVerified'] === true) {
                Log::info('Email già verificata', [
                    'peertube_user_id' => $peerTubeUserId
                ]);
                return true;
            }

            // Prova a verificare senza stringa di verifica (solo per admin)
            $verifyResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
            ])->post($this->baseUrl . '/api/v1/users/' . $peerTubeUserId . '/verify-email', [
                'isPendingEmail' => true
            ]);

            if ($verifyResponse->successful()) {
                Log::info('Email PeerTube verificata con successo', [
                    'peertube_user_id' => $peerTubeUserId
                ]);
                return true;
            }

            Log::error('Errore verifica email PeerTube', [
                'peertube_user_id' => $peerTubeUserId,
                'status' => $verifyResponse->status(),
                'response' => $verifyResponse->body()
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('Eccezione verifica email PeerTube', [
                'peertube_user_id' => $peerTubeUserId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
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
     * Cerca un utente PeerTube per username
     */
    public function findUserByUsername(string $username): ?array
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
            ])->get($this->baseUrl . '/api/v1/users', [
                'search' => $username
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'])) {
                    foreach ($data['data'] as $peerTubeUser) {
                        if ($peerTubeUser['username'] === $username) {
                            Log::info('Utente PeerTube trovato per username', [
                                'username' => $username,
                                'peertube_user_id' => $peerTubeUser['id']
                            ]);
                            return $peerTubeUser;
                        }
                    }
                }
            }

            Log::info('Utente PeerTube non trovato per username', ['username' => $username]);
            return null;
        } catch (Exception $e) {
            Log::error('Errore ricerca utente PeerTube per username', [
                'username' => $username,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Cerca un utente PeerTube per email
     */
    public function findUserByEmail(string $email): ?array
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
            ])->get($this->baseUrl . '/api/v1/users', [
                'search' => $email
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'])) {
                    foreach ($data['data'] as $peerTubeUser) {
                        if ($peerTubeUser['email'] === $email) {
                            Log::info('Utente PeerTube trovato per email', [
                                'email' => $email,
                                'peertube_user_id' => $peerTubeUser['id']
                            ]);
                            return $peerTubeUser;
                        }
                    }
                }
            }

            Log::info('Utente PeerTube non trovato per email', ['email' => $email]);
            return null;
        } catch (Exception $e) {
            Log::error('Errore ricerca utente PeerTube per email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Ottiene i dettagli di un canale PeerTube
     */
    public function getChannelDetails(int $channelId): ?array
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
            ])->get($this->baseUrl . '/api/v1/video-channels/' . $channelId);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Dettagli canale PeerTube ottenuti', [
                    'channel_id' => $channelId,
                    'channel_name' => $data['displayName'] ?? 'N/A'
                ]);
                return $data;
            }

            Log::error('Errore ottenimento dettagli canale PeerTube', [
                'channel_id' => $channelId,
                'status' => $response->status()
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Eccezione ottenimento dettagli canale PeerTube', [
                'channel_id' => $channelId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }


    /**
     * Elimina un utente PeerTube per email
     */
    public function deleteUserByEmail(string $email): ?array
    {
        try {
            // Prima trova l'utente per email
            $user = $this->findUserByEmail($email);
            if (!$user) {
                Log::info('Utente PeerTube non trovato per email', ['email' => $email]);
                return null;
            }

            $peerTubeUserId = $user['id'];
            Log::info('Utente PeerTube trovato per eliminazione', [
                'email' => $email,
                'peertube_user_id' => $peerTubeUserId,
                'username' => $user['username']
            ]);

            // Elimina l'utente
            $success = $this->deleteUser($peerTubeUserId);

            if ($success) {
                return [
                    'success' => true,
                    'deleted_user' => $user,
                    'message' => 'Utente PeerTube eliminato con successo'
                ];
            } else {
                return [
                    'success' => false,
                    'user' => $user,
                    'message' => 'Errore durante l\'eliminazione dell\'utente PeerTube'
                ];
            }

        } catch (Exception $e) {
            Log::error('Eccezione eliminazione utente PeerTube per email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ];
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

    /**
     * Elimina un video da PeerTube
     */
    public function deleteVideo(string $peerTubeVideoId): bool
    {
        try {
            Log::info('PeerTubeService - Tentativo eliminazione video', [
                'peer_tube_video_id' => $peerTubeVideoId
            ]);

            // Ottieni il token admin se non è già disponibile
            if (!$this->accessToken) {
                $this->accessToken = $this->getAdminToken();
                if (!$this->accessToken) {
                    Log::error('PeerTubeService - Impossibile ottenere token admin per eliminazione video', [
                        'peer_tube_video_id' => $peerTubeVideoId
                    ]);
                    return false;
                }
            }

            // Crea client Guzzle
            $client = new Client([
                'timeout' => 30,
                'connect_timeout' => 10,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'User-Agent' => 'Slamin-PeerTube-Integration/1.0'
                ]
            ]);

            // Esegui la richiesta DELETE
            $response = $client->delete($this->baseUrl . '/api/v1/videos/' . $peerTubeVideoId);

            Log::info('PeerTubeService - Risposta eliminazione video', [
                'peer_tube_video_id' => $peerTubeVideoId,
                'status_code' => $response->getStatusCode(),
                'successful' => $response->getStatusCode() === 204
            ]);

            // PeerTube restituisce 204 No Content per eliminazioni riuscite
            if ($response->getStatusCode() === 204) {
                Log::info('PeerTubeService - Video eliminato con successo', [
                    'peer_tube_video_id' => $peerTubeVideoId
                ]);
                return true;
            }

            Log::error('PeerTubeService - Errore eliminazione video', [
                'peer_tube_video_id' => $peerTubeVideoId,
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody()->getContents()
            ]);

            return false;

        } catch (RequestException $e) {
            Log::error('PeerTubeService - Errore Guzzle eliminazione video', [
                'peer_tube_video_id' => $peerTubeVideoId,
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown',
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'no response',
                'error' => $e->getMessage()
            ]);
            return false;
        } catch (Exception $e) {
            Log::error('PeerTubeService - Eccezione eliminazione video', [
                'peer_tube_video_id' => $peerTubeVideoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Elimina un video usando l'UUID
     */
    public function deleteVideoByUuid(string $peerTubeUuid): bool
    {
        try {
            Log::info('PeerTubeService - Tentativo eliminazione video per UUID', [
                'peer_tube_uuid' => $peerTubeUuid
            ]);

            // Ottieni il token admin se non è già disponibile
            if (!$this->accessToken) {
                $this->accessToken = $this->getAdminToken();
                if (!$this->accessToken) {
                    Log::error('PeerTubeService - Impossibile ottenere token admin per eliminazione video UUID', [
                        'peer_tube_uuid' => $peerTubeUuid
                    ]);
                    return false;
                }
            }

            // Crea client Guzzle
            $client = new Client([
                'timeout' => 30,
                'connect_timeout' => 10,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'User-Agent' => 'Slamin-PeerTube-Integration/1.0'
                ]
            ]);

            // Esegui la richiesta DELETE usando l'UUID
            $response = $client->delete($this->baseUrl . '/api/v1/videos/' . $peerTubeUuid);

            Log::info('PeerTubeService - Risposta eliminazione video UUID', [
                'peer_tube_uuid' => $peerTubeUuid,
                'status_code' => $response->getStatusCode(),
                'successful' => $response->getStatusCode() === 204
            ]);

            // PeerTube restituisce 204 No Content per eliminazioni riuscite
            if ($response->getStatusCode() === 204) {
                Log::info('PeerTubeService - Video eliminato con successo per UUID', [
                    'peer_tube_uuid' => $peerTubeUuid
                ]);
                return true;
            }

            Log::error('PeerTubeService - Errore eliminazione video UUID', [
                'peer_tube_uuid' => $peerTubeUuid,
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody()->getContents()
            ]);

            return false;

        } catch (RequestException $e) {
            Log::error('PeerTubeService - Errore Guzzle eliminazione video UUID', [
                'peer_tube_uuid' => $peerTubeUuid,
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown',
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'no response',
                'error' => $e->getMessage()
            ]);
            return false;
        } catch (Exception $e) {
            Log::error('PeerTubeService - Eccezione eliminazione video UUID', [
                'peer_tube_uuid' => $peerTubeUuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Elimina un utente da PeerTube
     */
    public function deleteUser(int $peerTubeUserId): bool
    {
        try {
            Log::info('PeerTubeService - Tentativo eliminazione utente', [
                'peer_tube_user_id' => $peerTubeUserId
            ]);

            // Ottieni il token admin se non è già disponibile
            if (!$this->accessToken) {
                $this->accessToken = $this->getAdminToken();
                if (!$this->accessToken) {
                    Log::error('PeerTubeService - Impossibile ottenere token admin per eliminazione utente', [
                        'peer_tube_user_id' => $peerTubeUserId
                    ]);
                    return false;
                }
            }

            // Crea client Guzzle
            $client = new Client([
                'timeout' => 30,
                'connect_timeout' => 10,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'User-Agent' => 'Slamin-PeerTube-Integration/1.0'
                ]
            ]);

            // Esegui la richiesta DELETE
            $response = $client->delete($this->baseUrl . '/api/v1/users/' . $peerTubeUserId);

            Log::info('PeerTubeService - Risposta eliminazione utente', [
                'peer_tube_user_id' => $peerTubeUserId,
                'status_code' => $response->getStatusCode(),
                'successful' => $response->getStatusCode() === 204
            ]);

            // PeerTube restituisce 204 No Content per eliminazioni riuscite
            if ($response->getStatusCode() === 204) {
                Log::info('PeerTubeService - Utente eliminato con successo', [
                    'peer_tube_user_id' => $peerTubeUserId
                ]);
                return true;
            }

            Log::error('PeerTubeService - Errore eliminazione utente', [
                'peer_tube_user_id' => $peerTubeUserId,
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody()->getContents()
            ]);

            return false;

        } catch (RequestException $e) {
            Log::error('PeerTubeService - Errore Guzzle eliminazione utente', [
                'peer_tube_user_id' => $peerTubeUserId,
                'status_code' => $e->getResponse() ? $e->getResponse()->getStatusCode() : 'unknown',
                'response_body' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'no response',
                'error' => $e->getMessage()
            ]);
            return false;
        } catch (Exception $e) {
            Log::error('PeerTubeService - Eccezione eliminazione utente', [
                'peer_tube_user_id' => $peerTubeUserId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Elimina un utente usando l'ID locale
     */
    public function deleteUserByLocalId(int $localUserId): bool
    {
        try {
            Log::info('PeerTubeService - Tentativo eliminazione utente per ID locale', [
                'local_user_id' => $localUserId
            ]);

            // Trova l'utente nel database locale
            $user = User::find($localUserId);
            if (!$user) {
                Log::error('PeerTubeService - Utente non trovato nel database locale', [
                    'local_user_id' => $localUserId
                ]);
                return false;
            }

            // Verifica se l'utente ha un account PeerTube
            if (!$user->peertube_user_id) {
                Log::info('PeerTubeService - Utente non ha account PeerTube, eliminazione locale completata', [
                    'local_user_id' => $localUserId,
                    'user_email' => $user->email
                ]);
                return true; // Non è un errore se l'utente non ha account PeerTube
            }

            // Elimina l'utente da PeerTube
            $deleted = $this->deleteUser($user->peertube_user_id);

            if ($deleted) {
                Log::info('PeerTubeService - Utente eliminato da PeerTube con successo', [
                    'local_user_id' => $localUserId,
                    'peer_tube_user_id' => $user->peertube_user_id,
                    'user_email' => $user->email
                ]);
            } else {
                Log::warning('PeerTubeService - Impossibile eliminare utente da PeerTube, continuando con eliminazione locale', [
                    'local_user_id' => $localUserId,
                    'peer_tube_user_id' => $user->peertube_user_id,
                    'user_email' => $user->email
                ]);
            }

            return true; // Ritorna true anche se PeerTube fallisce, per permettere l'eliminazione locale

        } catch (Exception $e) {
            Log::error('PeerTubeService - Eccezione eliminazione utente per ID locale', [
                'local_user_id' => $localUserId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
