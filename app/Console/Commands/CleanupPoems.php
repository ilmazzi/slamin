<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poem;
use App\Models\PoemComment;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CleanupPoems extends Command
{
    protected $signature = 'cleanup:poems 
                            {--force : Forza l\'eliminazione senza conferma}
                            {--dry-run : Mostra cosa verrebbe eliminato senza eseguire}
                            {--keep-images : Mantieni le immagini nel filesystem}';
    
    protected $description = 'Elimina tutte le poesie dal database e opzionalmente le loro immagini';

    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');
        $keepImages = $this->option('keep-images');

        $this->info('📝 PULIZIA POESIE');
        $this->line('');

        // Conta le poesie
        $poemsCount = Poem::count();
        $commentsCount = PoemComment::count();
        $reportsCount = Report::where('reportable_type', 'App\Models\Poem')->count();

        $this->info("📊 Statistiche attuali:");
        $this->line("   - Poesie: {$poemsCount}");
        $this->line("   - Commenti alle poesie: {$commentsCount}");
        $this->line("   - Segnalazioni poesie: {$reportsCount}");
        $this->line('');

        if ($poemsCount == 0) {
            $this->info('✅ Nessuna poesia da eliminare.');
            return 0;
        }

        if ($dryRun) {
            $this->warn('🔍 MODALITÀ DRY-RUN: Nessuna eliminazione verrà eseguita');
            $this->line('');
            
            $poems = Poem::with('user')->get();
            foreach ($poems as $poem) {
                $title = $poem->title ?: 'Poesia senza titolo';
                $author = $poem->user->getDisplayName();
                $this->line("   🔍 Eliminerebbe: '{$title}' di {$author} (ID: {$poem->id})");
                
                if ($poem->thumbnail_path && !$keepImages) {
                    $this->line("      📷 Eliminerebbe anche l'immagine: {$poem->thumbnail_path}");
                }
            }
            
            $this->line('');
            $this->info('💡 Per eseguire l\'eliminazione, rimuovi l\'opzione --dry-run');
            return 0;
        }

        if (!$force) {
            $this->warn('⚠️ ATTENZIONE: Questa operazione è IRREVERSIBILE!');
            $this->line('');
            
            if (!$this->confirm('Sei SICURO di voler eliminare TUTTE le poesie?')) {
                $this->info('❌ Operazione annullata.');
                return 0;
            }
        }

        $this->info('🗑️ Inizio eliminazione...');
        $this->line('');

        $deletedPoems = 0;
        $deletedImages = 0;
        $errors = 0;

        try {
            DB::transaction(function () use (&$deletedPoems, &$deletedImages, &$errors, $keepImages) {
                $poems = Poem::all();
                
                foreach ($poems as $poem) {
                    try {
                        $title = $poem->title ?: 'Poesia senza titolo';
                        $author = $poem->user->getDisplayName();
                        
                        // Elimina immagine se presente e richiesto
                        if ($poem->thumbnail_path && !$keepImages) {
                            if (Storage::disk('public')->exists($poem->thumbnail_path)) {
                                Storage::disk('public')->delete($poem->thumbnail_path);
                                $deletedImages++;
                                $this->line("   📷 Eliminata immagine: {$poem->thumbnail_path}");
                            }
                        }
                        
                        // Elimina la poesia (cascade eliminerà commenti e relazioni)
                        $poem->delete();
                        $deletedPoems++;
                        $this->line("   ✅ Eliminata: '{$title}' di {$author}");
                        
                    } catch (\Exception $e) {
                        $errors++;
                        $this->error("   ❌ Errore eliminazione poesia {$poem->id}: " . $e->getMessage());
                    }
                }
            });

        } catch (\Exception $e) {
            $this->error('❌ Errore durante la transazione: ' . $e->getMessage());
            return 1;
        }

        $this->line('');
        $this->info('📊 Risultati:');
        $this->line("   ✅ Poesie eliminate: {$deletedPoems}");
        
        if (!$keepImages) {
            $this->line("   📷 Immagini eliminate: {$deletedImages}");
        } else {
            $this->line("   📷 Immagini mantenute (--keep-images)");
        }
        
        if ($errors > 0) {
            $this->line("   ❌ Errori: {$errors}");
        }

        // Verifica finale
        $remainingPoems = Poem::count();
        $remainingComments = PoemComment::count();
        $remainingReports = Report::where('reportable_type', 'App\Models\Poem')->count();

        $this->line('');
        $this->info('🔍 Verifica finale:');
        $this->line("   - Poesie rimanenti: {$remainingPoems}");
        $this->line("   - Commenti rimanenti: {$remainingComments}");
        $this->line("   - Segnalazioni rimanenti: {$remainingReports}");

        if ($remainingPoems == 0) {
            $this->info('');
            $this->info('🎉 Pulizia completata con successo!');
        } else {
            $this->warn('⚠️ Alcune poesie potrebbero non essere state eliminate.');
        }

        return 0;
    }
}
