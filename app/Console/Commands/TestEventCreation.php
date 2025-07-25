<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class TestEventCreation extends Command
{
    protected $signature = 'test:event-creation {--count=3 : Number of test events to create}';
    protected $description = 'Create test events with new category and private event features';

    public function handle()
    {
        $count = $this->option('count');
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found in database. Please create a user first.');
            return 1;
        }

        $categories = Event::getCategories();
        $categoryKeys = array_keys($categories);

        $this->info("Creating {$count} test events...");

        for ($i = 1; $i <= $count; $i++) {
            $isPublic = $i % 3 !== 0; // Ogni terzo evento sarà privato
            $category = $categoryKeys[array_rand($categoryKeys)];
            
            $event = Event::create([
                'title' => "Test Event {$i} - " . $categories[$category],
                'description' => "This is a test event for category: {$categories[$category]}",
                'requirements' => 'Test requirements for this event',
                'start_datetime' => Carbon::now()->addDays(rand(1, 30))->addHours(rand(1, 12)),
                'end_datetime' => Carbon::now()->addDays(rand(1, 30))->addHours(rand(13, 18)),
                'registration_deadline' => Carbon::now()->addDays(rand(1, 15)),
                'venue_name' => "Test Venue {$i}",
                'venue_address' => "Test Address {$i}",
                'city' => "Test City {$i}",
                'postcode' => "12345",
                'country' => "IT",
                'latitude' => 41.9028 + (rand(-10, 10) / 100),
                'longitude' => 12.4964 + (rand(-10, 10) / 100),
                'is_public' => $isPublic,
                'category' => $category,
                'max_participants' => rand(10, 50),
                'entry_fee' => rand(0, 20),
                'status' => Event::STATUS_PUBLISHED,
                'organizer_id' => $user->id,
                'allow_requests' => $isPublic,
                'tags' => ['test', 'demo', $category],
            ]);

            $this->info("Created event: {$event->title} ({$categories[$category]}) - " . ($isPublic ? 'Public' : 'Private'));
        }

        $this->info('Test events created successfully!');
        $this->info('You can now test the event creation form with the new features.');
        
        return 0;
    }
} 