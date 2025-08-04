<?php

namespace App\Console\Commands;

use App\Models\SystemSetting;
use Illuminate\Console\Command;

class UpdatePeerTubeCredentials extends Command
{
    protected $signature = 'peertube:update-credentials {username} {password}';
    protected $description = 'Aggiorna le credenziali admin di PeerTube';

    public function handle(): int
    {
        $username = $this->argument('username');
        $password = $this->argument('password');

        $this->info("Aggiornamento credenziali PeerTube...");
        $this->line("Username: {$username}");
        $this->line("Password: " . str_repeat('*', strlen($password)));

        try {
            // Aggiorna username
            SystemSetting::updateOrCreate(
                ['key' => 'peertube_admin_username'],
                ['value' => $username]
            );

            // Aggiorna password
            SystemSetting::updateOrCreate(
                ['key' => 'peertube_admin_password'],
                ['value' => $password]
            );

            $this->info("✅ Credenziali aggiornate con successo!");

            // Testa la connessione
            $this->info("Testando la connessione...");
            $result = $this->call('peertube:test-connection');

            if ($result === 0) {
                $this->info("✅ Connessione PeerTube funzionante!");
            } else {
                $this->warn("⚠️ Connessione PeerTube fallita. Verifica le credenziali.");
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Errore durante l'aggiornamento: " . $e->getMessage());
            return 1;
        }
    }
}
