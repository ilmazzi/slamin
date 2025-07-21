<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckHardcodedTexts extends Command
{
    protected $signature = 'texts:check-hardcoded {--fix}';
    protected $description = 'Controlla i testi hardcoded nelle view e suggerisce traduzioni';

    private $italianTexts = [
        'Benvenuti' => 'home.welcome',
        'Welcome' => 'home.welcome',
        'Scopri' => 'home.discover',
        'Discover' => 'home.discover',
        'Partecipa' => 'home.participate',
        'Participate' => 'home.participate',
        'Visualizza' => 'common.view',
        'View' => 'common.view',
        'Gestisci' => 'common.manage',
        'Manage' => 'common.manage',
        'Crea' => 'common.create',
        'Create' => 'common.create',
        'Modifica' => 'common.edit',
        'Edit' => 'common.edit',
        'Elimina' => 'common.delete',
        'Delete' => 'common.delete',
        'Video Totali' => 'home.stats.total_videos',
        'Visualizzazioni' => 'videos.views',
        'Eventi' => 'home.stats.total_events',
        'Utenti' => 'home.stats.total_users',
        'Video Più Popolari' => 'home.popular_videos.title',
        'Prossimi Eventi' => 'home.recent_events.title',
        'Top Poeti' => 'home.top_poets.title',
        'Guarda' => 'home.popular_videos.watch',
        'Dettagli' => 'home.recent_events.details',
        'Profilo' => 'home.top_poets.profile',
        'Registrati' => 'home.cta.register',
        'Accedi' => 'home.cta.login',
        'Carica Video' => 'home.cta.upload_video',
        'Vedi Eventi' => 'home.cta.view_events',
        'Precedente' => 'home.carousel.previous',
        'Successivo' => 'home.carousel.next',
        'Previous' => 'home.carousel.previous',
        'Next' => 'home.carousel.next',
        'Gestione Carosello' => 'carousel.management',
        'Nuova Slide' => 'carousel.new_slide',
        'Modifica' => 'carousel.edit',
        'Visualizza' => 'carousel.view',
        'Elimina' => 'carousel.delete',
        'Conferma Eliminazione' => 'carousel.delete_confirm_title',
        'Sei sicuro di voler eliminare questa slide del carosello?' => 'carousel.delete_confirm_message',
        'Crea Prima Slide' => 'carousel.create_first_button',
        'Crea la tua prima slide per il carosello della home page' => 'carousel.create_first_message',
        'Caricato il' => 'videos.uploaded_on',
        'Test Views' => 'videos.test_views',
        'visualizzazioni' => 'videos.views',
        'video' => 'home.top_poets.videos_count',
        'di' => 'home.popular_videos.by',
    ];

    public function handle()
    {
        $this->info('=== CONTROLLO TESTI HARDCODED ===');

        $viewsPath = resource_path('views');
        $hardcodedTexts = [];

        // Cerca in tutte le view
        $this->scanDirectory($viewsPath, $hardcodedTexts);

        if (empty($hardcodedTexts)) {
            $this->info('✅ Nessun testo hardcoded trovato!');
            return;
        }

        $this->warn('⚠️  Testi hardcoded trovati:');
        foreach ($hardcodedTexts as $file => $texts) {
            $this->line("\n📁 {$file}:");
            foreach ($texts as $text => $suggestions) {
                $this->line("  • \"{$text}\"");
                if (!empty($suggestions)) {
                    $this->line("    Suggerimento: {{ __('{$suggestions}') }}");
                }
            }
        }

        if ($this->option('fix')) {
            $this->fixHardcodedTexts($hardcodedTexts);
        }
    }

    private function scanDirectory($path, &$hardcodedTexts)
    {
        $files = File::allFiles($path);

        foreach ($files as $file) {
            if ($file->getExtension() === 'blade.php') {
                $content = File::get($file->getPathname());
                $relativePath = str_replace(resource_path('views/'), '', $file->getPathname());

                $foundTexts = [];

                foreach ($this->italianTexts as $text => $translation) {
                    if (strpos($content, $text) !== false) {
                        $foundTexts[$text] = $translation;
                    }
                }

                if (!empty($foundTexts)) {
                    $hardcodedTexts[$relativePath] = $foundTexts;
                }
            }
        }
    }

    private function fixHardcodedTexts($hardcodedTexts)
    {
        $this->info('\n🔧 Applicazione correzioni...');

        foreach ($hardcodedTexts as $file => $texts) {
            $filePath = resource_path('views/' . $file);
            $content = File::get($filePath);

            foreach ($texts as $text => $translation) {
                $content = str_replace($text, "{{ __('{$translation}') }}", $content);
            }

            File::put($filePath, $content);
            $this->line("✅ Corretto: {$file}");
        }

        $this->info('✅ Tutte le correzioni applicate!');
    }
}
