<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;

class ApprovePendingVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos:approve-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Approva automaticamente tutti i video in stato pending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingVideos = Video::where('moderation_status', 'pending')->get();

        if ($pendingVideos->isEmpty()) {
            $this->info('Nessun video in stato pending trovato.');
            return;
        }

        $this->info("Trovati {$pendingVideos->count()} video in stato pending.");

        $bar = $this->output->createProgressBar($pendingVideos->count());
        $bar->start();

        foreach ($pendingVideos as $video) {
            $video->update([
                'moderation_status' => 'approved',
                'status' => 'active'
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Tutti i video sono stati approvati con successo!');
    }
}
