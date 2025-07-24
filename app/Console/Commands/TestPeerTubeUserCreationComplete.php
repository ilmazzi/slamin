<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class TestPeerTubeUserCreationComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:test-user-creation-complete {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa il processo completo di creazione utente PeerTube con salvataggio dati';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Test processo completo creazione utente PeerTube per: {$email}");

        // Trova l'utente
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("Utente non trovato: {$email}");
            return 1;
        }

        $this->info("Utente trovato: {$user->name} (ID: {$user->id})");

        // Verifica se ha già un account PeerTube
        if ($user->hasPeerTubeAccount()) {
            $this->warn("L'utente ha già un account PeerTube!");
            $this->info("PeerTube User ID: {$user->peertube_user_id}");
            $this->info("PeerTube Username: {$user->peertube_username}");
            $this->info("PeerTube Channel ID: {$user->peertube_channel_id}");

            if (!$this->confirm('Vuoi ricreare l\'account PeerTube?')) {
                $this->info('Operazione annullata.');
                return 0;
            }
        }

        // Verifica che l'utente abbia i ruoli corretti
        if (!$user->canHavePeerTubeAccount()) {
            $this->warn("L'utente non ha ruoli poet/organizer. Aggiungo ruolo 'poet' per test...");
            $poetRole = \App\Models\Role::where('name', 'poet')->first();
            if ($poetRole) {
                $user->roles()->attach($poetRole->id);
                $this->info("Ruolo 'poet' aggiunto per test.");
            } else {
                $this->error("Ruolo 'poet' non trovato nel database!");
                return 1;
            }
        }

        try {
            $this->info("Inizio processo completo di creazione utente PeerTube...");

            $peerTubeService = new PeerTubeService();

            // Genera una password sicura
            $password = \Illuminate\Support\Str::random(12);
            $this->info("Password generata: {$password}");

                        // Esegui il processo completo
            $result = $peerTubeService->createPeerTubeUser($user, $password);

            if ($result) {
                $this->info("✅ Processo completato con successo!");

                // Ricarica l'utente per vedere i dati aggiornati
                $user->refresh();

                // Forza il ricaricamento dal database
                $user = User::find($user->id);

                $this->info("Dati salvati nel database:");
                $this->info("- PeerTube User ID: " . ($user->peertube_user_id ?? 'NULL'));
                $this->info("- PeerTube Username: " . ($user->peertube_username ?? 'NULL'));
                $this->info("- PeerTube Display Name: " . ($user->peertube_display_name ?? 'NULL'));
                $this->info("- PeerTube Email: " . ($user->peertube_email ?? 'NULL'));
                $this->info("- PeerTube Role: " . ($user->peertube_role ?? 'NULL'));
                $this->info("- PeerTube Account ID: " . ($user->peertube_account_id ?? 'NULL'));
                $this->info("- PeerTube Channel ID: " . ($user->peertube_channel_id ?? 'NULL'));
                $this->info("- PeerTube Video Quota: " . ($user->peertube_video_quota ?? 'NULL'));
                $this->info("- PeerTube Video Quota Daily: " . ($user->peertube_video_quota_daily ?? 'NULL'));
                $this->info("- PeerTube Created At: " . ($user->peertube_created_at ?? 'NULL'));
                $this->info("- PeerTube Password: " . ($user->peertube_password ? 'CRYPTED' : 'NULL'));

                return 0;
            } else {
                $this->error("❌ Processo fallito!");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("❌ Errore durante il processo: " . $e->getMessage());
            Log::error('Errore test creazione utente PeerTube completo', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
