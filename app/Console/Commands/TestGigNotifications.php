<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\User;
use App\Models\Notification;

class TestGigNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gigs:test-notifications {gig_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa il sistema di notifiche per i gig';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gigId = $this->argument('gig_id');

        if ($gigId) {
            $gig = Gig::find($gigId);
            if (!$gig) {
                $this->error("Gig con ID {$gigId} non trovato!");
                return 1;
            }
        } else {
            $gig = Gig::first();
            if (!$gig) {
                $this->error("Nessun gig trovato nel database!");
                return 1;
            }
        }

        $this->info("Testando notifiche per il gig: {$gig->title}");

        // Test 1: Notifica nuova candidatura
        $this->info("\n1. Test notifica nuova candidatura...");
        $user = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'audience');
        })->first();

        if ($user) {
            $application = GigApplication::create([
                'gig_id' => $gig->id,
                'user_id' => $user->id,
                'message' => 'Test candidatura',
                'status' => 'pending'
            ]);

            Notification::createGigApplication($application);
            $this->info("✓ Notifica nuova candidatura creata");
        } else {
            $this->warn("Nessun utente non-audience trovato per il test");
        }

        // Test 2: Notifica candidatura accettata
        $this->info("\n2. Test notifica candidatura accettata...");
        $application = $gig->applications()->first();
        if ($application) {
            Notification::createGigApplicationResponse($application, 'accepted');
            $this->info("✓ Notifica candidatura accettata creata");
        } else {
            $this->warn("Nessuna candidatura trovata per il test");
        }

        // Test 3: Notifica candidatura rifiutata
        $this->info("\n3. Test notifica candidatura rifiutata...");
        if ($application) {
            Notification::createGigApplicationResponse($application, 'rejected');
            $this->info("✓ Notifica candidatura rifiutata creata");
        }

        // Test 4: Notifica gig chiuso
        $this->info("\n4. Test notifica gig chiuso...");
        Notification::createGigClosed($gig);
        $this->info("✓ Notifica gig chiuso creata");

        // Test 5: Notifica gig riaperto
        $this->info("\n5. Test notifica gig riaperto...");
        Notification::createGigReopened($gig);
        $this->info("✓ Notifica gig riaperto creata");

        // Test 6: Notifica gig condiviso
        $this->info("\n6. Test notifica gig condiviso...");
        $user = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'audience');
        })->first();

        if ($user) {
            Notification::createGigShared($gig, $user);
            $this->info("✓ Notifica gig condiviso creata");
        }

        // Test 7: Notifica messaggio globale
        $this->info("\n7. Test notifica messaggio globale...");
        if ($user) {
            Notification::createGigGlobalMessage($gig, 'Test messaggio globale', $user);
            $this->info("✓ Notifica messaggio globale creata");
        }

        $this->info("\n✅ Tutti i test completati!");
        $this->info("Controlla la tabella notifications per vedere le notifiche create.");

        return 0;
    }
}
