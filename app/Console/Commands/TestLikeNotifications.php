<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;
use App\Http\Controllers\LikeController;

class TestLikeNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:like-notifications {event_id?} {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the like notification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->argument('event_id') ?? 6;
        $userId = $this->argument('user_id') ?? 2;

        $this->info("🧪 Testando notifiche like per evento {$eventId} e utente {$userId}");

        // Trova evento e utente
        $event = Event::find($eventId);
        $user = User::find($userId);

        if (!$event) {
            $this->error("❌ Evento {$eventId} non trovato");
            return 1;
        }

        if (!$user) {
            $this->error("❌ Utente {$userId} non trovato");
            return 1;
        }

        $this->info("📋 Dettagli:");
        $this->line("   Evento: {$event->title} (ID: {$event->id})");
        $this->line("   Organizer: {$event->organizer->name} (ID: {$event->organizer_id})");
        $this->line("   User: {$user->name} (ID: {$user->id})");

        // Controlla se è il proprio contenuto
        if ($event->organizer_id === $user->id) {
            $this->warn("⚠️  L'utente sta likando il proprio contenuto - nessuna notifica dovrebbe essere inviata");
        } else {
            $this->info("✅ L'utente sta likando contenuto di un altro - notifica dovrebbe essere inviata");
        }

        // Conta notifiche esistenti
        $existingNotifications = Notification::where('user_id', $event->organizer_id)
            ->where('type', 'content_liked')
            ->count();

        $this->info("📊 Notifiche like esistenti per organizer: {$existingNotifications}");

        // Simula la creazione della notifica
        $this->info("🔧 Simulando creazione notifica...");
        
        $likeController = new LikeController();
        $reflection = new \ReflectionClass($likeController);
        $method = $reflection->getMethod('sendLikeNotification');
        $method->setAccessible(true);
        
        try {
            $method->invoke($likeController, $event, $user);
            $this->info("✅ Notifica simulata con successo");
        } catch (\Exception $e) {
            $this->error("❌ Errore durante la simulazione: " . $e->getMessage());
            return 1;
        }

        // Conta notifiche dopo la simulazione
        $newNotifications = Notification::where('user_id', $event->organizer_id)
            ->where('type', 'content_liked')
            ->count();

        $this->info("📊 Notifiche like dopo simulazione: {$newNotifications}");

        if ($newNotifications > $existingNotifications) {
            $this->info("✅ Notifica creata con successo!");
        } else {
            $this->warn("⚠️  Nessuna nuova notifica creata");
        }

        // Mostra le notifiche più recenti
        $recentNotifications = Notification::where('user_id', $event->organizer_id)
            ->where('type', 'content_liked')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        if ($recentNotifications->count() > 0) {
            $this->info("📋 Notifiche recenti:");
            foreach ($recentNotifications as $notification) {
                $this->line("   - {$notification->message} ({$notification->created_at})");
            }
        }

        return 0;
    }
} 