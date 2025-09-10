<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Carousel;

class UpdateCarousels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:carousels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggiorna i caroselli per usare i nuovi placeholder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Aggiornamento caroselli...');

        $carousels = Carousel::all();
        $this->info("📊 Caroselli trovati: {$carousels->count()}");

        foreach ($carousels as $carousel) {
            $this->info("🔄 Aggiornando: {$carousel->title}");
            
            try {
                $carousel->updateContentCache();
                $this->info("✅ Aggiornato: {$carousel->title}");
            } catch (\Exception $e) {
                $this->error("❌ Errore aggiornando {$carousel->title}: " . $e->getMessage());
            }
        }

        $this->info('🎉 Aggiornamento caroselli completato!');
    }
}
