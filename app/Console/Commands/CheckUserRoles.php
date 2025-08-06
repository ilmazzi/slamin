<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CheckUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-roles {email?} {--assign-admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user roles and assign admin role if needed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'poeta@poetryslam.it';
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Utente con email {$email} non trovato!");
            return 1;
        }
        
        $this->info("=== UTENTE: {$user->name} ({$user->email}) ===");
        $this->info("ID: {$user->id}");
        $this->info("Ruoli attuali: " . $user->getRoleNames()->implode(', ') ?: 'Nessun ruolo');
        $this->info("Is Admin: " . ($user->hasRole('admin') ? 'SI' : 'NO'));
        $this->info("Is Super Admin: " . ($user->hasRole('super-admin') ? 'SI' : 'NO'));
        
        if (!$user->hasRole('admin') && !$user->hasRole('super-admin')) {
            $this->warn("L'utente NON ha il ruolo admin!");
            
            if ($this->option('assign-admin') || $this->confirm('Vuoi assegnare il ruolo admin a questo utente?')) {
                $adminRole = Role::where('name', 'admin')->first();
                
                if ($adminRole) {
                    $user->assignRole($adminRole);
                    $this->info("✅ Ruolo admin assegnato con successo!");
                    $this->info("Nuovi ruoli: " . $user->fresh()->getRoleNames()->implode(', '));
                } else {
                    $this->error("❌ Ruolo admin non trovato nel database!");
                }
            }
        } else {
            $this->info("✅ L'utente ha già il ruolo admin!");
        }
        
        return 0;
    }
}
