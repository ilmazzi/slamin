<?php

namespace App\Console\Commands;

use Spatie\Permission\Models\Permission;
use Illuminate\Console\Command;

class CreateTestPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-permission {name} {display_name} {group=system}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un permesso di test';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $displayName = $this->argument('display_name');
        $group = $this->argument('group');
        
        $this->info("➕ CREAZIONE PERMESSO DI TEST");
        $this->info("=============================");

        // Verifica se il permesso esiste già
        $existingPermission = Permission::where('name', $name)->first();
        if ($existingPermission) {
            $this->error("❌ Permesso '{$name}' esiste già!");
            return 1;
        }

        try {
            $permission = Permission::create([
                'name' => $name,
                'display_name' => $displayName,
                'group' => $group,
                'description' => 'Permesso di test creato automaticamente'
            ]);

            $this->info("✅ Permesso creato con successo!");
            $this->line("   ID: {$permission->id}");
            $this->line("   Nome: {$permission->name}");
            $this->line("   Display Name: {$permission->display_name}");
            $this->line("   Gruppo: {$permission->group}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Errore durante la creazione: " . $e->getMessage());
            return 1;
        }
    }
}
