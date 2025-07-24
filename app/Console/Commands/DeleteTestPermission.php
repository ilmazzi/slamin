<?php

namespace App\Console\Commands;

use Spatie\Permission\Models\Permission;
use Illuminate\Console\Command;

class DeleteTestPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:delete-permission {permission_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa l\'eliminazione di un permesso specifico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissionId = $this->argument('permission_id');
        
        $this->info("🗑️  TEST ELIMINAZIONE PERMESSO ID: {$permissionId}");
        $this->info("==========================================");

        $permission = Permission::find($permissionId);
        
        if (!$permission) {
            $this->error("❌ Permesso con ID {$permissionId} non trovato!");
            return 1;
        }

        $this->line("🔑 Permesso trovato:");
        $this->line("   Nome: {$permission->name}");
        $this->line("   Display Name: {$permission->display_name}");
        $this->line("   Gruppo: {$permission->group}");
        $this->line("   Ruoli assegnati: {$permission->roles->count()}");

        if ($permission->roles->count() > 0) {
            $this->error("❌ Impossibile eliminare: il permesso è assegnato a dei ruoli!");
            return 1;
        }

        $this->info("✅ Permesso può essere eliminato. Procedo...");

        try {
            $permission->delete();
            $this->info("🎉 Permesso eliminato con successo!");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Errore durante l'eliminazione: " . $e->getMessage());
            return 1;
        }
    }
}
