<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Video;
use App\Models\Photo;
use App\Models\VideoLike;
use App\Models\VideoComment;
use App\Models\VideoSnap;
use App\Models\Notification;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use Illuminate\Console\Command;

class TestUserDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-deletion {user_id} {--dry-run : Mostra solo cosa verrebbe eliminato senza eliminare}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa l\'eliminazione completa di un utente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $dryRun = $this->option('dry-run');
        
        $this->info("🗑️  TEST ELIMINAZIONE UTENTE ID: {$userId}");
        $this->info("==========================================");

        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ Utente con ID {$userId} non trovato!");
            return 1;
        }

        $this->line("👤 Utente trovato:");
        $this->line("   Nome: {$user->name}");
        $this->line("   Email: {$user->email}");
        $this->line("   Nickname: {$user->nickname}");
        $this->line("   Ruoli: " . $user->roles->pluck('name')->implode(', '));
        $this->line("   Account PeerTube: " . ($user->hasPeerTubeAccount() ? 'Sì (ID: ' . $user->peertube_user_id . ')' : 'No'));

        // Conta tutti i dati associati
        $stats = $this->getUserStats($user);
        
        $this->info("\n📊 DATI ASSOCIATI:");
        $this->line("   Video: {$stats['videos']}");
        $this->line("   Foto: {$stats['photos']}");
        $this->line("   Like dati: {$stats['likes_given']}");
        $this->line("   Commenti: {$stats['comments']}");
        $this->line("   Snap: {$stats['snaps']}");
        $this->line("   Notifiche: {$stats['notifications']}");
        $this->line("   Inviti ricevuti: {$stats['invitations_received']}");
        $this->line("   Inviti inviati: {$stats['invitations_sent']}");
        $this->line("   Richieste eventi: {$stats['event_requests']}");
        $this->line("   Eventi organizzati: {$stats['organized_events']}");

        if ($dryRun) {
            $this->warn("\n⚠️  DRY RUN - Nessun dato verrà eliminato");
            return 0;
        }

        $this->warn("\n⚠️  ATTENZIONE: Questa operazione eliminerà definitivamente l'utente e tutti i suoi dati!");
        
        if (!$this->confirm('Sei sicuro di voler procedere?')) {
            $this->info("❌ Operazione annullata");
            return 0;
        }

        try {
            $this->info("\n🔄 Eliminazione in corso...");

            // Simula l'eliminazione chiamando il controller
            $controller = new \App\Http\Controllers\Admin\UserController();
            $response = $controller->destroy($user);

            if ($response->getData()->success) {
                $this->info("✅ Utente eliminato con successo!");
                $this->line("   Messaggio: " . $response->getData()->message);
            } else {
                $this->error("❌ Errore durante l'eliminazione: " . $response->getData()->message);
                return 1;
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Errore durante l'eliminazione: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Ottieni statistiche sui dati dell'utente
     */
    private function getUserStats(User $user): array
    {
        return [
            'videos' => $user->videos()->count(),
            'photos' => $user->photos()->count(),
            'likes_given' => VideoLike::where('user_id', $user->id)->count(),
            'comments' => VideoComment::where('user_id', $user->id)->count(),
            'snaps' => VideoSnap::where('user_id', $user->id)->count(),
            'notifications' => Notification::where('user_id', $user->id)->count(),
            'invitations_received' => EventInvitation::where('invited_user_id', $user->id)->count(),
            'invitations_sent' => EventInvitation::where('inviter_id', $user->id)->count(),
            'event_requests' => EventRequest::where('user_id', $user->id)->count(),
            'organized_events' => $user->organizedEvents()->count(),
        ];
    }
}
