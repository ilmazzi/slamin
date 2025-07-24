<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Log;

class TestPeerTubeUserCreation extends Command
{
    protected $signature = 'peertube:test-user-creation {email}';
    protected $description = 'Test PeerTube user creation for existing user';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Testing PeerTube user creation for: $email");

        // Trova l'utente
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Utente non trovato con email: $email");
            return 1;
        }

        $this->info("Utente trovato: {$user->name} (ID: {$user->id})");

        // Verifica se ha già un account PeerTube
        if ($user->peertube_user_id) {
            $this->warn("L'utente ha già un account PeerTube (ID: {$user->peertube_user_id})");
            if (!$this->confirm('Vuoi procedere comunque?')) {
                return 0;
            }
        }

        // Verifica i ruoli
        $roles = $user->roles->pluck('name')->toArray();
        $this->info("Ruoli: " . implode(', ', $roles));

        $shouldCreate = false;
        foreach ($roles as $role) {
            if (in_array($role, ['poet', 'organizer'])) {
                $shouldCreate = true;
                break;
            }
        }

        if (!$shouldCreate) {
            $this->warn("L'utente non ha ruoli poet/organizer. Aggiungendo ruolo 'poet' per test...");
            $user->assignRole('poet');
        }

        try {
            $this->info("Creazione utente PeerTube...");

            $peerTubeService = new PeerTubeService();

            // Genera una password sicura per PeerTube
            $peerTubePassword = \Illuminate\Support\Str::random(12);
            $this->info("Password generata: $peerTubePassword");

            // Crea l'utente su PeerTube
            $result = $peerTubeService->createPeerTubeUser($user, $peerTubePassword);

            if ($result) {
                $this->info("✅ Utente PeerTube creato con successo!");
                $this->info("PeerTube User ID: {$user->peertube_user_id}");
                $this->info("PeerTube Username: {$user->peertube_username}");
                $this->info("PeerTube Display Name: {$user->peertube_display_name}");
                $this->info("PeerTube Account ID: {$user->peertube_account_id}");
                $this->info("PeerTube Channel ID: {$user->peertube_channel_id}");
            } else {
                $this->error("❌ Fallimento creazione utente PeerTube");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("❌ Errore: " . $e->getMessage());
            Log::error('Errore test creazione utente PeerTube', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }
}
