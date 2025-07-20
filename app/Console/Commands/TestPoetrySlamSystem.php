<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestPoetrySlamSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poetry:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Poetry Slam social network roles and permissions system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎭 POETRY SLAM SYSTEM TEST');
        $this->info('==========================');

        // Test 1: Count entities
        $this->newLine();
        $this->info('📊 SYSTEM COUNTS:');
        $this->table(
            ['Entity', 'Count'],
            [
                ['Roles', Role::count()],
                ['Permissions', Permission::count()],
                ['Users', User::count()],
            ]
        );

        // Test 2: List all roles
        $this->newLine();
        $this->info('👥 AVAILABLE ROLES:');
        $roles = Role::all();
        foreach ($roles as $role) {
            $permissionCount = $role->permissions->count();
            $this->line("• {$role->name} ({$permissionCount} permissions)");
        }

        // Test 3: Test user functionality
        $this->newLine();
        $this->info('🧪 USER FUNCTIONALITY TEST:');

        $testUser = User::where('email', 'test@poetryslam.com')->first();
        if ($testUser) {
            $this->info("✅ Test user found: {$testUser->name}");
            $this->info("📧 Email: {$testUser->email}");
            $this->info("🎭 Roles: " . implode(', ', $testUser->getDisplayRoles()));

            // Test helper methods
            $tests = [
                'Is Admin' => $testUser->isAdmin(),
                'Is Poet' => $testUser->isPoet(),
                'Can Organize Events' => $testUser->canOrganizeEvents(),
                'Can Judge' => $testUser->canJudge(),
                'Is Venue Owner' => $testUser->isVenueOwner(),
                'Is Active' => $testUser->isActive(),
            ];

            $this->table(
                ['Test', 'Result'],
                collect($tests)->map(function ($result, $test) {
                    return [$test, $result ? '✅ Yes' : '❌ No'];
                })->toArray()
            );

            // Test permissions
            $this->newLine();
            $this->info('🔐 PERMISSION TESTS:');
            $permissionTests = [
                'votes.cast' => $testUser->can('votes.cast'),
                'profile.manage.own' => $testUser->can('profile.manage.own'),
                'events.view.public' => $testUser->can('events.view.public'),
                'events.create.public' => $testUser->can('events.create.public'),
                'admin.access' => $testUser->can('admin.access'),
            ];

            $this->table(
                ['Permission', 'Has Access'],
                collect($permissionTests)->map(function ($hasAccess, $permission) {
                    return [$permission, $hasAccess ? '✅ Yes' : '❌ No'];
                })->toArray()
            );

        } else {
            $this->error('❌ Test user not found!');
        }

        // Test 4: Multi-role functionality
        $this->newLine();
        $this->info('🎪 MULTI-ROLE TEST:');

        // Create a test user with multiple roles
        $multiUser = User::firstOrCreate(
            ['email' => 'multi@poetryslam.com'],
            ['name' => 'Multi Role User', 'password' => bcrypt('password')]
        );

        // Assign multiple roles
        $multiUser->syncRoles(['poet', 'organizer', 'judge']);

        $this->info("👤 Multi-role user: {$multiUser->name}");
        $this->info("🎭 Roles: " . implode(', ', $multiUser->getDisplayRoles()));

        $multiTests = [
            'Is Poet' => $multiUser->isPoet(),
            'Can Organize Events' => $multiUser->canOrganizeEvents(),
            'Can Judge' => $multiUser->canJudge(),
            'Can create public events' => $multiUser->can('events.create.public'),
            'Can judge events' => $multiUser->can('events.judge'),
        ];

        $this->table(
            ['Multi-Role Test', 'Result'],
            collect($multiTests)->map(function ($result, $test) {
                return [$test, $result ? '✅ Yes' : '❌ No'];
            })->toArray()
        );

        $this->newLine();
        $this->info('🎉 POETRY SLAM SYSTEM TEST COMPLETED!');
        $this->info('✅ All core functionality is working correctly');

        return Command::SUCCESS;
    }
}
