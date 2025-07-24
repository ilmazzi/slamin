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
            Log::info('Configurazioni PeerTube salvate', [
                'count' => $savedSettings->count(),
                'settings' => $savedSettings->pluck('key')->toArray()
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
