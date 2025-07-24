<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\PeerTubeService;
use Illuminate\Console\Command;

class CreatePeerTubeAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:create-accounts {--user-id= : ID specifico utente} {--force : Forza creazione anche se già esistente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea account PeerTube per utenti esistenti con ruoli poet o organizer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔗 CREAZIONE ACCOUNT PEERTUBE');
        $this->info('============================');

        $userId = $this->option('user-id');
        $force = $this->option('force');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ Utente con ID {$userId} non trovato!");
                return 1;
            }
            $users = collect([$user]);
        } else {
            // Trova tutti gli utenti con ruoli poet o organizer
            $users = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['poet', 'organizer']);
            })->get();
        }

        if ($users->isEmpty()) {
            $this->warn('⚠️  Nessun utente trovato con ruoli poet o organizer');
            return 0;
        }

        $this->info("Trovati {$users->count()} utenti da processare:");

        $peerTubeService = new PeerTubeService();
        
        // Verifica configurazione PeerTube
        if (!$peerTubeService->isConfigured()) {
            $this->error('❌ PeerTube non è configurato!');
            return 1;
        }

        if (!$peerTubeService->testConnection()) {
            $this->error('❌ Connessione PeerTube fallita!');
            return 1;
        }

        $this->info('✅ PeerTube configurato e connesso');

        $created = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($users as $user) {
            $this->line("\n👤 Processando: {$user->name} (ID: {$user->id})");
            $this->line("   Email: {$user->email}");
            
            $userRoles = $user->getRoleNames()->toArray();
            $this->line("   Ruoli: " . implode(', ', $userRoles));

            // Verifica se ha già un account PeerTube
            if ($user->hasPeerTubeAccount() && !$force) {
                $this->line("   ⏭️  Già ha account PeerTube (User ID: {$user->peertube_user_id})");
                $skipped++;
                continue;
            }

            // Se force è true e ha già un account, lo ricreiamo
            if ($user->hasPeerTubeAccount() && $force) {
                $this->line("   🔄 Ricreazione account PeerTube...");
            } else {
                $this->line("   ➕ Creazione nuovo account PeerTube...");
            }

            try {
                // Genera username PeerTube
                $peertubeUsername = $user->nickname ?: strtolower(str_replace(['@', '.'], ['', '_'], $user->email));
                $peertubeUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $peertubeUsername);
                
                if (strlen($peertubeUsername) < 3) {
                    $peertubeUsername = 'user_' . $user->id;
                }

                // Crea utente su PeerTube
                $peerTubeUserData = [
                    'peertube_username' => $peertubeUsername,
                    'email' => $user->email,
                    'peertube_password' => 'password123', // Password temporanea
                    'peertube_display_name' => $user->name,
                ];

                $peerTubeUser = $peerTubeService->createUser($peerTubeUserData);

                // Aggiorna utente locale
                $user->update([
                    'peertube_user_id' => $peerTubeUser['peertube_user_id'],
                    'peertube_username' => $peerTubeUser['peertube_username'],
                    'peertube_display_name' => $peerTubeUser['peertube_display_name'],
                    'peertube_password' => $peerTubeUser['peertube_password'],
                ]);

                $this->line("   ✅ Account creato: {$peerTubeUser['peertube_username']} (ID: {$peerTubeUser['peertube_user_id']})");
                $created++;

            } catch (\Exception $e) {
                $this->line("   ❌ Errore: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n📊 RISULTATI:");
        $this->info("✅ Creati: {$created}");
        $this->info("⏭️  Saltati: {$skipped}");
        $this->info("❌ Errori: {$errors}");
        $this->info("📊 Totale: " . ($created + $skipped + $errors));

        return 0;
    }
}
