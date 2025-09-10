<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Carousel;
use Illuminate\Support\Facades\Log;

class CleanupCarousels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:carousels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulisce i caroselli da URL placeholder sbagliati';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Pulizia caroselli in corso...');
        
        $carousels = Carousel::all();
        $cleaned = 0;
        
        foreach ($carousels as $carousel) {
            $needsUpdate = false;
            
            // Controlla se image_path contiene URL placeholder sbagliati
            if ($carousel->image_path && (
                str_contains($carousel->image_path, 'placeholder/poem-placeholder.jpg') ||
                str_contains($carousel->image_path, 'placeholder/article-placeholder.jpg') ||
                str_contains($carousel->image_path, 'data:image/svg+xml')
            )) {
                $this->line("Carosello ID {$carousel->id}: rimuovo image_path sbagliato: {$carousel->image_path}");
                $carousel->image_path = null;
                $needsUpdate = true;
            }
            
            // Controlla se content_image_url contiene URL placeholder sbagliati
            if ($carousel->content_image_url && (
                str_contains($carousel->content_image_url, 'placeholder/poem-placeholder.jpg') ||
                str_contains($carousel->content_image_url, 'placeholder/article-placeholder.jpg') ||
                str_contains($carousel->content_image_url, 'data:image/svg+xml')
            )) {
                $this->line("Carosello ID {$carousel->id}: rimuovo content_image_url sbagliato: {$carousel->content_image_url}");
                $carousel->content_image_url = null;
                $needsUpdate = true;
            }
            
            if ($needsUpdate) {
                $carousel->save();
                $cleaned++;
            }
        }
        
        $this->info("Pulizia completata! {$cleaned} caroselli aggiornati.");
        
        return 0;
    }
}