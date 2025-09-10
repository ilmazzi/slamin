<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Video;
use App\Models\Event;
use App\Models\Poem;
use App\Models\Photo;
use App\Services\ActivityService;

class CreateTestActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activities:create-test {--count=10 : Number of test activities to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test activities for the dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');
        $this->info("Creating {$count} test activities...");

        $users = User::take(3)->get();
        if ($users->isEmpty()) {
            $this->error('No users found. Please create some users first.');
            return 1;
        }

                $videos = Video::take(5)->get();
                $events = Event::take(5)->get();
                $poems = Poem::take(5)->get();
                $photos = Photo::take(5)->get();

        $activities = [
            'view' => ['video', 'event', 'poem', 'photo'],
            'like' => ['video', 'event', 'poem', 'photo'],
            'comment' => ['video', 'event', 'poem', 'photo'],
            'create' => ['video', 'event', 'poem', 'photo'],
            'upload' => ['video', 'photo'],
        ];

        $created = 0;
        for ($i = 0; $i < $count; $i++) {
            $user = $users->random();
            $activityType = array_rand($activities);
            $subjectType = $activities[$activityType][array_rand($activities[$activityType])];

            $subject = null;
            switch ($subjectType) {
                case 'video':
                    $subject = $videos->isNotEmpty() ? $videos->random() : null;
                    break;
                case 'event':
                    $subject = $events->isNotEmpty() ? $events->random() : null;
                    break;
                case 'poem':
                    $subject = $poems->isNotEmpty() ? $poems->random() : null;
                    break;
                case 'photo':
                    $subject = $photos->isNotEmpty() ? $photos->random() : null;
                    break;
            }

            if (!$subject) {
                continue;
            }

            try {
                switch ($activityType) {
                    case 'view':
                        ActivityService::logView($user, $subject);
                        break;
                    case 'like':
                        ActivityService::logLike($user, $subject);
                        break;
                    case 'comment':
                        ActivityService::logComment($user, $subject);
                        break;
                    case 'create':
                        ActivityService::logCreate($user, $subject);
                        break;
                    case 'upload':
                        ActivityService::logUpload($user, $subject);
                        break;
                }
                $created++;
            } catch (\Exception $e) {
                $this->warn("Failed to create activity: " . $e->getMessage());
            }
        }

        $this->info("Successfully created {$created} test activities!");
        return 0;
    }
}
