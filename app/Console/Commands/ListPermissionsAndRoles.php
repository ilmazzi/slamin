<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ListPermissionsAndRoles extends Command
{
    protected $signature = 'permissions:list {--group=} {--role=}';
    protected $description = 'Lista tutte le permission e ruoli con le loro descrizioni';

    public function handle()
    {
        $group = $this->option('group');
        $role = $this->option('role');

        if ($role) {
            $this->showRoleDetails($role);
        } else {
            $this->showPermissions($group);
            $this->showRoles();
        }
    }

    private function showPermissions($group = null)
    {
        $this->info('=== PERMISSION ===');

        $query = Permission::orderBy('group')->orderBy('name');
        if ($group) {
            $query->where('group', $group);
        }

        $permissions = $query->get();

        if ($permissions->isEmpty()) {
            $this->warn('Nessuna permission trovata.');
            return;
        }

        $currentGroup = null;
        foreach ($permissions as $permission) {
            if ($permission->group !== $currentGroup) {
                $currentGroup = $permission->group;
                $this->line("\n📁 Gruppo: " . strtoupper($currentGroup));
            }

            $this->line("  🔐 {$permission->name}");
            $this->line("     Nome: {$permission->display_name}");
            $this->line("     Descrizione: {$permission->description}");
        }

        $this->line("\n📊 Totale permission: {$permissions->count()}");
    }

    private function showRoles()
    {
        $this->info("\n=== RUOLI ===\n");

        $roles = Role::orderBy('name')->get();

        if ($roles->isEmpty()) {
            $this->warn('Nessun ruolo trovato.');
            return;
        }

        foreach ($roles as $role) {
            $this->line("👤 {$role->name}");
            $this->line("   Nome: {$role->display_name}");
            $this->line("   Descrizione: {$role->description}");

            $permissions = $role->permissions->pluck('name')->toArray();
            if (!empty($permissions)) {
                $this->line("   Permission: " . implode(', ', $permissions));
            } else {
                $this->line("   Permission: Nessuna");
            }
            $this->line("");
        }

        $this->line("📊 Totale ruoli: {$roles->count()}");
    }

    private function showRoleDetails($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("❌ Ruolo '{$roleName}' non trovato!");
            return;
        }

        $this->info("=== DETTAGLI RUOLO: {$roleName} ===");
        $this->line("Nome: {$role->display_name}");
        $this->line("Descrizione: {$role->description}");
        $this->line("Creato: {$role->created_at}");

        $permissions = $role->permissions;
        if ($permissions->isNotEmpty()) {
            $this->line("\n🔐 Permission assegnate:");
            foreach ($permissions as $permission) {
                $this->line("  • {$permission->name} - {$permission->display_name}");
            }
        } else {
            $this->line("\n⚠️  Nessuna permission assegnata");
        }

        $users = $role->users;
        if ($users->isNotEmpty()) {
            $this->line("\n👥 Utenti con questo ruolo:");
            foreach ($users as $user) {
                $this->line("  • {$user->name} ({$user->email})");
            }
        } else {
            $this->line("\n⚠️  Nessun utente con questo ruolo");
        }
    }
}
