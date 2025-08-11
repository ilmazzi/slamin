<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonitorPeerTubeServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:monitor {--detailed} {--check-jobs} {--check-storage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitora lo stato del server PeerTube per diagnosticare problemi di finalizzazione';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 MONITOR SERVER PEERTUBE');
        $this->info('==========================');
        $this->newLine();

        $detailed = $this->option('detailed');
        $checkJobs = $this->option('check-jobs');
        $checkStorage = $this->option('check-storage');

        try {
            $peerTubeService = new PeerTubeService();
            $baseUrl = $peerTubeService->getBaseUrl();

            // 1. Verifica configurazione
            $this->info('1️⃣ VERIFICA CONFIGURAZIONE');
            $this->info('========================');
            
            if (!$peerTubeService->isConfigured()) {
                $this->error('❌ PeerTube non è configurato!');
                return 1;
            }
            $this->info('✅ PeerTube configurato');
            $this->info('🌐 URL: ' . $baseUrl);
            $this->newLine();

            // 2. Test connessione base
            $this->info('2️⃣ TEST CONNESSIONE BASE');
            $this->info('=======================');
            
            $response = Http::timeout(10)->get($baseUrl . '/api/v1/config');
            if ($response->successful()) {
                $config = $response->json();
                $this->info('✅ Server raggiungibile');
                $this->info('📋 Versione: ' . ($config['version'] ?? 'N/A'));
                $this->info('🏷️ Nome: ' . ($config['instance']['name'] ?? 'N/A'));
                $this->info('📧 Email: ' . ($config['instance']['email'] ?? 'N/A'));
            } else {
                $this->error('❌ Server non raggiungibile: ' . $response->status());
                return 1;
            }
            $this->newLine();

            // 3. Test autenticazione
            $this->info('3️⃣ TEST AUTENTICAZIONE');
            $this->info('======================');
            
            if (!$peerTubeService->testAuthentication()) {
                $this->error('❌ Autenticazione fallita!');
                return 1;
            }
            $this->info('✅ Autenticazione OK');
            $this->newLine();

            // 4. Verifica endpoint di upload
            $this->info('4️⃣ VERIFICA ENDPOINT UPLOAD');
            $this->info('==========================');
            
            $token = $peerTubeService->authenticate();
            $uploadResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($baseUrl . '/api/v1/videos/upload');
            
            if ($uploadResponse->status() === 200) {
                $this->info('✅ Endpoint upload disponibile');
            } else {
                $this->warn('⚠️ Endpoint upload non disponibile: ' . $uploadResponse->status());
            }
            $this->newLine();

            // 5. Verifica configurazioni di upload
            $this->info('5️⃣ CONFIGURAZIONI UPLOAD');
            $this->info('========================');
            
            $configResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($baseUrl . '/api/v1/config');
            
            if ($configResponse->successful()) {
                $serverConfig = $configResponse->json();
                
                // Limiti di upload
                $maxFileSize = $serverConfig['video']['file']['maxSize'] ?? 'N/A';
                $this->info('📊 Max dimensione file: ' . $this->formatBytes($maxFileSize));
                
                // Formati supportati
                $supportedFormats = $serverConfig['video']['file']['extensions'] ?? [];
                $this->info('📁 Formati supportati: ' . implode(', ', $supportedFormats));
                
                // Transcoding
                $transcodingEnabled = $serverConfig['transcoding']['enabled'] ?? false;
                $this->info('🔄 Transcoding: ' . ($transcodingEnabled ? 'Abilitato' : 'Disabilitato'));
                
                if ($transcodingEnabled) {
                    $resolutions = $serverConfig['transcoding']['resolutions'] ?? [];
                    $this->info('📺 Risoluzioni: ' . implode(', ', $resolutions));
                }
            }
            $this->newLine();

            // 6. Verifica spazio disco (se abilitato)
            if ($checkStorage) {
                $this->info('6️⃣ VERIFICA SPAZIO DISCO');
                $this->info('=======================');
                
                $storageResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get($baseUrl . '/api/v1/server/stats');
                
                if ($storageResponse->successful()) {
                    $stats = $storageResponse->json();
                    $this->info('💾 Spazio totale: ' . $this->formatBytes($stats['totalDiskSpace'] ?? 0));
                    $this->info('📊 Spazio usato: ' . $this->formatBytes($stats['usedDiskSpace'] ?? 0));
                    $this->info('📈 Spazio libero: ' . $this->formatBytes(($stats['totalDiskSpace'] ?? 0) - ($stats['usedDiskSpace'] ?? 0)));
                } else {
                    $this->warn('⚠️ Impossibile recuperare statistiche disco');
                }
                $this->newLine();
            }

            // 7. Verifica job di transcoding (se abilitato)
            if ($checkJobs) {
                $this->info('7️⃣ VERIFICA JOB TRANCODING');
                $this->info('==========================');
                
                $jobsResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get($baseUrl . '/api/v1/jobs');
                
                if ($jobsResponse->successful()) {
                    $jobs = $jobsResponse->json();
                    
                    if (is_array($jobs) && !empty($jobs)) {
                        $pendingJobs = array_filter($jobs, function($job) {
                            return is_array($job) && isset($job['state']) && 
                                   ($job['state'] === 'pending' || $job['state'] === 'processing');
                        });
                        
                        $completedJobs = array_filter($jobs, function($job) {
                            return is_array($job) && isset($job['state']) && $job['state'] === 'completed';
                        });
                        
                        $failedJobs = array_filter($jobs, function($job) {
                            return is_array($job) && isset($job['state']) && $job['state'] === 'failed';
                        });
                        
                        $this->info('⏳ Job in attesa: ' . count($pendingJobs));
                        $this->info('✅ Job completati: ' . count($completedJobs));
                        $this->info('❌ Job falliti: ' . count($failedJobs));
                        
                        if (count($pendingJobs) > 0) {
                            $this->warn('⚠️ Ci sono job in attesa di elaborazione');
                            foreach (array_slice($pendingJobs, 0, 5) as $job) {
                                $this->info('   - Job ' . ($job['id'] ?? 'N/A') . ': ' . ($job['type'] ?? 'N/A') . ' (' . ($job['state'] ?? 'N/A') . ')');
                            }
                        }
                    } else {
                        $this->info('📋 Nessun job trovato');
                    }
                } else {
                    $this->warn('⚠️ Impossibile recuperare job: ' . $jobsResponse->status());
                }
                $this->newLine();
            }

            // 8. Test dettagliato (se richiesto)
            if ($detailed) {
                $this->info('8️⃣ TEST DETTAGLIATO');
                $this->info('==================');
                
                // Test timeout
                $this->info('⏱️ Test timeout connessione...');
                $startTime = microtime(true);
                $timeoutResponse = Http::timeout(30)->get($baseUrl . '/api/v1/config');
                $endTime = microtime(true);
                $responseTime = ($endTime - $startTime) * 1000;
                
                $this->info('📡 Tempo di risposta: ' . number_format($responseTime, 2) . ' ms');
                
                if ($responseTime > 5000) {
                    $this->warn('⚠️ Tempo di risposta elevato (>5s)');
                } else {
                    $this->info('✅ Tempo di risposta OK');
                }
                $this->newLine();
            }

            // 9. Suggerimenti
            $this->info('9️⃣ SUGGERIMENTI PER DEBUG');
            $this->info('=========================');
            
            $this->info('💡 Se l\'upload si blocca alla finalizzazione:');
            $this->info('   1. Verifica che il transcoding sia abilitato');
            $this->info('   2. Controlla che ci sia spazio disco sufficiente');
            $this->info('   3. Verifica che non ci siano job in coda');
            $this->info('   4. Controlla i log del server PeerTube');
            $this->info('   5. Verifica i limiti di memoria del server');
            $this->newLine();
            
            $this->info('🔧 Comandi utili:');
            $this->info('   - php artisan peertube:monitor --detailed (test dettagliato)');
            $this->info('   - php artisan peertube:monitor --check-jobs (verifica job)');
            $this->info('   - php artisan peertube:monitor --check-storage (verifica disco)');
            $this->newLine();

            $this->info('🎉 Monitoraggio completato!');

        } catch (\Exception $e) {
            $this->error('❌ Errore durante il monitoraggio: ' . $e->getMessage());
            Log::error('PeerTube monitor error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes === 'N/A' || $bytes === 0) {
            return 'N/A';
        }
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
} 
