<?php

namespace App\Console\Commands;

use App\Models\Carousel;
use Illuminate\Console\Command;

class UpdateCarouselContentCache extends Command
{
    protected $signature = 'carousels:update-cache';
    protected $description = 'Aggiorna la cache dei contenuti referenziati nei carousel';

    public function handle()
    {
        $this->info('=== AGGIORNAMENTO CACHE CAROUSEL ===');

        $carousels = Carousel::withContent()->get();

        if ($carousels->isEmpty()) {
            $this->warn('Nessun carousel con contenuti referenziati trovato!');
            return;
        }

        $this->info("Trovati {$carousels->count()} carousel con contenuti referenziati:");

        $updated = 0;
        $errors = 0;

        foreach ($carousels as $carousel) {
            $this->line("\n--- Carousel ID: {$carousel->id} ---");
            $this->line("Tipo: {$carousel->content_type}");
            $this->line("ID Contenuto: {$carousel->content_id}");
            $this->line("Titolo: {$carousel->display_title}");

            try {
                $carousel->updateContentCache();
                $this->line("✅ Cache aggiornata con successo");
                $updated++;
            } catch (\Exception $e) {
                $this->error("❌ Errore: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\n=== RISULTATI ===");
        $this->info("Aggiornati: {$updated}");
        $this->info("Errori: {$errors}");
        $this->info("=== FINE AGGIORNAMENTO ===");
    }
}
