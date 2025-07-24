<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\VideoUploadController;
use Exception;

class TestVideoUploadAccess extends Command
{
    protected $signature = 'test:video-upload-access {--user-id=} {--user-email=}';
    protected $description = 'Testa l\'accesso alla pagina di upload video per un utente';

    public function handle()
    {
        $this->info('🎬 TEST ACCESSO UPLOAD VIDEO');
        $this->info('============================');
        $this->newLine();

        // Trova l'utente
        $user = null;
        if ($userId = $this->option('user-id')) {
            $user = User::find($userId);
        } elseif ($userEmail = $this->option('user-email')) {
            $user = User::where('email', $userEmail)->first();
        } else {
            // Usa il primo utente con account PeerTube
            $user = User::whereNotNull('peertube_user_id')->first();
        }

        if (!$user) {
            $this->error('❌ Nessun utente trovato!');
            return 1;
        }

        $this->info("👤 Testando utente: {$user->name} ({$user->email})");
        $this->newLine();

        // Test 1: Verifica account PeerTube
        $this->info('1. Verifica account PeerTube:');
        $hasAccount = $user->hasPeerTubeAccount();
        $this->line("   Account PeerTube: " . ($hasAccount ? '✅ ATTIVO' : '❌ NON ATTIVO'));
        
        if ($hasAccount) {
            $this->line("   Username: {$user->peertube_username}");
            $this->line("   User ID: {$user->peertube_user_id}");
            $this->line("   Channel ID: " . ($user->peertube_channel_id ?: '❌ MANCANTE'));
        }
        $this->newLine();

        // Test 2: Verifica limiti video
        $this->info('2. Verifica limiti video:');
        $canUpload = $user->canUploadMoreVideos();
        $this->line("   Può caricare video: " . ($canUpload ? '✅ SÌ' : '❌ NO'));
        $this->line("   Video attuali: {$user->current_video_count}");
        $this->line("   Limite video: {$user->current_video_limit}");
        $this->line("   Video rimanenti: {$user->remaining_video_uploads}");
        $this->newLine();

        // Test 3: Simula accesso alla pagina upload
        $this->info('3. Simula accesso pagina upload:');
        
        try {
            // Simula il controllo del VideoUploadController
            if (!$user->canUploadMoreVideos()) {
                $this->line("   ❌ Redirect a: videos.upload-limit (limite raggiunto)");
                $this->line("   Motivo: L'utente ha raggiunto il limite di video");
            } elseif (!$user->hasPeerTubeAccount()) {
                $this->line("   ❌ Redirect a: dashboard (account PeerTube mancante)");
                $this->line("   Motivo: L'utente non ha un account PeerTube configurato");
            } else {
                $this->line("   ✅ Accesso consentito alla pagina upload");
                $this->line("   Motivo: Tutti i controlli superati");
            }
        } catch (Exception $e) {
            $this->line("   ❌ Errore durante il controllo: " . $e->getMessage());
        }
        $this->newLine();

        // Test 4: Verifica ruoli
        $this->info('4. Verifica ruoli:');
        $roles = $user->getRoleNames()->toArray();
        $this->line("   Ruoli: " . implode(', ', $roles));
        
        $peerTubeRoles = ['poet', 'organizer'];
        $hasPeerTubeRole = !empty(array_intersect($roles, $peerTubeRoles));
        $this->line("   Ha ruoli PeerTube: " . ($hasPeerTubeRole ? '✅ SÌ' : '❌ NO'));
        $this->newLine();

        // Test 5: Risultato finale
        $this->info('5. Risultato finale:');
        
        if (!$hasAccount) {
            $this->error("   ❌ L'utente NON può accedere all'upload video");
            $this->line("   Motivo: Account PeerTube non configurato");
            $this->line("   Soluzione: Creare account PeerTube per l'utente");
        } elseif (!$canUpload) {
            $this->error("   ❌ L'utente NON può accedere all'upload video");
            $this->line("   Motivo: Limite video raggiunto");
            $this->line("   Soluzione: Acquisto pacchetto premium o aumento limite");
        } else {
            $this->info("   ✅ L'utente PUÒ accedere all'upload video");
            $this->line("   Motivo: Tutti i controlli superati");
        }

        $this->newLine();
        $this->info('🎯 Test completato!');
        
        return 0;
    }
} 