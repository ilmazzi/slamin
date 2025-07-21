<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckAdminUsers extends Command
{
    protected $signature = 'users:check-admin';
    protected $description = 'Controlla gli utenti admin nel sistema';

    public function handle()
    {
        $this->info('=== CONTROLLO UTENTI ADMIN ===');

        $users = User::with('roles')->get();

        if ($users->isEmpty()) {
            $this->warn('Nessun utente trovato nel sistema!');
            return;
        }

        $this->info("Trovati {$users->count()} utenti:");

        $hasAdmin = false;

        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->toArray();
            $isAdmin = in_array('admin', $roles);

            if ($isAdmin) {
                $hasAdmin = true;
                $this->line("\n--- UTENTE ADMIN ---");
                $this->line("ID: {$user->id}");
                $this->line("Nome: {$user->name}");
                $this->line("Email: {$user->email}");
                $this->line("Ruoli: " . implode(', ', $roles));
                $this->line("Creato: {$user->created_at}");
            } else {
                $this->line("\n--- UTENTE NORMALE ---");
                $this->line("ID: {$user->id}");
                $this->line("Nome: {$user->name}");
                $this->line("Email: {$user->email}");
                $this->line("Ruoli: " . (empty($roles) ? 'Nessun ruolo' : implode(', ', $roles)));
            }
        }

        if (!$hasAdmin) {
            $this->warn("\n⚠️  NESSUN UTENTE ADMIN TROVATO!");
            $this->info("Per creare un admin, usa: php artisan users:make-admin <email>");
        } else {
            $this->info("\n✅ Utenti admin presenti nel sistema");
        }

        $this->info("\n=== FINE CONTROLLO ===");
    }
}
