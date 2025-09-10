<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveBreadcrumbs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:breadcrumbs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rimuove tutti i breadcrumb da tutte le pagine';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Rimozione breadcrumb in corso...');
        
        $viewsPath = resource_path('views');
        $files = File::allFiles($viewsPath);
        $removed = 0;
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                
                // Pattern per trovare i breadcrumb
                $patterns = [
                    // Pattern completo per app-line-breadcrumbs
                    '/<ul class="app-line-breadcrumbs[^"]*"[^>]*>.*?<\/ul>/s',
                    // Pattern per breadcrumb generici
                    '/<nav[^>]*breadcrumb[^>]*>.*?<\/nav>/s',
                    '/<ol[^>]*breadcrumb[^>]*>.*?<\/ol>/s',
                    '/<ul[^>]*breadcrumb[^>]*>.*?<\/ul>/s',
                ];
                
                $originalContent = $content;
                
                foreach ($patterns as $pattern) {
                    $content = preg_replace($pattern, '', $content);
                }
                
                // Se il contenuto è cambiato, salva il file
                if ($content !== $originalContent) {
                    File::put($file->getPathname(), $content);
                    $this->line("✅ Rimosso breadcrumb da: " . $file->getRelativePathname());
                    $removed++;
                }
            }
        }
        
        $this->info("🎉 Rimozione completata! {$removed} file modificati.");
        
        return 0;
    }
}