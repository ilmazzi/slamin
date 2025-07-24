<?php

namespace App\Console\Commands;

use Spatie\Permission\Models\Permission;
use Illuminate\Console\Command;

class TestPermissionDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:permission-delete {permission_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa l\'eliminazione dei permessi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🗑️  TEST ELIMINAZIONE PERMESSI');
        $this->info('============================');

        $permissionId = $this->argument('permission_id');

        if ($permissionId) {
            $permission = Permission::find($permissionId);
            if (!$permission) {
                $this->error("❌ Permesso con ID {$permissionId} non trovato!");
                return 1;
            }
            $permissions = collect([$permission]);
        } else {
            // Trova permessi che non sono assegnati a ruoli
            $permissions = Permission::whereDoesntHave('roles')->get();
        }

        if ($permissions->isEmpty()) {
            $this->warn('⚠️  Nessun permesso trovato per il test');
            return 0;
        }

        $this->info("Trovati {$permissions->count()} permessi per il test:");

        foreach ($permissions as $permission) {
            $this->line("\n🔑 Permesso: {$permission->name}");
            $this->line("   ID: {$permission->id}");
            $this->line("   Display Name: {$permission->display_name}");
            $this->line("   Gruppo: {$permission->group}");
            $this->line("   Ruoli assegnati: {$permission->roles->count()}");
            
            if ($permission->roles->count() > 0) {
                $this->line("   ⚠️  Non può essere eliminato (ha ruoli assegnati)");
            } else {
                $this->line("   ✅ Può essere eliminato");
            }
        }

        $this->info("\n📊 RISULTATI:");
        $this->info("✅ Eliminabili: " . $permissions->where('roles_count', 0)->count());
        $this->info("⚠️  Non eliminabili: " . $permissions->where('roles_count', '>', 0)->count());
        $this->info("📊 Totale: " . $permissions->count());

        return 0;
    }
}
