<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\User;
use App\Http\Controllers\ViewController;

class TestEventViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:event-views {event_id?} {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the event views system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->argument('event_id') ?? 6;
        $userId = $this->argument('user_id') ?? 2;

        $this->info("🧪 Testando visualizzazioni evento {$eventId} per utente {$userId}");

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
        $this->line("   View count iniziale: {$event->view_count}");

        // Controlla se è il proprio contenuto
        if ($event->organizer_id === $user->id) {
            $this->warn("⚠️  L'utente è l'organizer - visualizzazione non dovrebbe essere incrementata");
        } else {
            $this->info("✅ L'utente non è l'organizer - visualizzazione dovrebbe essere incrementata");
        }

        // Simula la visualizzazione
        $this->info("🔧 Simulando visualizzazione...");
        
        $viewController = new ViewController();
        $reflection = new \ReflectionClass($viewController);
        $method = $reflection->getMethod('increment');
        $method->setAccessible(true);
        
        // Crea una request simulata
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'viewable_type' => 'event',
            'viewable_id' => $event->id
        ]);
        
        // Simula l'autenticazione
        \Auth::login($user);
        
        try {
            $response = $method->invoke($viewController, $request);
            $responseData = json_decode($response->getContent(), true);
            
            if ($responseData['success']) {
                $this->info("✅ Visualizzazione incrementata con successo");
                $this->line("   Nuovo view count: {$responseData['view_count']}");
            } else {
                $this->warn("⚠️  Visualizzazione non incrementata: {$responseData['message']}");
            }
        } catch (\Exception $e) {
            $this->error("❌ Errore durante la simulazione: " . $e->getMessage());
            return 1;
        }

        // Verifica il nuovo view count nel database
        $event->refresh();
        $this->info("📊 View count finale nel database: {$event->view_count}");

        return 0;
    }
} 