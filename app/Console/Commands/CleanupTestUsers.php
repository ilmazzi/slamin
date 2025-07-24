<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use App\Models\User;

class CleanupTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:cleanup-test-users {--username=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulisce gli utenti di test da PeerTube e dal database locale';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 PULIZIA UTENTI TEST PEERTUBE');
        $this->info('===============================');
        $this->newLine();

        $username = $this->option('username');
        $cleanAll = $this->option('all');

        try {
            $peerTubeService = new PeerTubeService();

            if ($cleanAll) {
                $this->info('🗑️ Eliminazione di tutti gli utenti di test...');
                
                // Trova tutti gli utenti di test nel database locale
                $testUsers = User::where('email', 'like', '%test%')
                    ->orWhere('email', 'like', '%slamin.it')
                    ->orWhere('name', 'like', '%Test%')
                    ->get();

                $this->info('Trovati ' . $testUsers->count() . ' utenti di test nel database locale.');

                foreach ($testUsers as $user) {
                    $this->info('Eliminando utente: ' . $user->name . ' (' . $user->email . ')');
                    
                    // Elimina da PeerTube se ha un ID PeerTube
                    if ($user->peertube_user_id) {
                        if ($peerTubeService->deleteUser($user->peertube_user_id)) {
                            $this->info('  ✅ Eliminato da PeerTube');
                        } else {
                            $this->warn('  ⚠️ Errore eliminazione da PeerTube');
                        }
                    }
                    
                    // Elimina dal database locale
                    $user->delete();
                    $this->info('  ✅ Eliminato dal database locale');
                }

                $this->info('🎉 Pulizia completata!');

            } elseif ($username) {
                $this->info('🗑️ Eliminazione utente specifico: ' . $username);
                
                // Trova l'utente nel database locale
                $user = User::where('peertube_username', $username)
                    ->orWhere('email', 'like', '%' . $username . '%')
                    ->first();

                if ($user) {
                    $this->info('Utente trovato: ' . $user->name . ' (' . $user->email . ')');
                    
                    // Elimina da PeerTube se ha un ID PeerTube
                    if ($user->peertube_user_id) {
                        if ($peerTubeService->deleteUser($user->peertube_user_id)) {
                            $this->info('  ✅ Eliminato da PeerTube');
                        } else {
                            $this->warn('  ⚠️ Errore eliminazione da PeerTube');
                        }
                    } else {
                        // Prova a eliminare per username
                        if ($peerTubeService->deleteTestUser($username)) {
                            $this->info('  ✅ Eliminato da PeerTube (per username)');
                        } else {
                            $this->warn('  ⚠️ Errore eliminazione da PeerTube (per username)');
                        }
                    }
                    
                    // Elimina dal database locale
                    $user->delete();
                    $this->info('  ✅ Eliminato dal database locale');
                    
                } else {
                    $this->warn('⚠️ Utente non trovato nel database locale');
                    
                    // Prova a eliminare solo da PeerTube
                    if ($peerTubeService->deleteTestUser($username)) {
                        $this->info('  ✅ Eliminato da PeerTube');
                    } else {
                        $this->warn('  ⚠️ Errore eliminazione da PeerTube');
                    }
                }

            } else {
                $this->error('❌ Specifica --username=NOME o --all per eliminare tutti gli utenti di test');
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante la pulizia: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 