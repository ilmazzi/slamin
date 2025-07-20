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

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
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
            $role = Role::firstOrCreate(['name' => $roleName]);

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
