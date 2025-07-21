<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Exception;

class TestPeerTubeAuth extends Command
{
    protected $signature = 'test:peertube-auth {username?} {password?} {--url=https://video.slamin.it}';
    protected $description = 'Test autenticazione PeerTube con credenziali specifiche';

    public function handle()
    {
        $this->info('🔐 TEST AUTENTICAZIONE PEERTUBE');
        $this->info('================================');
        $this->newLine();

        // Credenziali di test
        $username = $this->argument('username') ?: 'slamin';
        $password = $this->argument('password') ?: 'D1NUw4POzYezKTD97L1LSoSL';
        $baseUrl = $this->option('url');

        $this->info("Testando credenziali:");
        $this->line("Server: {$baseUrl}");
        $this->line("Username: {$username}");
        $this->line("Password: " . str_repeat('*', strlen($password)));
        $this->newLine();

        try {
            // 1. Ottieni client OAuth
            $this->info('1. Ottenendo client OAuth...');
            $clientResponse = Http::timeout(30)
                ->get("{$baseUrl}/api/v1/oauth-clients/local");

            if (!$clientResponse->successful()) {
                $this->error('❌ Errore ottenendo client OAuth: ' . $clientResponse->body());
                return 1;
            }

            $clientData = $clientResponse->json();
            $this->line('✅ Client OAuth ottenuto');
            $this->line("   Client ID: {$clientData['client_id']}");
            $this->newLine();

            // 2. Test autenticazione con token endpoint
            $this->info('2. Testando autenticazione...');

            $authData = [
                'client_id' => $clientData['client_id'],
                'client_secret' => $clientData['client_secret'],
                'grant_type' => 'password',
                'response_type' => 'code',
                'username' => $username,
                'password' => $password
            ];

            $tokenResponse = Http::timeout(30)
                ->asForm()
                ->post("{$baseUrl}/api/v1/users/token", $authData);

            if ($tokenResponse->successful()) {
                $tokenData = $tokenResponse->json();
                $this->line('✅ Autenticazione riuscita!');
                $this->line("   Access Token: " . substr($tokenData['access_token'], 0, 20) . '...');
                $this->line("   Token Type: {$tokenData['token_type']}");
                $this->line("   Expires In: {$tokenData['expires_in']} secondi");
                $this->line("   Refresh Token: " . substr($tokenData['refresh_token'], 0, 20) . '...');

                // 3. Test chiamata API con token
                $this->newLine();
                $this->info('3. Testando chiamata API...');

                $apiResponse = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $tokenData['access_token']
                    ])
                    ->get("{$baseUrl}/api/v1/users/me");

                if ($apiResponse->successful()) {
                    $userData = $apiResponse->json();
                    $this->line('✅ Chiamata API riuscita!');
                    $this->line("   Username: {$userData['username']}");
                    $this->line("   Email: {$userData['email']}");
                    $this->line("   Role: {$userData['role']['label']}");

                    // Verifica se è admin
                    if ($userData['role']['id'] === 1) {
                        $this->line('✅ Utente è ADMIN - Può caricare video');
                    } else {
                        $this->warn('⚠️  Utente NON è admin - Potrebbe non avere permessi di upload');
                    }
                } else {
                    $this->error('❌ Errore chiamata API: ' . $apiResponse->body());
                }

            } else {
                $this->error('❌ Autenticazione fallita: ' . $tokenResponse->body());
                $this->newLine();
                $this->info('Possibili cause:');
                $this->line('- Credenziali errate');
                $this->line('- Utente non esistente');
                $this->line('- Account bloccato');
                $this->line('- Server PeerTube non raggiungibile');
                return 1;
            }

        } catch (Exception $e) {
            $this->error('❌ Errore durante il test: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('🎉 Test completato con successo!');
        $this->info('Le credenziali funzionano correttamente.');

        return 0;
    }
}
