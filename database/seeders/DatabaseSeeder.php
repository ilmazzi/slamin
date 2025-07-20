<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run Poetry Slam roles and permissions setup
        $this->call([
            PoetrySlamSeeder::class,
        ]);

        // Create test user with default 'audience' role
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@poetryslam.com',
        ]);

        // Assign default audience role
        $testUser->assignRole('audience');

        $this->command->info('✅ Test user created: test@poetryslam.com with audience role');
    }
}
