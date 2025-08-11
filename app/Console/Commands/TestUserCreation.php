<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PeerTubeService;
use Illuminate\Support\Str;

class TestUserCreation extends Command
{
    protected $signature = 'peertube:test-user-creation {--username=} {--email=}';
    protected $description = 'Testa la creazione di un utente PeerTube';

    public function handle()
    {
        $this->info('👤 TEST CREAZIONE UTENTE PEERTUBE');
        $this->info('==================================');
        $this->newLine();

        $peerTubeService = new PeerTubeService();

        // Verifica configurazione
        if (!$peerTubeService->isConfigured()) {
            $this->error('❌ PeerTube non configurato!');
            return 1;
        }

        $this->info('✅ PeerTube configurato');

        // Test connessione
        if (!$peerTubeService->testConnection()) {
            $this->error('❌ Connessione PeerTube fallita!');
            return 1;
        }

        $this->info('✅ Connessione OK');

        // Test autenticazione
        try {
            $peerTubeService->testAuthentication();
            $this->info('✅ Autenticazione OK');
        } catch (\Exception $e) {
            $this->error('❌ Autenticazione fallita: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();

        // Dati utente di test
        $username = $this->option('username') ?: 'testuser' . Str::random(4);
        $email = $this->option('email') ?: 'test_' . Str::random(6) . '@example.com';
        $password = Str::random(12);

        $this->line("Username: {$username}");
        $this->line("Email: {$email}");
        $this->line("Password: {$password}");
        $this->newLine();

        // Dati per PeerTube
        $userData = [
            'peertube_username' => $username,
            'email' => $email,
            'name' => 'Test User',
            'peertube_display_name' => 'Test User',
            'peertube_password' => $password
        ];

        $this->info('🔄 Creando utente PeerTube...');

        try {
            $result = $peerTubeService->createUser($userData);

            if ($result['success']) {
                $this->info('✅ Utente creato con successo!');
                $this->newLine();
                $this->line('📋 Dettagli utente:');
                $this->line("   ID: " . ($result['peertube_user_id'] ?? 'N/A'));
                $this->line("   Username: " . $result['peertube_username']);
                $this->line("   Display Name: " . $result['peertube_display_name']);
                $this->line("   Password: " . $result['peertube_password_plain']);
                $this->newLine();
                
                $this->info('🎉 Test completato con successo!');
                return 0;
            } else {
                $this->error('❌ Creazione utente fallita!');
                $this->line('Errore: ' . ($result['error'] ?? 'Errore sconosciuto'));
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante la creazione: ' . $e->getMessage());
            return 1;
        }
    }
} 
