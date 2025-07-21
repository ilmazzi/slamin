<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PoetrySlamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions for Poetry Slam Social Network
        $permissions = [
            // Profile & Content Management
            'profile.manage.own',
            'content.publish.own',
            'content.edit.own',
            'content.delete.own',
            'content.moderate',
            'content.delete.any',
            'profile.suspend',

            // Events Management
            'events.create.public',
            'events.create.private',
            'events.manage.own',
            'events.manage.any',
            'events.view.public',
            'events.view.private',
            'events.invite',
            'events.participate',
            'events.judge',

            // Voting & Social Interactions
            'votes.cast',
            'comments.create',
            'comments.moderate',
            'follows.manage',

            // Gigs Management
            'gigs.create',
            'gigs.manage.own',
            'gigs.apply',
            'gigs.invite',

            // Venues Management
            'venues.create',
            'venues.manage.own',
            'venues.book',
            'venues.approve.bookings',

            // Statistics & Analytics
            'stats.view.own',
            'stats.view.public',
            'stats.view.all',

            // System Administration
            'admin.access',
            'users.manage',
            'system.settings',
        ];

        // Define permission groups and display names
        $permissionGroups = [
            'profile.manage.own' => ['display_name' => 'Gestione Profilo Proprio', 'group' => 'profile'],
            'content.publish.own' => ['display_name' => 'Pubblica Contenuti Propri', 'group' => 'content'],
            'content.edit.own' => ['display_name' => 'Modifica Contenuti Propri', 'group' => 'content'],
            'content.delete.own' => ['display_name' => 'Elimina Contenuti Propri', 'group' => 'content'],
            'content.moderate' => ['display_name' => 'Modera Contenuti', 'group' => 'content'],
            'content.delete.any' => ['display_name' => 'Elimina Qualsiasi Contenuto', 'group' => 'content'],
            'profile.suspend' => ['display_name' => 'Sospendi Profili', 'group' => 'users'],
            'events.create.public' => ['display_name' => 'Crea Eventi Pubblici', 'group' => 'events'],
            'events.create.private' => ['display_name' => 'Crea Eventi Privati', 'group' => 'events'],
            'events.manage.own' => ['display_name' => 'Gestisce Eventi Propri', 'group' => 'events'],
            'events.manage.any' => ['display_name' => 'Gestisce Qualsiasi Evento', 'group' => 'events'],
            'events.view.public' => ['display_name' => 'Visualizza Eventi Pubblici', 'group' => 'events'],
            'events.view.private' => ['display_name' => 'Visualizza Eventi Privati', 'group' => 'events'],
            'events.invite' => ['display_name' => 'Invia Inviti Eventi', 'group' => 'events'],
            'events.participate' => ['display_name' => 'Partecipa agli Eventi', 'group' => 'events'],
            'events.judge' => ['display_name' => 'Giudica Eventi', 'group' => 'events'],
            'votes.cast' => ['display_name' => 'Vota', 'group' => 'interactions'],
            'comments.create' => ['display_name' => 'Crea Commenti', 'group' => 'interactions'],
            'comments.moderate' => ['display_name' => 'Modera Commenti', 'group' => 'interactions'],
            'follows.manage' => ['display_name' => 'Gestisce Seguiti', 'group' => 'interactions'],
            'gigs.create' => ['display_name' => 'Crea Performance', 'group' => 'gigs'],
            'gigs.manage.own' => ['display_name' => 'Gestisce Performance Proprie', 'group' => 'gigs'],
            'gigs.apply' => ['display_name' => 'Candidatura Performance', 'group' => 'gigs'],
            'gigs.invite' => ['display_name' => 'Invita a Performance', 'group' => 'gigs'],
            'venues.create' => ['display_name' => 'Crea Locali', 'group' => 'venues'],
            'venues.manage.own' => ['display_name' => 'Gestisce Locali Propri', 'group' => 'venues'],
            'venues.book' => ['display_name' => 'Prenota Locali', 'group' => 'venues'],
            'venues.approve.bookings' => ['display_name' => 'Approva Prenotazioni', 'group' => 'venues'],
            'stats.view.own' => ['display_name' => 'Visualizza Statistiche Proprie', 'group' => 'analytics'],
            'stats.view.public' => ['display_name' => 'Visualizza Statistiche Pubbliche', 'group' => 'analytics'],
            'stats.view.all' => ['display_name' => 'Visualizza Tutte le Statistiche', 'group' => 'analytics'],
            'admin.access' => ['display_name' => 'Accesso Amministrativo', 'group' => 'system'],
            'users.manage' => ['display_name' => 'Gestisce Utenti', 'group' => 'users'],
            'system.settings' => ['display_name' => 'Impostazioni Sistema', 'group' => 'system'],
        ];

        // Create permissions with display names and groups
        foreach ($permissions as $permission) {
            $groupData = $permissionGroups[$permission] ?? ['display_name' => $permission, 'group' => null];
            Permission::firstOrCreate(
                ['name' => $permission],
                [
                    'display_name' => $groupData['display_name'],
                    'group' => $groupData['group'],
                    'description' => 'Permesso per ' . strtolower($groupData['display_name'])
                ]
            );
        }

        // Define roles and their permissions
        $roles = [
            'admin' => [
                'display_name' => 'Administrator',
                'description' => 'Full system access - Poetry Slam platform administrator',
                'permissions' => $permissions // All permissions
            ],

            'moderator' => [
                'display_name' => 'Moderator',
                'description' => 'Community moderator with content management powers',
                'permissions' => [
                    'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                    'content.moderate', 'content.delete.any', 'profile.suspend',
                    'events.view.public', 'events.view.private', 'votes.cast', 'comments.create', 'comments.moderate',
                    'follows.manage', 'stats.view.own', 'stats.view.public'
                ]
            ],

            'poet' => [
                'display_name' => 'Poet',
                'description' => 'Poetry performer - can participate in events and publish content',
                'permissions' => [
                    'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                    'events.view.public', 'events.participate', 'votes.cast', 'comments.create',
                    'follows.manage', 'gigs.apply', 'stats.view.own'
                ]
            ],

            'organizer' => [
                'display_name' => 'Event Organizer',
                'description' => 'Can create and manage poetry slam events',
                'permissions' => [
                    'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                    'events.create.public', 'events.create.private', 'events.manage.own', 'events.view.public',
                    'events.view.private', 'events.invite', 'events.participate',
                    'votes.cast', 'comments.create', 'follows.manage',
                    'gigs.create', 'gigs.manage.own', 'gigs.invite', 'venues.book',
                    'stats.view.own', 'stats.view.public'
                ]
            ],

            'judge' => [
                'display_name' => 'Competition Judge',
                'description' => 'Authorized to judge poetry slam competitions',
                'permissions' => [
                    'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                    'events.view.public', 'events.view.private', 'events.judge',
                    'votes.cast', 'comments.create', 'follows.manage', 'stats.view.own'
                ]
            ],

            'venue_owner' => [
                'display_name' => 'Venue Owner',
                'description' => 'Owns or manages venues for poetry slam events',
                'permissions' => [
                    'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                    'events.view.public', 'votes.cast', 'comments.create', 'follows.manage',
                    'venues.create', 'venues.manage.own', 'venues.approve.bookings',
                    'stats.view.own'
                ]
            ],

            'technician' => [
                'display_name' => 'Technical Professional',
                'description' => 'Sound, lighting, and technical professionals for events',
                'permissions' => [
                    'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                    'events.view.public', 'votes.cast', 'comments.create', 'follows.manage',
                    'gigs.apply', 'stats.view.own'
                ]
            ],

            'audience' => [
                'display_name' => 'Audience Member',
                'description' => 'Default role for registered users - can vote and engage',
                'permissions' => [
                    'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                    'events.view.public', 'votes.cast', 'comments.create', 'follows.manage',
                    'stats.view.own'
                ]
            ]
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description']
                ]
            );

            // Sync permissions for this role
            $role->syncPermissions($roleData['permissions']);
        }

        $this->command->info('✅ Poetry Slam roles and permissions created successfully!');

        // Display summary table
        $this->command->table(
            ['Role', 'Display Name', 'Permissions Count'],
            collect($roles)->map(function ($data, $name) {
                return [
                    $name,
                    $data['display_name'],
                    count($data['permissions'])
                ];
            })->toArray()
        );
    }
}
