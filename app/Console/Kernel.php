<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\UpdatePeerTubeVideoStatusJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Controlla lo stato dei video PeerTube ogni 5 minuti
        $schedule->job(new UpdatePeerTubeVideoStatusJob())
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->name('update-peertube-videos')
            ->description('Aggiorna stato, durata e thumbnail dei video PeerTube');

        // Pulisci i log ogni giorno alle 2:00
        $schedule->command('log:clear')
            ->dailyAt('02:00')
            ->name('clear-logs')
            ->description('Pulisce i log vecchi');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 
