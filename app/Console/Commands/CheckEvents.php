<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;

class CheckEvents extends Command
{
    protected $signature = 'check:events';
    protected $description = 'Controlla gli eventi nel database';

    public function handle()
    {
        $this->info("=== CONTROLLO EVENTI ===");

        $events = Event::with('organizer')->get();
        $this->info("Totale eventi: " . $events->count());

        foreach ($events as $event) {
            $organizerName = $event->organizer ? $event->organizer->name : 'N/A';
            $this->info("ID: {$event->id}, Titolo: {$event->title}, Organizzatore: {$organizerName} (ID: {$event->organizer_id}), Data: {$event->start_datetime}");
        }

        $this->info("\n=== CONTROLLO UTENTI ===");
        $users = User::all();
        $this->info("Totale utenti: " . $users->count());

        foreach ($users as $user) {
            $organizedCount = $user->organizedEvents()->count();
            $this->info("ID: {$user->id}, Nome: {$user->name}, Eventi organizzati: {$organizedCount}");
        }
    }
}
