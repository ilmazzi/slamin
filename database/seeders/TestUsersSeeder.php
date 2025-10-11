<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test User 1 - Davide Mazzitelli (Gmail)
        $user1 = User::firstOrCreate(
            ['email' => 'davide.mazzitelli84@gmail.com'],
            [
                'name' => 'Davide Mazzitelli (PoetDavide84)',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign roles if not already assigned
        if (!$user1->hasRole('poet')) {
            $user1->assignRole('poet');
        }
        if (!$user1->hasRole('organizer')) {
            $user1->assignRole('organizer');
        }

        // Test User 2 - Davide Mazzitelli (Yahoo)
        $user2 = User::firstOrCreate(
            ['email' => 'davide.mazzitelli@yahoo.it'],
            [
                'name' => 'Davide Mazzitelli (SlamMaster)',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign roles if not already assigned
        if (!$user2->hasRole('organizer')) {
            $user2->assignRole('organizer');
        }
        if (!$user2->hasRole('judge')) {
            $user2->assignRole('judge');
        }

        // Test User 3 - Utente per richieste
        $user3 = User::firstOrCreate(
            ['email' => 'test@poetryslam.com'],
            [
                'name' => 'Test User (SlamTester)',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign roles if not already assigned
        if (!$user3->hasRole('poet')) {
            $user3->assignRole('poet');
        }

        $this->command->info('✅ Test users created successfully:');
        $this->command->info('   👤 davide.mazzitelli84@gmail.com (PoetDavide84) - Poet & Organizer');
        $this->command->info('   👤 davide.mazzitelli@yahoo.it (SlamMaster) - Organizer & Host');
        $this->command->info('   👤 test@poetryslam.com (SlamTester) - Poet');
        $this->command->info('   🔑 Password for all: password123');
    }
}
