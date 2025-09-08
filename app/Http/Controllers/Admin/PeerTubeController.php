<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class PeerTubeController extends Controller
{
    protected $peerTubeService;

    public function __construct(PeerTubeService $peerTubeService)
    {
        $this->peerTubeService = $peerTubeService;
    }

    /**
     * Mostra il pannello di configurazione PeerTube
     */
    public function index()
    {
        $settings = \App\Models\SystemSetting::where('group', 'peertube')
            ->pluck('value', 'key')
            ->toArray();

        $isConfigured = $this->peerTubeService->validateConfiguration();
        $connectionTest = null;

        if ($isConfigured) {
            $connectionTest = $this->testConnection();
        }

        return view('admin.peertube.index', compact('settings', 'isConfigured', 'connectionTest'));
    }

    /**
     * Aggiorna le configurazioni PeerTube
     */
    public function update(Request $request)
    {
        $request->validate([
            'peertube_url' => 'required|url',
            'peertube_admin_username' => 'required|string|max:255',
            'peertube_admin_password' => 'required|string|min:6',
        ]);

        try {
            Log::info('Aggiornamento configurazioni PeerTube - Dati ricevuti', [
                'url' => $request->peertube_url,
                'has_username' => !empty($request->peertube_admin_username),
                'has_password' => !empty($request->peertube_admin_password),
            ]);

            // Aggiorna le configurazioni nel database
            $this->updateSystemSettings([
                'peertube_url' => $request->peertube_url,
                'peertube_admin_username' => $request->peertube_admin_username,
                'peertube_admin_password' => $request->peertube_admin_password,
            ]);

            // Verifica che siano state salvate
            $savedSettings = \App\Models\SystemSetting::where('group', 'peertube')->get();
            $settingsKeys = $savedSettings->pluck('key')->toArray();
            Log::info('Configurazioni PeerTube salvate', [
                'count' => $savedSettings->count(),
                'settings' => $settingsKeys
            ]);

            // Pulisci la cache delle configurazioni
            Cache::forget('system_settings');

            return redirect()->route('admin.peertube.index')
                ->with('success', 'Configurazioni PeerTube aggiornate con successo!');

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento configurazioni PeerTube', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.peertube.index')
                ->with('error', 'Errore durante l\'aggiornamento delle configurazioni: ' . $e->getMessage());
        }
    }

    /**
     * Testa la connessione a PeerTube
     */
    public function testConnection()
    {
        try {
            $token = $this->peerTubeService->getAdminToken();

            if ($token) {
                return [
                    'success' => true,
                    'message' => 'Connessione a PeerTube stabilita con successo!',
                    'token' => substr($token, 0, 20) . '...'
                ];
            }

            return [
                'success' => false,
                'message' => 'Impossibile ottenere il token di accesso. Verifica le credenziali admin.'
            ];

        } catch (\Exception $e) {
            Log::error('Errore test connessione PeerTube', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Errore di connessione: ' . $e->getMessage()
            ];
        }
    }

        /**
     * API per testare la connessione
     */
    public function testConnectionApi()
    {
        try {
            // Debug: verifica se le configurazioni sono caricate
            $settings = \App\Models\SystemSetting::where('group', 'peertube')->pluck('value', 'key');

            Log::info('Test connessione PeerTube - Configurazioni caricate', [
                'settings' => $settings->toArray(),
                'has_url' => $settings->has('peertube_url'),
                'has_username' => $settings->has('peertube_admin_username'),
                'has_password' => $settings->has('peertube_admin_password'),
            ]);

            $result = $this->testConnection();

            Log::info('Test connessione PeerTube - Risultato', [
                'result' => $result
            ]);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Errore API test connessione PeerTube', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostra le statistiche degli utenti PeerTube
     */
    public function statistics()
    {
        $stats = [
            'total_users' => \App\Models\User::whereNotNull('peertube_user_id')->count(),
            'users_with_channel' => \App\Models\User::whereNotNull('peertube_channel_id')->count(),
            'recent_users' => \App\Models\User::whereNotNull('peertube_user_id')
                ->where('peertube_created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        return view('admin.peertube.statistics', compact('stats'));
    }

    /**
     * Lista degli utenti PeerTube
     */
    public function users()
    {
        $users = \App\Models\User::whereNotNull('peertube_user_id')
            ->with('roles')
            ->orderBy('peertube_created_at', 'desc')
            ->paginate(20);

        return view('admin.peertube.users', compact('users'));
    }

    /**
     * Gestione utenti PeerTube - Vista principale
     */
    public function manageUsers()
    {
        // Tutti gli utenti del sistema per il dropdown
        $allUsers = \App\Models\User::with('roles')
            ->orderBy('name')
            ->get();

        // Utenti con account PeerTube
        $peertubeUsers = \App\Models\User::whereNotNull('peertube_user_id')
            ->with('roles')
            ->orderBy('peertube_created_at', 'desc')
            ->get();

        // Log recenti per debug
        $recentLogs = $this->getRecentPeerTubeLogs();

        return view('admin.peertube.manage-users', compact('allUsers', 'peertubeUsers', 'recentLogs'));
    }

    /**
     * Mostra dettagli di un utente PeerTube
     */
    public function showUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $user = \App\Models\User::with('roles')->findOrFail($request->user_id);

        // Dettagli PeerTube se esiste
        $peertubeDetails = null;
        if ($user->peertube_user_id) {
            try {
                $peertubeDetails = $this->peerTubeService->getUserDetails($user->peertube_user_id);
            } catch (\Exception $e) {
                Log::error('Errore ottenimento dettagli PeerTube', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $user->peertube_user_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'user' => $user,
            'peertube_details' => $peertubeDetails,
            'has_peertube_account' => !is_null($user->peertube_user_id),
            'peertube_created_at' => $user->peertube_created_at?->format('d/m/Y H:i:s'),
            'peertube_channel_id' => $user->peertube_channel_id,
            'peertube_account_id' => $user->peertube_account_id,
            'peertube_username' => $user->peertube_username,
        ]);
    }

    /**
     * Crea o ricrea account PeerTube per un utente
     */
    public function createUserAccount(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'force_recreate' => 'boolean',
            'link_existing' => 'boolean'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);
        $forceRecreate = $request->boolean('force_recreate', false);
        $linkExisting = $request->boolean('link_existing', false);

        try {
            Log::info('Inizio creazione account PeerTube via admin', [
                'user_id' => $user->id,
                'email' => $user->email,
                'force_recreate' => $forceRecreate,
                'link_existing' => $linkExisting,
                'existing_peertube_id' => $user->peertube_user_id
            ]);

            // Prima verifica se esiste già un utente con questa email
            $existingUser = $this->peerTubeService->findUserByEmail($user->email);

            if ($existingUser && !$forceRecreate && !$linkExisting) {
                Log::info('Utente PeerTube esistente trovato per email', [
                    'user_id' => $user->id,
                    'existing_peertube_id' => $existingUser['id'],
                    'existing_username' => $existingUser['username']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Esiste già un utente PeerTube con questa email!',
                    'existing_user' => [
                        'peertube_user_id' => $existingUser['id'],
                        'username' => $existingUser['username'],
                        'email' => $existingUser['email'],
                        'created_at' => $existingUser['createdAt'] ?? null
                    ],
                    'suggestions' => [
                        'link_existing' => 'Collega l\'account esistente',
                        'force_recreate' => 'Forza la ricreazione (elimina l\'account esistente)',
                        'change_email' => 'Cambia l\'email dell\'utente locale'
                    ]
                ]);
            }

            // Se link_existing è true, collega l'account esistente
            if ($linkExisting && $existingUser) {
                Log::info('Collegamento account PeerTube esistente', [
                    'user_id' => $user->id,
                    'existing_peertube_id' => $existingUser['id']
                ]);

                // Aggiorna i dati dell'utente con quelli dell'account esistente
                $updateData = [
                    'peertube_user_id' => $existingUser['id'],
                    'peertube_username' => $existingUser['username'],
                    'peertube_created_at' => now(),
                ];

                // Se l'utente esistente ha un account/canale, aggiorna anche quelli
                if (isset($existingUser['account'])) {
                    if (isset($existingUser['account']['id'])) {
                        $updateData['peertube_account_id'] = $existingUser['account']['id'];
                    }
                    if (isset($existingUser['account']['name'])) {
                        $updateData['peertube_channel_id'] = $existingUser['account']['name'];
                    }
                }

                $user->update($updateData);

                Log::info('Account PeerTube esistente collegato con successo', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $existingUser['id']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Account PeerTube esistente collegato con successo!',
                    'user_data' => [
                        'peertube_user_id' => $user->peertube_user_id,
                        'peertube_channel_id' => $user->peertube_channel_id,
                        'peertube_account_id' => $user->peertube_account_id,
                        'peertube_username' => $user->peertube_username,
                        'peertube_created_at' => $user->peertube_created_at?->format('d/m/Y H:i:s'),
                    ]
                ]);
            }

            // Genera password casuale per PeerTube
            $peertubePassword = \Illuminate\Support\Str::random(12);

            // Se force_recreate è true, elimina l'utente esistente da PeerTube
            if ($forceRecreate && $existingUser) {
                Log::info('Eliminazione utente PeerTube esistente per ricreazione', [
                    'user_id' => $user->id,
                    'existing_peertube_id' => $existingUser['id']
                ]);

                $deleteResult = $this->peerTubeService->deleteUserByEmail($user->email);
                if (!$deleteResult || !$deleteResult['success']) {
                    Log::error('Fallimento eliminazione utente PeerTube esistente', [
                        'user_id' => $user->id,
                        'result' => $deleteResult
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossibile eliminare l\'utente PeerTube esistente: ' . ($deleteResult['message'] ?? 'Errore sconosciuto')
                    ], 500);
                }

                Log::info('Utente PeerTube esistente eliminato con successo', [
                    'user_id' => $user->id,
                    'deleted_peertube_id' => $existingUser['id']
                ]);
            }

            // Se force_recreate è true, resetta i dati PeerTube nel database locale
            if ($forceRecreate && $user->peertube_user_id) {
                Log::info('Reset dati PeerTube per ricreazione', ['user_id' => $user->id]);
                $user->update([
                    'peertube_user_id' => null,
                    'peertube_channel_id' => null,
                    'peertube_account_id' => null,
                    'peertube_username' => null,
                    'peertube_created_at' => null,
                ]);
            }

            // Crea l'account PeerTube
            $success = $this->peerTubeService->createPeerTubeUser($user, $peertubePassword);

            if ($success) {
                // Ricarica l'utente per ottenere i nuovi dati
                $user->refresh();

                Log::info('Account PeerTube creato con successo via admin', [
                    'user_id' => $user->id,
                    'peertube_user_id' => $user->peertube_user_id,
                    'peertube_channel_id' => $user->peertube_channel_id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Account PeerTube creato con successo!',
                    'user_data' => [
                        'peertube_user_id' => $user->peertube_user_id,
                        'peertube_channel_id' => $user->peertube_channel_id,
                        'peertube_account_id' => $user->peertube_account_id,
                        'peertube_username' => $user->peertube_username,
                        'peertube_created_at' => $user->peertube_created_at?->format('d/m/Y H:i:s'),
                    ]
                ]);
            } else {
                Log::error('Fallimento creazione account PeerTube via admin', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante la creazione dell\'account PeerTube. Controlla i log per maggiori dettagli.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Eccezione creazione account PeerTube via admin', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un utente PeerTube per risolvere conflitti
     */
    public function deletePeerTubeUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'delete_by_email' => 'boolean'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);
        $deleteByEmail = $request->boolean('delete_by_email', true);

        try {
            Log::info('Inizio eliminazione utente PeerTube via admin', [
                'user_id' => $user->id,
                'email' => $user->email,
                'delete_by_email' => $deleteByEmail
            ]);

            $result = null;

            if ($deleteByEmail) {
                // Elimina per email
                $result = $this->peerTubeService->deleteUserByEmail($user->email);
            } else {
                // Elimina per User ID se disponibile
                if ($user->peertube_user_id) {
                    $success = $this->peerTubeService->deleteUser($user->peertube_user_id);
                    $result = [
                        'success' => $success,
                        'message' => $success ? 'Utente PeerTube eliminato con successo' : 'Errore durante l\'eliminazione'
                    ];
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nessun PeerTube User ID disponibile per l\'eliminazione'
                    ]);
                }
            }

            if ($result && $result['success']) {
                // Resetta i dati PeerTube nel database locale
                $user->update([
                    'peertube_user_id' => null,
                    'peertube_channel_id' => null,
                    'peertube_account_id' => null,
                    'peertube_username' => null,
                    'peertube_created_at' => null,
                ]);

                Log::info('Utente PeerTube eliminato e dati locali resettati', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Utente PeerTube eliminato con successo! Ora puoi creare un nuovo account.',
                    'deleted_user' => $result['deleted_user'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Errore durante l\'eliminazione dell\'utente PeerTube'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Eccezione eliminazione utente PeerTube via admin', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambia l'email di un utente per risolvere conflitti PeerTube
     */
    public function changeUserEmail(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'new_email' => 'required|email|unique:users,email'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);
        $oldEmail = $user->email;
        $newEmail = $request->new_email;

        try {
            Log::info('Cambio email utente per risolvere conflitto PeerTube', [
                'user_id' => $user->id,
                'old_email' => $oldEmail,
                'new_email' => $newEmail
            ]);

            $user->update([
                'email' => $newEmail,
                'email_verified_at' => null // Richiede nuova verifica
            ]);

            Log::info('Email utente cambiata con successo', [
                'user_id' => $user->id,
                'old_email' => $oldEmail,
                'new_email' => $newEmail
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email cambiata con successo! L\'utente dovrà verificare la nuova email.',
                'user_data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'old_email' => $oldEmail,
                    'new_email' => $newEmail
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Errore cambio email utente', [
                'user_id' => $user->id,
                'old_email' => $oldEmail,
                'new_email' => $newEmail,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il cambio email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aggiorna dati PeerTube di un utente
     */
    public function updateUserData(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'peertube_channel_id' => 'nullable|integer',
            'peertube_account_id' => 'nullable|integer',
            'peertube_username' => 'nullable|string|max:255'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        try {
            $user->update([
                'peertube_channel_id' => $request->peertube_channel_id,
                'peertube_account_id' => $request->peertube_account_id,
                'peertube_username' => $request->peertube_username,
            ]);

            Log::info('Dati PeerTube aggiornati via admin', [
                'user_id' => $user->id,
                'peertube_channel_id' => $request->peertube_channel_id,
                'peertube_account_id' => $request->peertube_account_id,
                'peertube_username' => $request->peertube_username
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dati PeerTube aggiornati con successo!'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento dati PeerTube via admin', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiornamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifica se un utente esiste su PeerTube
     */
    public function verifyUserExists(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        try {
            Log::info('Verifica esistenza utente PeerTube via admin', [
                'user_id' => $user->id,
                'peertube_user_id' => $user->peertube_user_id,
                'peertube_username' => $user->peertube_username
            ]);

            $verificationResults = [];

            // 1. Verifica tramite User ID (se presente)
            if ($user->peertube_user_id) {
                $userDetails = $this->peerTubeService->getUserDetails($user->peertube_user_id);
                if ($userDetails) {
                    $verificationResults['by_user_id'] = [
                        'success' => true,
                        'method' => 'User ID',
                        'peertube_user_id' => $user->peertube_user_id,
                        'data' => $userDetails
                    ];
                } else {
                    $verificationResults['by_user_id'] = [
                        'success' => false,
                        'method' => 'User ID',
                        'peertube_user_id' => $user->peertube_user_id,
                        'error' => 'Utente non trovato con questo User ID'
                    ];
                }
            } else {
                $verificationResults['by_user_id'] = [
                    'success' => false,
                    'method' => 'User ID',
                    'error' => 'Nessun PeerTube User ID salvato nel database'
                ];
            }

            // 2. Verifica tramite username (se presente)
            if ($user->peertube_username) {
                $userByUsername = $this->peerTubeService->findUserByUsername($user->peertube_username);
                if ($userByUsername) {
                    $verificationResults['by_username'] = [
                        'success' => true,
                        'method' => 'Username',
                        'username' => $user->peertube_username,
                        'data' => $userByUsername
                    ];
                } else {
                    $verificationResults['by_username'] = [
                        'success' => false,
                        'method' => 'Username',
                        'username' => $user->peertube_username,
                        'error' => 'Utente non trovato con questo username'
                    ];
                }
            } else {
                $verificationResults['by_username'] = [
                    'success' => false,
                    'method' => 'Username',
                    'error' => 'Nessun username PeerTube salvato nel database'
                ];
            }

            // 3. Verifica tramite email
            $userByEmail = $this->peerTubeService->findUserByEmail($user->email);
            if ($userByEmail) {
                $verificationResults['by_email'] = [
                    'success' => true,
                    'method' => 'Email',
                    'email' => $user->email,
                    'data' => $userByEmail
                ];
            } else {
                $verificationResults['by_email'] = [
                    'success' => false,
                    'method' => 'Email',
                    'email' => $user->email,
                    'error' => 'Utente non trovato con questa email'
                ];
            }

            // 4. Verifica tramite channel ID (se presente)
            if ($user->peertube_channel_id) {
                $channelDetails = $this->peerTubeService->getChannelDetails($user->peertube_channel_id);
                if ($channelDetails) {
                    $verificationResults['by_channel_id'] = [
                        'success' => true,
                        'method' => 'Channel ID',
                        'channel_id' => $user->peertube_channel_id,
                        'data' => $channelDetails
                    ];
                } else {
                    $verificationResults['by_channel_id'] = [
                        'success' => false,
                        'method' => 'Channel ID',
                        'channel_id' => $user->peertube_channel_id,
                        'error' => 'Canale non trovato con questo Channel ID'
                    ];
                }
            } else {
                $verificationResults['by_channel_id'] = [
                    'success' => false,
                    'method' => 'Channel ID',
                    'error' => 'Nessun Channel ID salvato nel database'
                ];
            }

            // Determina lo stato generale
            $anySuccess = false;
            $successfulMethods = [];
            $failedMethods = [];

            foreach ($verificationResults as $method => $result) {
                if ($result['success']) {
                    $anySuccess = true;
                    $successfulMethods[] = $method;
                } else {
                    $failedMethods[] = $method;
                }
            }

            Log::info('Verifica esistenza utente PeerTube completata', [
                'user_id' => $user->id,
                'any_success' => $anySuccess,
                'successful_methods' => $successfulMethods,
                'failed_methods' => $failedMethods
            ]);

            return response()->json([
                'success' => true,
                'user_exists' => $anySuccess,
                'verification_results' => $verificationResults,
                'summary' => [
                    'any_success' => $anySuccess,
                    'successful_methods' => $successfulMethods,
                    'failed_methods' => $failedMethods,
                    'total_methods' => count($verificationResults)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Errore verifica esistenza utente PeerTube', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante la verifica: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sincronizza i dati PeerTube di un utente
     */
    public function syncUserData(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        try {
            Log::info('Sincronizzazione dati PeerTube via admin', [
                'user_id' => $user->id,
                'current_peertube_user_id' => $user->peertube_user_id
            ]);

            // Prima verifica se l'utente esiste
            $verificationResponse = $this->verifyUserExists($request);
            $verificationData = json_decode($verificationResponse->getContent(), true);

            if (!$verificationData['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errore durante la verifica: ' . $verificationData['message']
                ], 500);
            }

            if (!$verificationData['user_exists']) {
                // L'utente non esiste più su PeerTube, resetta i dati locali
                Log::info('Utente non trovato su PeerTube, reset dati locali', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);

                $user->update([
                    'peertube_user_id' => null,
                    'peertube_channel_id' => null,
                    'peertube_account_id' => null,
                    'peertube_username' => null,
                    'peertube_created_at' => null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Utente non trovato su PeerTube. Dati locali resettati. Ora puoi creare un nuovo account.',
                    'verification_results' => $verificationData['verification_results'],
                    'action' => 'reset_local_data'
                ]);
            }

            // Trova i dati più aggiornati
            $latestData = null;
            $sourceMethod = null;

            foreach ($verificationData['verification_results'] as $method => $result) {
                if ($result['success'] && isset($result['data'])) {
                    $latestData = $result['data'];
                    $sourceMethod = $method;
                    break; // Usa il primo metodo che ha successo
                }
            }

            if (!$latestData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile ottenere dati aggiornati da PeerTube'
                ]);
            }

            // Aggiorna i dati nel database
            $updateData = [];

            if (isset($latestData['id'])) {
                $updateData['peertube_user_id'] = $latestData['id'];
            }

            if (isset($latestData['username'])) {
                $updateData['peertube_username'] = $latestData['username'];
            }

            if (isset($latestData['account'])) {
                if (isset($latestData['account']['id'])) {
                    $updateData['peertube_account_id'] = $latestData['account']['id'];
                }
                if (isset($latestData['account']['name'])) {
                    $updateData['peertube_channel_id'] = $latestData['account']['name'];
                }
            }

            if (!empty($updateData)) {
                $user->update($updateData);

                Log::info('Dati PeerTube sincronizzati con successo', [
                    'user_id' => $user->id,
                    'source_method' => $sourceMethod,
                    'updated_fields' => array_keys($updateData)
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Dati PeerTube sincronizzati con successo!',
                    'source_method' => $sourceMethod,
                    'updated_data' => $updateData,
                    'verification_results' => $verificationData['verification_results']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Nessun dato da aggiornare trovato'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Errore sincronizzazione dati PeerTube', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante la sincronizzazione: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ottiene i log recenti di PeerTube per debug
     */
    private function getRecentPeerTubeLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (!file_exists($logFile)) {
                return [];
            }

            $logs = [];
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $recentLines = array_slice($lines, -100); // Ultime 100 righe

            foreach ($recentLines as $line) {
                if (strpos($line, 'PeerTube') !== false || strpos($line, 'peertube') !== false) {
                    $logs[] = $line;
                }
            }

            return array_slice($logs, -20); // Ultimi 20 log PeerTube

        } catch (\Exception $e) {
            Log::error('Errore lettura log PeerTube', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Aggiorna le impostazioni di sistema
     */
    protected function updateSystemSettings(array $settings)
    {
        Log::info('updateSystemSettings - Inizio aggiornamento', [
            'settings_count' => count($settings),
            'settings_keys' => array_keys($settings)
        ]);

        foreach ($settings as $key => $value) {
            try {
                Log::info('updateSystemSettings - Aggiornamento setting', [
                    'key' => $key,
                    'value_length' => strlen($value),
                    'group' => 'peertube'
                ]);

                $result = \App\Models\SystemSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group' => 'peertube',
                        'type' => 'string',
                        'display_name' => ucfirst(str_replace('_', ' ', $key)),
                        'description' => 'Configurazione PeerTube: ' . ucfirst(str_replace('_', ' ', $key))
                    ]
                );

                Log::info('updateSystemSettings - Setting salvato', [
                    'key' => $key,
                    'saved' => $result->wasRecentlyCreated ? 'created' : 'updated',
                    'id' => $result->id
                ]);

            } catch (\Exception $e) {
                Log::error('updateSystemSettings - Errore salvataggio setting', [
                    'key' => $key,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }

        Log::info('updateSystemSettings - Completato');
    }
}
