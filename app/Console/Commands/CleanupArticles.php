<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CleanupArticles extends Command
{
    protected $signature = 'db:cleanup-articles {--force} {--dry-run}';
    protected $description = 'Elimina TUTTI gli articoli e le loro relazioni';

    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 MODALITÀ DRY-RUN: Nessuna eliminazione verrà eseguita');
        }

        $this->line('');
        $this->line('📰 PULIZIA ARTICOLI E RELAZIONI');
        $this->line('');

        // Mostra statistiche attuali
        $this->showCurrentStats();

        if (!$force && !$dryRun) {
            if (!$this->confirm('⚠️  ATTENZIONE: Eliminerà TUTTI gli articoli e le loro relazioni. Continuare?')) {
                $this->info('Operazione annullata.');
                return;
            }
        }

        $this->line('');
        $this->line('🚀 Inizio pulizia articoli...');
        $this->line('');

        // Disabilita foreign key checks
        if (!$dryRun) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $this->line('🔓 Foreign key checks disabilitati');
        }

        // Pulisci articoli e relazioni
        $this->cleanupArticlesAndRelations($dryRun);

        // Riabilita foreign key checks
        if (!$dryRun) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->line('🔒 Foreign key checks riabilitati');
        }

        $this->line('');
        $this->line('✅ Pulizia articoli completata!');
        $this->line('');
        $this->showFinalStats();
    }

    private function showCurrentStats()
    {
        $this->line('📊 STATISTICHE ATUALI ARTICOLI:');
        $this->line("   📰 Articoli: " . DB::table('articles')->count());
        $this->line("   📂 Categorie articoli: " . DB::table('article_categories')->count());
        $this->line("   🏷️ Tag articoli: " . DB::table('article_tags')->count());
        $this->line("   📐 Layout articoli: " . DB::table('article_layouts')->count());
        $this->line("   🚨 Segnalazioni articoli: " . DB::table('article_reports')->count());
        $this->line("   💬 Commenti articoli: " . DB::table('article_comments')->count());
        $this->line("   ❤️ Like articoli: " . DB::table('article_likes')->count());
        $this->line("   ❤️ Like unificati: " . DB::table('unified_likes')->count());
        $this->line("   💬 Commenti unificati: " . DB::table('unified_comments')->count());
        $this->line("   👁️ Visualizzazioni unificate: " . DB::table('unified_views')->count());
        $this->line('');
    }

    private function showFinalStats()
    {
        $this->line('📊 STATISTICHE FINALI ARTICOLI:');
        $this->line("   📰 Articoli: " . DB::table('articles')->count());
        $this->line("   📂 Categorie articoli: " . DB::table('article_categories')->count());
        $this->line("   🏷️ Tag articoli: " . DB::table('article_tags')->count());
        $this->line("   📐 Layout articoli: " . DB::table('article_layouts')->count());
        $this->line("   🚨 Segnalazioni articoli: " . DB::table('article_reports')->count());
        $this->line("   💬 Commenti articoli: " . DB::table('article_comments')->count());
        $this->line("   ❤️ Like articoli: " . DB::table('article_likes')->count());
        $this->line("   ❤️ Like unificati: " . DB::table('unified_likes')->count());
        $this->line("   💬 Commenti unificati: " . DB::table('unified_comments')->count());
        $this->line("   👁️ Visualizzazioni unificate: " . DB::table('unified_views')->count());
        $this->line('');
    }

    private function cleanupArticlesAndRelations($dryRun)
    {
        $this->line('🗑️  Eliminazione articoli e relazioni...');

        // Ordine di eliminazione: prima le relazioni, poi gli articoli
        $tablesToClean = [
            // 1. Prima elimina le relazioni che dipendono dagli articoli
            'article_comments',      // Commenti articoli
            'article_likes',         // Like articoli
            'article_reports',       // Segnalazioni articoli
            'article_tag',           // Tag degli articoli (tabella pivot)
            'article_layouts',       // Layout articoli
            'article_tags',          // Tag articoli
            'article_categories',    // Categorie articoli
            
            // 2. Poi elimina i modelli unificati che potrebbero riferirsi agli articoli
            'unified_likes',         // Like unificati
            'unified_comments',      // Commenti unificati
            'unified_views',         // Visualizzazioni unificate
            
            // 3. Infine elimina gli articoli
            'articles',              // Articoli
        ];

        foreach ($tablesToClean as $table) {
            try {
                $count = DB::table($table)->count();
                $this->line("   Trovati {$count} record in {$table}");
                
                if (!$dryRun && $count > 0) {
                    // Elimina TUTTI i record dalla tabella
                    DB::statement("DELETE FROM {$table}");
                    $this->line("   ✅ {$count} record eliminati da {$table}");
                } elseif ($dryRun) {
                    $this->line("   🔍 DRY-RUN: Eliminerebbe {$count} record da {$table}");
                }
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione {$table}: " . $e->getMessage());
            }
        }

        // Pulisci anche i file degli articoli
        if (!$dryRun) {
            $this->line('');
            $this->line('🧹 Pulizia file articoli...');
            
            try {
                // Pulisci file temporanei di upload
                $tempDirs = ['temp', 'uploads/temp', 'public/temp', 'storage/app/public/articles'];
                foreach ($tempDirs as $dir) {
                    if (Storage::exists($dir)) {
                        $files = Storage::allFiles($dir);
                        foreach ($files as $file) {
                            Storage::delete($file);
                        }
                        $this->line("   ✅ File eliminati da {$dir}");
                    }
                }
            } catch (\Exception $e) {
                $this->error('   ❌ Errore pulizia file: ' . $e->getMessage());
            }
        }
    }
}