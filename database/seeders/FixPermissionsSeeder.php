<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FixPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Rimuovi permissions ridondanti/duplicate
        $permissionsToDelete = [
            'create events',
            'manage events',
            'edit events',
            'delete events',
            'view events',
            'edit profiles',
            'view profiles',
            'manage users',
            'view notifications',
            'manage notifications',
            'send notifications',
            'view requests',
            'send requests',
            'approve requests',
            'decline requests',
            'view system',
            'export analytics',
            'view analytics',
            'test.delete'
        ];

        foreach ($permissionsToDelete as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                echo "Rimuovendo permission duplicata: {$permissionName}\n";
                $permission->delete();
            }
        }

        // Aggiungi permissions mancanti per poems e articles
        $newPermissions = [
            'poems.create' => ['display_name' => 'Crea Poesie', 'group' => 'content'],
            'poems.edit.own' => ['display_name' => 'Modifica Poesie Proprie', 'group' => 'content'],
            'poems.delete.own' => ['display_name' => 'Elimina Poesie Proprie', 'group' => 'content'],
            'poems.moderate' => ['display_name' => 'Modera Poesie', 'group' => 'moderation'],
            'articles.view' => ['display_name' => 'Visualizza Articoli', 'group' => 'content'],
            'articles.create' => ['display_name' => 'Crea Articoli', 'group' => 'content'],
            'articles.edit' => ['display_name' => 'Modifica Articoli', 'group' => 'content'],
            'articles.edit.own' => ['display_name' => 'Modifica Articoli Propri', 'group' => 'content'],
            'articles.delete' => ['display_name' => 'Elimina Articoli', 'group' => 'content'],
            'articles.delete.own' => ['display_name' => 'Elimina Articoli Propri', 'group' => 'content'],
            'articles.publish' => ['display_name' => 'Pubblica Articoli', 'group' => 'content'],
            'articles.unpublish' => ['display_name' => 'Rimetti in Bozza Articoli', 'group' => 'content'],
            'articles.feature' => ['display_name' => 'Gestisce Featured Articoli', 'group' => 'content'],
            'articles.toggle_featured' => ['display_name' => 'Cambia Featured Articoli', 'group' => 'content'],
            'articles.manage_layout' => ['display_name' => 'Gestisce Layout Articoli', 'group' => 'content'],
            'articles.moderate' => ['display_name' => 'Modera Articoli', 'group' => 'moderation'],
            'articles.manage_categories' => ['display_name' => 'Gestisce Categorie Articoli', 'group' => 'content'],
            'articles.manage_tags' => ['display_name' => 'Gestisce Tag Articoli', 'group' => 'content'],
            'articles.view_reports' => ['display_name' => 'Visualizza Segnalazioni Articoli', 'group' => 'moderation'],
            'videos.upload' => ['display_name' => 'Carica Video', 'group' => 'content'],
            'videos.edit.own' => ['display_name' => 'Modifica Video Propri', 'group' => 'content'],
            'videos.delete.own' => ['display_name' => 'Elimina Video Propri', 'group' => 'content'],
            'videos.moderate' => ['display_name' => 'Modera Video', 'group' => 'content'],
        ];

        foreach ($newPermissions as $permissionName => $data) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName],
                [
                    'display_name' => $data['display_name'],
                    'group' => $data['group'],
                    'description' => 'Permesso per ' . strtolower($data['display_name'])
                ]
            );
            echo "Aggiunta/aggiornata permission: {$permissionName}\n";
        }

        // Aggiorna i gruppi delle permissions esistenti
        $groupUpdates = [
            'admin.access' => 'system',
            'users.manage' => 'users',
            'system.settings' => 'system',
            'profile.manage.own' => 'profile',
            'profile.suspend' => 'users',
            'content.publish.own' => 'content',
            'content.edit.own' => 'content',
            'content.delete.own' => 'content',
            'content.moderate' => 'moderation',
            'content.delete.any' => 'moderation',
            'events.create.public' => 'events',
            'events.create.private' => 'events',
            'events.manage.own' => 'events',
            'events.manage.any' => 'events',
            'events.view.public' => 'events',
            'events.view.private' => 'events',
            'events.invite' => 'events',
            'events.participate' => 'events',
            'events.judge' => 'events',
            'votes.cast' => 'interactions',
            'comments.create' => 'interactions',
            'comments.moderate' => 'moderation',
            'follows.manage' => 'interactions',
            'gigs.create' => 'gigs',
            'gigs.manage.own' => 'gigs',
            'gigs.apply' => 'gigs',
            'gigs.invite' => 'gigs',
            'venues.create' => 'venues',
            'venues.manage.own' => 'venues',
            'venues.book' => 'venues',
            'venues.approve.bookings' => 'venues',
            'stats.view.own' => 'analytics',
            'stats.view.public' => 'analytics',
            'stats.view.all' => 'analytics',
            'accept invitations' => 'invitations',
            'decline invitations' => 'invitations',
            'cancel invitations' => 'invitations',
            'send invitations' => 'invitations',
        ];

        foreach ($groupUpdates as $permissionName => $group) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission && $permission->group !== $group) {
                $permission->update(['group' => $group]);
                echo "Aggiornato gruppo per {$permissionName}: {$group}\n";
            }
        }

        // Sistema i ruoli duplicati
        $venueOwnerRole = Role::where('name', 'venue-owner')->first();
        $venueOwnerRole2 = Role::where('name', 'venue_owner')->first();

        if ($venueOwnerRole && $venueOwnerRole2) {
            // Sposta le permissions dal secondo al primo
            $permissions = $venueOwnerRole2->permissions;
            $venueOwnerRole->syncPermissions($permissions);

            // Elimina il secondo ruolo
            $venueOwnerRole2->delete();
            echo "Unificati i ruoli venue-owner e venue_owner\n";
        }

        // Aggiorna le permissions dei ruoli principali
        $this->updateRolePermissions();
    }

    private function updateRolePermissions(): void
    {
        // Admin - tutte le permissions
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all()->pluck('name')->toArray();
            $adminRole->syncPermissions($allPermissions);
            echo "Aggiornate permissions per admin\n";
        }

        // Moderator - aggiungi permissions di moderazione
        $moderatorRole = Role::where('name', 'moderator')->first();
        if ($moderatorRole) {
            $moderatorPermissions = [
                'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                'content.moderate', 'content.delete.any', 'profile.suspend',
                'articles.view', 'articles.create', 'articles.edit', 'articles.delete', 'articles.publish', 'articles.unpublish', 'articles.feature', 'articles.toggle_featured', 'articles.manage_layout', 'articles.manage_categories', 'articles.manage_tags', 'articles.view_reports',
                'events.view.public', 'events.view.private', 'votes.cast', 'comments.create', 'comments.moderate',
                'follows.manage', 'poems.moderate', 'articles.moderate', 'videos.moderate',
                'stats.view.own', 'stats.view.public'
            ];
            $moderatorRole->syncPermissions($moderatorPermissions);
            echo "Aggiornate permissions per moderator\n";
        }

        // Poet - aggiungi permissions per poesie e video
        $poetRole = Role::where('name', 'poet')->first();
        if ($poetRole) {
            $poetPermissions = [
                'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                'poems.create', 'poems.edit.own', 'poems.delete.own',
                'videos.upload', 'videos.edit.own', 'videos.delete.own',
                'articles.view', 'articles.create', 'articles.edit.own', 'articles.delete.own', 'articles.publish', 'articles.unpublish', 'articles.feature',
                'events.view.public', 'events.participate', 'votes.cast', 'comments.create',
                'follows.manage', 'gigs.apply', 'stats.view.own'
            ];
            $poetRole->syncPermissions($poetPermissions);
            echo "Aggiornate permissions per poet\n";
        }

        // Organizer - aggiungi permissions per articoli
        $organizerRole = Role::where('name', 'organizer')->first();
        if ($organizerRole) {
            $organizerPermissions = [
                'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                'articles.view', 'articles.create', 'articles.edit', 'articles.edit.own', 'articles.delete', 'articles.delete.own', 'articles.publish', 'articles.unpublish', 'articles.feature', 'articles.toggle_featured', 'articles.manage_layout', 'articles.manage_categories', 'articles.manage_tags',
                'events.create.public', 'events.create.private', 'events.manage.own', 'events.view.public',
                'events.view.private', 'events.invite', 'events.participate',
                'votes.cast', 'comments.create', 'follows.manage',
                'gigs.create', 'gigs.manage.own', 'gigs.invite', 'venues.book',
                'stats.view.own', 'stats.view.public'
            ];
            $organizerRole->syncPermissions($organizerPermissions);
            echo "Aggiornate permissions per organizer\n";
        }

        // Venue Owner - aggiorna permissions
        $venueOwnerRole = Role::where('name', 'venue-owner')->first();
        if ($venueOwnerRole) {
            $venueOwnerPermissions = [
                'profile.manage.own', 'content.publish.own', 'content.edit.own', 'content.delete.own',
                'articles.view', 'articles.create', 'articles.edit', 'articles.edit.own', 'articles.delete', 'articles.delete.own', 'articles.publish', 'articles.unpublish', 'articles.feature', 'articles.toggle_featured',
                'events.view.public', 'votes.cast', 'comments.create', 'follows.manage',
                'venues.create', 'venues.manage.own', 'venues.approve.bookings',
                'stats.view.own'
            ];
            $venueOwnerRole->syncPermissions($venueOwnerPermissions);
            echo "Aggiornate permissions per venue-owner\n";
        }
    }
}
