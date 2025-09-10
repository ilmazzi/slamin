<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggiorna i template per usare i placeholder HTML';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Aggiornamento template...');

        // Aggiorna articles/index.blade.php
        $this->updateArticlesIndex();

        $this->info('🎉 Template aggiornati!');
    }

    private function updateArticlesIndex()
    {
        $filePath = resource_path('views/articles/index.blade.php');
        $content = File::get($filePath);

        // Pattern per aggiungere i link ai placeholder degli articoli
        $pattern = '/\{!! PlaceholderHelper::getArticlePlaceholderHtml\(([^)]+)\) !!}/';
        $replacement = '{!! PlaceholderHelper::getArticlePlaceholderHtml($1, route(\'articles.show\', $article->slug)) !!}';

        $newContent = preg_replace($pattern, $replacement, $content);

        if ($newContent !== $content) {
            File::put($filePath, $newContent);
            $this->info('✅ articles/index.blade.php aggiornato con link');
        } else {
            $this->info('ℹ️ articles/index.blade.php già aggiornato');
        }
    }
}
