<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GroupsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========================================
        // CREAZIONE PERMISSIONS PER I GRUPPI
        // ========================================

        $groupPermissions = [
            // Permissions per la creazione e gestione base dei gruppi
            'groups.create' => 'groups',
            'groups.view.public' => 'groups',
            'groups.view.private' => 'groups',
            'groups.edit.own' => 'groups',
            'groups.delete.own' => 'groups',
            
            // Permissions per la gestione membri
            'groups.manage.members' => 'groups',
            'groups.invite' => 'groups',
            'groups.remove.members' => 'groups',
            'groups.promote.members' => 'groups',
            'groups.demote.members' => 'groups',
            
            // Permissions per la gestione inviti e richieste
            'groups.manage.invitations' => 'groups',
            'groups.manage.join_requests' => 'groups',
            'groups.join.public' => 'groups',
            'groups.join.private' => 'groups',
            
            // Permissions per la moderazione dei gruppi
            'groups.moderate' => 'moderation',
            'groups.suspend' => 'moderation',
            'groups.delete.any' => 'moderation',
            
            // Permissions per eventi di gruppo
            'groups.events.create' => 'groups',
            'groups.events.manage' => 'groups',
            'groups.events.view' => 'groups',
        ];

        // Creo le permissions
        foreach ($groupPermissions as $permission => $group) {
            Permission::firstOrCreate([
                'name' => $permission,
                'group' => $group,
            ]);
        }

        // ========================================
        // AGGIORNAMENTO RUOLI ESISTENTI
        // ========================================

        // Admin - può fare tutto
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'groups.create',
                'groups.view.public',
                'groups.view.private',
                'groups.edit.own',
                'groups.delete.own',
                'groups.manage.members',
                'groups.invite',
                'groups.remove.members',
                'groups.promote.members',
                'groups.demote.members',
                'groups.manage.invitations',
                'groups.manage.join_requests',
                'groups.join.public',
                'groups.join.private',
                'groups.moderate',
                'groups.suspend',
                'groups.delete.any',
                'groups.events.create',
                'groups.events.manage',
                'groups.events.view',
            ]);
        }

        // Moderator - può moderare e gestire gruppi
        $moderatorRole = Role::where('name', 'moderator')->first();
        if ($moderatorRole) {
            $moderatorRole->givePermissionTo([
                'groups.view.public',
                'groups.view.private',
                'groups.moderate',
                'groups.suspend',
                'groups.events.view',
            ]);
        }

        // Poet - può creare e gestire i propri gruppi
        $poetRole = Role::where('name', 'poet')->first();
        if ($poetRole) {
            $poetRole->givePermissionTo([
                'groups.create',
                'groups.view.public',
                'groups.view.private',
                'groups.edit.own',
                'groups.delete.own',
                'groups.manage.members',
                'groups.invite',
                'groups.remove.members',
                'groups.promote.members',
                'groups.demote.members',
                'groups.manage.invitations',
                'groups.manage.join_requests',
                'groups.join.public',
                'groups.join.private',
                'groups.events.create',
                'groups.events.manage',
                'groups.events.view',
            ]);
        }

        // Organizer - può creare e gestire i propri gruppi
        $organizerRole = Role::where('name', 'organizer')->first();
        if ($organizerRole) {
            $organizerRole->givePermissionTo([
                'groups.create',
                'groups.view.public',
                'groups.view.private',
                'groups.edit.own',
                'groups.delete.own',
                'groups.manage.members',
                'groups.invite',
                'groups.remove.members',
                'groups.promote.members',
                'groups.demote.members',
                'groups.manage.invitations',
                'groups.manage.join_requests',
                'groups.join.public',
                'groups.join.private',
                'groups.events.create',
                'groups.events.manage',
                'groups.events.view',
            ]);
        }

        // Venue Owner - può creare e gestire i propri gruppi
        $venueOwnerRole = Role::where('name', 'venue-owner')->first();
        if ($venueOwnerRole) {
            $venueOwnerRole->givePermissionTo([
                'groups.create',
                'groups.view.public',
                'groups.view.private',
                'groups.edit.own',
                'groups.delete.own',
                'groups.manage.members',
                'groups.invite',
                'groups.remove.members',
                'groups.promote.members',
                'groups.demote.members',
                'groups.manage.invitations',
                'groups.manage.join_requests',
                'groups.join.public',
                'groups.join.private',
                'groups.events.create',
                'groups.events.manage',
                'groups.events.view',
            ]);
        }

        // Host - può creare e gestire i propri gruppi
        $hostRole = Role::where('name', 'host')->first();
        if ($hostRole) {
            $hostRole->givePermissionTo([
                'groups.create',
                'groups.view.public',
                'groups.view.private',
                'groups.edit.own',
                'groups.delete.own',
                'groups.manage.members',
                'groups.invite',
                'groups.remove.members',
                'groups.promote.members',
                'groups.demote.members',
                'groups.manage.invitations',
                'groups.manage.join_requests',
                'groups.join.public',
                'groups.join.private',
                'groups.events.create',
                'groups.events.manage',
                'groups.events.view',
            ]);
        }

        // User - può visualizzare gruppi pubblici e richiedere di entrare
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userRole->givePermissionTo([
                'groups.view.public',
                'groups.join.public',
            ]);
        }

        $this->command->info('✅ Permissions per i gruppi create e ruoli aggiornati con successo!');
    }
}
