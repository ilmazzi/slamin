<?php

namespace App\Console\Commands;

use App\Models\Carousel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckCarousels extends Command
{
    protected $signature = 'carousels:check';
    protected $description = 'Controlla i carousel e i loro file';

    public function handle()
    {
        $this->info('=== CONTROLLO CAROUSEL ===');

        $carousels = Carousel::all();

        if ($carousels->isEmpty()) {
            $this->warn('Nessun carousel trovato nel database!');
            return;
        }

        $this->info("Trovati {$carousels->count()} carousel:");

        foreach ($carousels as $carousel) {
            $this->line("\n--- Carousel ID: {$carousel->id} ---");
            $this->line("Titolo: {$carousel->title}");
            $this->line("Attivo: " . ($carousel->is_active ? 'SÌ' : 'NO'));
            $this->line("Ordine: {$carousel->order}");

            if ($carousel->image_path) {
                $this->line("Percorso immagine: {$carousel->image_path}");
                $exists = Storage::disk('public')->exists($carousel->image_path);
                $this->line("File esiste: " . ($exists ? 'SÌ' : 'NO'));

                if ($exists) {
                    $size = Storage::disk('public')->size($carousel->image_path);
                    $this->line("Dimensione: " . number_format($size) . " bytes");
                }
            } else {
                $this->warn("Nessun percorso immagine!");
            }

            if ($carousel->video_path) {
                $this->line("Percorso video: {$carousel->video_path}");
                $exists = Storage::disk('public')->exists($carousel->video_path);
                $this->line("File esiste: " . ($exists ? 'SÌ' : 'NO'));
            }
        }

        $this->info("\n=== FINE CONTROLLO ===");
    }
}
