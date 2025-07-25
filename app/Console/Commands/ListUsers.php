<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    protected $signature = 'users:list';
    protected $description = 'List all users';

    public function handle()
    {
        $this->info('Utenti esistenti:');

        $users = User::all();

        if ($users->isEmpty()) {
            $this->warn('Nessun utente trovato');
            return 0;
        }

        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $this->line("- {$user->email} (ID: {$user->id}, Ruoli: {$roles})");
        }

        return 0;
    }
}
