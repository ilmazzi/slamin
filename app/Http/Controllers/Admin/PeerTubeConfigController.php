<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeerTubeConfig;
use App\Services\PeerTubeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PeerTubeConfigController extends Controller
{
    private $peerTubeService;

    public function __construct(PeerTubeService $peerTubeService)
    {
        $this->peerTubeService = $peerTubeService;
    }

    /**
     * Mostra la pagina di configurazione PeerTube
     */
    public function index()
    {
        $configs = PeerTubeConfig::getAllAsArray();
        $isConfigured = $this->peerTubeService->isConfigured();
        $connectionTest = $this->peerTubeService->testConnection();
        $authTest = $isConfigured ? $this->peerTubeService->testAuthentication() : false;
        
        $channelInfo = null;
        $accountInfo = null;
        
        if ($authTest) {
            $channelInfo = $this->peerTubeService->getChannelInfo();
            $accountInfo = $this->peerTubeService->getAccountInfo();
        }

        return view('admin.peertube.config', compact(
            'configs', 
            'isConfigured', 
            'connectionTest', 
            'authTest', 
            'channelInfo', 
            'accountInfo'
        ));
    }

    /**
     * Aggiorna le configurazioni PeerTube
     */
    public function update(Request $request)
    {
        $request->validate([
            'peertube_url' => 'required|url',
            'peertube_admin_username' => 'required|string',
            'peertube_admin_password' => 'nullable|string',
            'peertube_channel_id' => 'nullable|string',
            'peertube_account_id' => 'nullable|string',
            'peertube_default_privacy' => 'required|in:public,unlisted,private',
            'peertube_max_file_size' => 'required|integer|min:1048576', // Min 1MB
            'peertube_default_tags' => 'nullable|array',
            'peertube_allowed_extensions' => 'required|array',
        ]);

        try {
            // Aggiorna configurazioni base
            PeerTubeConfig::setValue('peertube_url', $request->peertube_url);
            PeerTubeConfig::setValue('peertube_admin_username', $request->peertube_admin_username);
            
            // Aggiorna password solo se fornita
            if ($request->filled('peertube_admin_password')) {
                PeerTubeConfig::setValue('peertube_admin_password', $request->peertube_admin_password, 'string', 'Password admin PeerTube', true);
            }

            PeerTubeConfig::setValue('peertube_channel_id', $request->peertube_channel_id);
            PeerTubeConfig::setValue('peertube_account_id', $request->peertube_account_id);
            PeerTubeConfig::setValue('peertube_default_privacy', $request->peertube_default_privacy);
            PeerTubeConfig::setValue('peertube_max_file_size', $request->peertube_max_file_size, 'integer');
            PeerTubeConfig::setValue('peertube_default_tags', $request->peertube_default_tags ?? ['poetry', 'slam', 'poetry-slam'], 'json');
            PeerTubeConfig::setValue('peertube_allowed_extensions', $request->peertube_allowed_extensions, 'json');

            // Invalida token esistente se le credenziali sono cambiate
            if ($request->filled('peertube_admin_password') || $request->filled('peertube_admin_username')) {
                PeerTubeConfig::setValue('peertube_access_token', '', 'string', 'Token di accesso admin PeerTube', true);
                PeerTubeConfig::setValue('peertube_token_expires_at', null, 'datetime');
            }

            return redirect()->route('admin.peertube.config')
                ->with('success', 'Configurazioni PeerTube aggiornate con successo!');

        } catch (\Exception $e) {
            Log::error('Error updating PeerTube config: ' . $e->getMessage());
            return redirect()->route('admin.peertube.config')
                ->with('error', 'Errore durante l\'aggiornamento delle configurazioni: ' . $e->getMessage());
        }
    }

    /**
     * Testa la connessione a PeerTube
     */
    public function testConnection()
    {
        try {
            $connectionTest = $this->peerTubeService->testConnection();
            
            if ($connectionTest) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connessione a PeerTube stabilita con successo!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossibile connettersi a PeerTube. Verifica l\'URL.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il test di connessione: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Testa l'autenticazione PeerTube
     */
    public function testAuthentication()
    {
        try {
            if (!$this->peerTubeService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PeerTube non è configurato. Inserisci URL, username e password.'
                ]);
            }

            $authTest = $this->peerTubeService->testAuthentication();
            
            if ($authTest) {
                $channelInfo = $this->peerTubeService->getChannelInfo();
                $accountInfo = $this->peerTubeService->getAccountInfo();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Autenticazione PeerTube riuscita!',
                    'channel_info' => $channelInfo,
                    'account_info' => $accountInfo
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Autenticazione fallita. Verifica username e password.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'autenticazione: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Resetta le configurazioni PeerTube
     */
    public function reset()
    {
        try {
            // Rimuovi token e credenziali
            PeerTubeConfig::setValue('peertube_access_token', '', 'string', 'Token di accesso admin PeerTube', true);
            PeerTubeConfig::setValue('peertube_token_expires_at', null, 'datetime');
            PeerTubeConfig::setValue('peertube_admin_username', '');
            PeerTubeConfig::setValue('peertube_admin_password', '', 'string', 'Password admin PeerTube', true);
            PeerTubeConfig::setValue('peertube_channel_id', '');
            PeerTubeConfig::setValue('peertube_account_id', '');

            return redirect()->route('admin.peertube.config')
                ->with('success', 'Configurazioni PeerTube resettate con successo!');

        } catch (\Exception $e) {
            Log::error('Error resetting PeerTube config: ' . $e->getMessage());
            return redirect()->route('admin.peertube.config')
                ->with('error', 'Errore durante il reset delle configurazioni: ' . $e->getMessage());
        }
    }

    /**
     * Ottieni lista account per trovare ID utente
     */
    public function getAccounts()
    {
        try {
            if (!$this->peerTubeService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PeerTube non è configurato'
                ]);
            }

            $accounts = $this->peerTubeService->getAccounts(100);
            
            return response()->json([
                'success' => true,
                'accounts' => $accounts['data'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nel recupero account: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ottieni lista canali per trovare ID canale
     */
    public function getChannels()
    {
        try {
            if (!$this->peerTubeService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PeerTube non è configurato'
                ]);
            }

            $channels = $this->peerTubeService->getChannels(100);
            
            return response()->json([
                'success' => true,
                'channels' => $channels['data'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nel recupero canali: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Trova account per username
     */
    public function findAccount(Request $request)
    {
        $request->validate([
            'username' => 'required|string'
        ]);

        try {
            if (!$this->peerTubeService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PeerTube non è configurato'
                ]);
            }

            $account = $this->peerTubeService->getAccountInfoByUsername($request->username);
            
            if ($account) {
                return response()->json([
                    'success' => true,
                    'account' => $account
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Account non trovato'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella ricerca account: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Trova canale per nome
     */
    public function findChannel(Request $request)
    {
        $request->validate([
            'channel_name' => 'required|string'
        ]);

        try {
            if (!$this->peerTubeService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PeerTube non è configurato'
                ]);
            }

            $channel = $this->peerTubeService->getChannelByName($request->channel_name);
            
            if ($channel) {
                return response()->json([
                    'success' => true,
                    'channel' => $channel
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Canale non trovato'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella ricerca canale: ' . $e->getMessage()
            ]);
        }
    }
} 