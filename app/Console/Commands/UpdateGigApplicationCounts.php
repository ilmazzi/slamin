<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gig;

class UpdateGigApplicationCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gigs:update-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggiorna i contatori delle candidature per tutti i gig';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Aggiornamento contatori candidature gig...');

        $gigs = Gig::all();
        $updated = 0;

        foreach ($gigs as $gig) {
            $applicationCount = $gig->applications()->count();
            $acceptedApplicationsCount = $gig->acceptedApplications()->count();

            if ($gig->application_count != $applicationCount ||
                $gig->accepted_applications_count != $acceptedApplicationsCount) {

                $gig->update([
                    'application_count' => $applicationCount,
                    'accepted_applications_count' => $acceptedApplicationsCount,
                ]);

                $updated++;
                $this->line("Aggiornato gig: {$gig->title} - Candidature: {$applicationCount}, Accettate: {$acceptedApplicationsCount}");
            }
        }

        $this->info("Completato! Aggiornati {$updated} gig su {$gigs->count()} totali.");
    }
}
