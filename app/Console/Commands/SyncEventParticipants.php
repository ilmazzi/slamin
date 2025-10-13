<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\EventParticipant;

class SyncEventParticipants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:sync-participants {event_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync participants from accepted invitations and requests for Poetry Slam events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->argument('event_id');

        if ($eventId) {
            $events = Event::where('id', $eventId)->where('category', 'poetry_slam')->get();
            if ($events->isEmpty()) {
                $this->error("Event #{$eventId} not found or not a Poetry Slam event.");
                return 1;
            }
        } else {
            $events = Event::where('category', 'poetry_slam')->get();
        }

        if ($events->isEmpty()) {
            $this->info('No Poetry Slam events found.');
            return 0;
        }

        $this->info("Processing {$events->count()} Poetry Slam event(s)...");

        $totalImported = 0;
        $totalSkipped = 0;

        foreach ($events as $event) {
            $this->line("\n📅 Event: {$event->title}");
            
            $imported = 0;
            $skipped = 0;

            // Get users with accepted invitations
            $acceptedInvitations = $event->invitations()
                ->where('status', 'accepted')
                ->with('invitedUser')
                ->get();

            foreach ($acceptedInvitations as $invitation) {
                if ($invitation->invitedUser) {
                    $exists = EventParticipant::where('event_id', $event->id)
                        ->where('user_id', $invitation->invitedUser->id)
                        ->exists();

                    if (!$exists) {
                        EventParticipant::create([
                            'event_id' => $event->id,
                            'user_id' => $invitation->invitedUser->id,
                            'registration_type' => 'invitation',
                            'status' => 'confirmed',
                        ]);
                        $imported++;
                        $this->line("  ✓ Added: {$invitation->invitedUser->getDisplayName()} (invitation)");
                    } else {
                        $skipped++;
                    }
                }
            }

            // Get users with accepted requests
            $acceptedRequests = $event->requests()
                ->where('status', 'accepted')
                ->with('user')
                ->get();

            foreach ($acceptedRequests as $request) {
                if ($request->user) {
                    $exists = EventParticipant::where('event_id', $event->id)
                        ->where('user_id', $request->user->id)
                        ->exists();

                    if (!$exists) {
                        EventParticipant::create([
                            'event_id' => $event->id,
                            'user_id' => $request->user->id,
                            'registration_type' => 'request',
                            'status' => 'confirmed',
                        ]);
                        $imported++;
                        $this->line("  ✓ Added: {$request->user->getDisplayName()} (request)");
                    } else {
                        $skipped++;
                    }
                }
            }

            if ($imported > 0) {
                $this->info("  ✅ Imported: {$imported} participant(s)");
            }
            if ($skipped > 0) {
                $this->comment("  ⏭️  Skipped: {$skipped} (already present)");
            }
            if ($imported === 0 && $skipped === 0) {
                $this->comment("  ℹ️  No accepted invitations or requests");
            }

            $totalImported += $imported;
            $totalSkipped += $skipped;
        }

        $this->newLine();
        $this->info("🎉 Sync completed!");
        $this->info("Total imported: {$totalImported}");
        if ($totalSkipped > 0) {
            $this->info("Total skipped: {$totalSkipped}");
        }

        return 0;
    }
}
