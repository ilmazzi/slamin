<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MakeAdmin extends Command
{
    protected $signature = 'users:make-admin {email} {--password=} {--name=}';
    protected $description = 'Crea un utente admin o promuove un utente esistente ad admin';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password');
        $name = $this->option('name');

        $this->info("=== CREAZIONE UTENTE ADMIN ===");
        $this->line("Email: {$email}");

        // Cerca l'utente esistente
        $user = User::where('email', $email)->first();

        if ($user) {
            $this->info("✅ Utente trovato: {$user->name}");

            // Controlla se è già admin
            if ($user->hasRole('admin')) {
                $this->warn("⚠️  L'utente è già admin!");
                return;
            }

            // Aggiungi ruolo admin
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->assignRole($adminRole);
                $this->info("✅ Ruolo admin aggiunto a {$user->name}");
            } else {
                $this->error("❌ Ruolo 'admin' non trovato nel database!");
                return;
            }

        } else {
            $this->info("📝 Creazione nuovo utente...");

            // Richiedi password se non fornita
            if (!$password) {
                $password = $this->secret('Inserisci la password per il nuovo admin:');
                if (!$password) {
                    $this->error("❌ Password richiesta!");
                    return;
                }
            }

            // Richiedi nome se non fornito
            if (!$name) {
                $name = $this->ask('Inserisci il nome per il nuovo admin:');
                if (!$name) {
                    $this->error("❌ Nome richiesto!");
                    return;
                }
            }

            // Crea nuovo utente
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $this->info("✅ Nuovo utente creato: {$user->name}");

            // Aggiungi ruolo admin
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->assignRole($adminRole);
                $this->info("✅ Ruolo admin assegnato");
            } else {
                $this->error("❌ Ruolo 'admin' non trovato nel database!");
                return;
            }
        }

        $this->info("\n=== ACCESSO ADMIN ===");
        $this->line("Email: {$user->email}");
        $this->line("Password: " . ($password ? 'quella inserita' : 'quella esistente'));
        $this->line("URL Login: slamin.local/login");
        $this->line("URL Admin: slamin.local/admin/carousels");

        $this->info("\n✅ Admin creato/promosso con successo!");
    }
}
