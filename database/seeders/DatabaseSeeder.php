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
            TestUsersSeeder::class,
            SystemSettingsSeeder::class,
            PhotoSeeder::class,
            TaskSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully!');
    }
}
