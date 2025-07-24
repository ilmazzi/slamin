<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class DebugVideoUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:debug-upload {--user-id=} {--video-id=} {--test-connection} {--test-auth} {--check-config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug dei problemi di upload video su PeerTube';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 DEBUG UPLOAD VIDEO PEERTUBE');
        $this->info('==============================');
        $this->newLine();

        $userId = $this->option('user-id');
        $videoId = $this->option('video-id');
        $testConnection = $this->option('test-connection');
        $testAuth = $this->option('test-auth');
        $checkConfig = $this->option('check-config');

        try {
            $peerTubeService = new PeerTubeService();

            // 1. Verifica configurazione
            if ($checkConfig || (!$userId && !$videoId)) {
                $this->info('1️⃣ VERIFICA CONFIGURAZIONE PEERTUBE');
                $this->info('==================================');
                
                if (!$peerTubeService->isConfigured()) {
                    $this->error('❌ PeerTube non è configurato!');
                    $this->info('Verifica le configurazioni nel pannello admin.');
                    return 1;
                }
                $this->info('✅ PeerTube configurato correttamente');
                $this->newLine();
            }

            // 2. Test connessione
            if ($testConnection || (!$userId && !$videoId)) {
                $this->info('2️⃣ TEST CONNESSIONE PEERTUBE');
                $this->info('============================');
                
                if (!$peerTubeService->testConnection()) {
                    $this->error('❌ Connessione PeerTube fallita!');
                    $this->info('Verifica che il server PeerTube sia raggiungibile.');
                    return 1;
                }
                $this->info('✅ Connessione PeerTube OK');
                $this->newLine();
            }

            // 3. Test autenticazione
            if ($testAuth || (!$userId && !$videoId)) {
                $this->info('3️⃣ TEST AUTENTICAZIONE PEERTUBE');
                $this->info('================================');
                
                if (!$peerTubeService->testAuthentication()) {
                    $this->error('❌ Autenticazione PeerTube fallita!');
                    $this->info('Verifica le credenziali admin nel pannello admin.');
                    return 1;
                }
                $this->info('✅ Autenticazione PeerTube OK');
                $this->newLine();
            }

            // 4. Debug utente specifico
            if ($userId) {
                $this->info('4️⃣ DEBUG UTENTE SPECIFICO');
                $this->info('==========================');
                
                $user = User::find($userId);
                if (!$user) {
                    $this->error('❌ Utente non trovato con ID: ' . $userId);
                    return 1;
                }

                $this->info('👤 Utente: ' . $user->name . ' (' . $user->email . ')');
                $this->info('🎭 Ruoli: ' . implode(', ', $user->getRoleNames()->toArray()));
                
                if ($user->peertube_user_id) {
                    $this->info('✅ PeerTube User ID: ' . $user->peertube_user_id);
                    $this->info('✅ PeerTube Username: ' . $user->peertube_username);
                    $this->info('✅ PeerTube Channel ID: ' . ($user->peertube_channel_id ?? 'N/A'));
                    $this->info('✅ PeerTube Account ID: ' . ($user->peertube_account_id ?? 'N/A'));

                    // Test token utente
                    $this->info('🔑 Test ottenimento token utente...');
                    try {
                        $tokenData = $peerTubeService->getUserToken($user);
                        $this->info('✅ Token utente ottenuto con successo!');
                        $this->info('⏰ Scade in: ' . $tokenData['expires_in'] . ' secondi');
                    } catch (\Exception $e) {
                        $this->error('❌ Errore ottenimento token: ' . $e->getMessage());
                    }

                    // Verifica informazioni utente su PeerTube
                    $this->info('📋 Verifica informazioni utente su PeerTube...');
                    $userInfo = $peerTubeService->getUserInfo($user->peertube_user_id);
                    if ($userInfo) {
                        $this->info('✅ Informazioni utente recuperate');
                        $this->info('📧 Email verificata: ' . ($userInfo['emailVerified'] ? 'Sì' : 'No'));
                        $this->info('📺 Canali: ' . count($userInfo['videoChannels'] ?? []));
                        $this->info('🎬 Quota video: ' . ($userInfo['videoQuota'] ?? 'N/A'));
                        $this->info('📅 Quota giornaliera: ' . ($userInfo['videoQuotaDaily'] ?? 'N/A'));
                    } else {
                        $this->warn('⚠️ Impossibile recuperare informazioni utente');
                    }

                } else {
                    $this->warn('⚠️ Utente non ha account PeerTube');
                }
                $this->newLine();
            }

            // 5. Debug video specifico
            if ($videoId) {
                $this->info('5️⃣ DEBUG VIDEO SPECIFICO');
                $this->info('========================');
                
                $video = Video::find($videoId);
                if (!$video) {
                    $this->error('❌ Video non trovato con ID: ' . $videoId);
                    return 1;
                }

                $this->info('🎬 Video: ' . $video->title);
                $this->info('👤 Proprietario: ' . $video->user->name);
                $this->info('📁 File: ' . $video->file_path);
                $this->info('📊 Dimensione: ' . ($video->file_size ? number_format($video->file_size / 1024 / 1024, 2) . ' MB' : 'N/A'));
                $this->info('🔄 Stato: ' . $video->status);
                $this->info('🔗 URL PeerTube: ' . ($video->peertube_url ?? 'N/A'));
                $this->info('🆔 ID PeerTube: ' . ($video->peertube_id ?? 'N/A'));

                if ($video->peertube_id) {
                    $this->info('📋 Verifica stato video su PeerTube...');
                    // Qui potresti aggiungere una chiamata per verificare lo stato del video su PeerTube
                }
                $this->newLine();
            }

            // 6. Statistiche generali
            if (!$userId && !$videoId) {
                $this->info('6️⃣ STATISTICHE GENERALI');
                $this->info('=======================');
                
                $totalUsers = User::count();
                $usersWithPeerTube = User::whereNotNull('peertube_user_id')->count();
                $totalVideos = Video::count();
                $pendingVideos = Video::where('status', 'pending')->count();
                $uploadedVideos = Video::where('status', 'uploaded')->count();
                $failedVideos = Video::where('status', 'failed')->count();

                $this->info('👥 Utenti totali: ' . $totalUsers);
                $this->info('✅ Utenti con PeerTube: ' . $usersWithPeerTube);
                $this->info('🎬 Video totali: ' . $totalVideos);
                $this->info('⏳ Video in attesa: ' . $pendingVideos);
                $this->info('✅ Video caricati: ' . $uploadedVideos);
                $this->info('❌ Video falliti: ' . $failedVideos);

                if ($pendingVideos > 0) {
                    $this->warn('⚠️ Ci sono ' . $pendingVideos . ' video in attesa di upload');
                    $pendingVideoList = Video::where('status', 'pending')->with('user')->get();
                    foreach ($pendingVideoList as $video) {
                        $this->info('   - ' . $video->title . ' (ID: ' . $video->id . ', User: ' . $video->user->name . ')');
                    }
                }
                $this->newLine();
            }

            $this->info('🎉 Debug completato!');
            
            if (!$userId && !$videoId) {
                $this->info('💡 Suggerimenti per il debug:');
                $this->info('   - Usa --user-id=N per debug di un utente specifico');
                $this->info('   - Usa --video-id=N per debug di un video specifico');
                $this->info('   - Usa --test-connection per testare solo la connessione');
                $this->info('   - Usa --test-auth per testare solo l\'autenticazione');
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante il debug: ' . $e->getMessage());
            Log::error('Debug video upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }
} 