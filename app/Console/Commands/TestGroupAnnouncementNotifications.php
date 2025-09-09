<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\GroupAnnouncement;
use App\Models\User;
use App\Notifications\GroupAnnouncementCreated;
use App\Notifications\PublicGroupAnnouncementCreated;
use Illuminate\Console\Command;

class TestGroupAnnouncementNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:group-notifications {--group-id=} {--user-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test group announcement notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $groupId = $this->option('group-id');
        $userId = $this->option('user-id');

        if (!$groupId) {
            $this->error('Please provide a group ID with --group-id');
            return;
        }

        $group = Group::find($groupId);
        if (!$group) {
            $this->error("Group with ID {$groupId} not found");
            return;
        }

        $this->info("Testing notifications for group: {$group->name}");

        // Crea un annuncio di test
        $announcement = GroupAnnouncement::create([
            'group_id' => $group->id,
            'author_id' => $userId ?: $group->created_by,
            'title' => 'Test Notification - ' . now()->format('H:i:s'),
            'content' => 'Questo è un annuncio di test per verificare le notifiche.',
            'visibility' => 'members_only',
            'has_poll' => false,
        ]);

        $this->info("Created test announcement: {$announcement->title}");

        // Testa le notifiche
        $members = $group->users;
        $this->info("Sending notifications to {$members->count()} members...");

        foreach ($members as $member) {
            if ($member->id !== $announcement->author_id) {
                $member->notify(new GroupAnnouncementCreated($announcement));
                $this->line("✓ Notified: {$member->name}");
            }
        }

        $this->info('Test completed! Check the notifications in the database.');
    }
}
