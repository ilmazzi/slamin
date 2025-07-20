<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Event permissions
            'create events',
            'edit events',
            'delete events',
            'view events',
            'manage events',

            // Invitation permissions
            'send invitations',
            'accept invitations',
            'decline invitations',
            'cancel invitations',

            // Request permissions
            'send requests',
            'approve requests',
            'decline requests',
            'view requests',

            // Notification permissions
            'send notifications',
            'view notifications',
            'manage notifications',

            // Analytics permissions
            'view analytics',
            'export analytics',

            // User management
            'manage users',
            'view profiles',
            'edit profiles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign existing permissions

        // Super Admin - can do everything
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Organizer - can create and manage events
        $organizer = Role::create(['name' => 'organizer']);
        $organizer->givePermissionTo([
            'create events',
            'edit events',
            'delete events',
            'view events',
            'manage events',
            'send invitations',
            'cancel invitations',
            'approve requests',
            'decline requests',
            'view requests',
            'send notifications',
            'view notifications',
            'view analytics',
            'export analytics',
            'view profiles',
        ]);

        // Poet/Performer - can participate in events
        $poet = Role::create(['name' => 'poet']);
        $poet->givePermissionTo([
            'view events',
            'accept invitations',
            'decline invitations',
            'send requests',
            'view notifications',
            'view profiles',
            'edit profiles',
        ]);

        // Judge - can judge events and view analytics
        $judge = Role::create(['name' => 'judge']);
        $judge->givePermissionTo([
            'view events',
            'accept invitations',
            'decline invitations',
            'send requests',
            'view notifications',
            'view analytics',
            'view profiles',
            'edit profiles',
        ]);

        // Technician - technical support for events
        $technician = Role::create(['name' => 'technician']);
        $technician->givePermissionTo([
            'view events',
            'accept invitations',
            'decline invitations',
            'send requests',
            'view notifications',
            'view profiles',
            'edit profiles',
        ]);

        // Venue Owner - manages venues
        $venueOwner = Role::create(['name' => 'venue-owner']);
        $venueOwner->givePermissionTo([
            'view events',
            'accept invitations',
            'decline invitations',
            'view notifications',
            'view profiles',
            'edit profiles',
        ]);

        // Host - can host events
        $host = Role::create(['name' => 'host']);
        $host->givePermissionTo([
            'view events',
            'accept invitations',
            'decline invitations',
            'send requests',
            'view notifications',
            'view profiles',
            'edit profiles',
        ]);

        // Basic User - minimal permissions
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'view events',
            'view notifications',
            'view profiles',
            'edit profiles',
        ]);

        echo "✅ Roles and Permissions created successfully!\n";
        echo "📝 Created roles: super-admin, organizer, poet, judge, technician, venue-owner, host, user\n";
        echo "🔐 Created " . count($permissions) . " permissions\n";
    }
}
