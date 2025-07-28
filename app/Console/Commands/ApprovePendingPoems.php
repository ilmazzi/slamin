<?php

namespace App\Console\Commands;

use App\Models\Poem;
use Illuminate\Console\Command;

class ApprovePendingPoems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poems:approve {--all : Approva tutte le poesie in attesa} {--id= : ID specifico della poesia da approvare}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Approva le poesie in attesa di moderazione';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sistema di approvazione poesie');
        $this->newLine();

        if ($this->option('id')) {
            $poemId = $this->option('id');
            $poem = Poem::find($poemId);

            if (!$poem) {
                $this->error("Poesia con ID {$poemId} non trovata.");
                return 1;
            }

            $this->approvePoem($poem);
            return 0;
        }

        if ($this->option('all')) {
            $pendingPoems = Poem::where('moderation_status', 'pending')->get();

            if ($pendingPoems->isEmpty()) {
                $this->info('Nessuna poesia in attesa di approvazione.');
                return 0;
            }

            $this->info("Trovate {$pendingPoems->count()} poesie in attesa di approvazione.");

            if ($this->confirm('Vuoi approvare tutte le poesie?')) {
                $bar = $this->output->createProgressBar($pendingPoems->count());
                $bar->start();

                foreach ($pendingPoems as $poem) {
                    $poem->update(['moderation_status' => 'approved']);
                    $bar->advance();
                }

                $bar->finish();
                $this->newLine();
                $this->info('Tutte le poesie sono state approvate con successo!');
            }

            return 0;
        }

        // Modalità interattiva
        $pendingPoems = Poem::where('moderation_status', 'pending')->get();

        if ($pendingPoems->isEmpty()) {
            $this->info('Nessuna poesia in attesa di approvazione.');
            return 0;
        }

        $this->info("Trovate {$pendingPoems->count()} poesie in attesa di approvazione:");
        $this->newLine();

        $poems = $pendingPoems->map(function ($poem) {
            return [
                'id' => $poem->id,
                'title' => $poem->title,
                'author' => $poem->user ? $poem->user->name : 'N/A',
                'category' => $poem->category,
                'created_at' => $poem->created_at->format('d/m/Y H:i')
            ];
        })->toArray();

        $this->table(['ID', 'Titolo', 'Autore', 'Categoria', 'Data Creazione'], $poems);

        $choice = $this->choice(
            'Cosa vuoi fare?',
            ['approva_tutte' => 'Approva tutte', 'approva_singola' => 'Approva una singola poesia', 'esci' => 'Esci'],
            'approva_tutte'
        );

        switch ($choice) {
            case 'approva_tutte':
                $bar = $this->output->createProgressBar($pendingPoems->count());
                $bar->start();

                foreach ($pendingPoems as $poem) {
                    $poem->update(['moderation_status' => 'approved']);
                    $bar->advance();
                }

                $bar->finish();
                $this->newLine();
                $this->info('Tutte le poesie sono state approvate con successo!');
                break;

            case 'approva_singola':
                $poemId = $this->ask('Inserisci l\'ID della poesia da approvare');
                $poem = Poem::find($poemId);

                if (!$poem) {
                    $this->error("Poesia con ID {$poemId} non trovata.");
                    return 1;
                }

                $this->approvePoem($poem);
                break;

            case 'esci':
                $this->info('Operazione annullata.');
                break;
        }

        return 0;
    }

    private function approvePoem(Poem $poem)
    {
        $this->info("Approvazione poesia: {$poem->title}");
        $this->info("Autore: " . ($poem->user ? $poem->user->name : 'N/A'));
        $this->info("Categoria: {$poem->category}");
        $this->newLine();

        if ($this->confirm('Confermi l\'approvazione di questa poesia?')) {
            $poem->update(['moderation_status' => 'approved']);
            $this->info('Poesia approvata con successo!');
        } else {
            $this->info('Operazione annullata.');
        }
    }
}
