<?php

namespace App\Console\Commands;

use App\Models\Video;
use App\Models\Event;
use App\Models\User;
use App\Models\VideoSnap;
use Illuminate\Console\Command;

class TestCarouselContentSearch extends Command
{
    protected $signature = 'carousels:test-search {type} {query?}';
    protected $description = 'Testa la ricerca di contenuti per il carousel';

    public function handle()
    {
        $type = $this->argument('type');
        $query = $this->argument('query') ?? '';

        $this->info("=== TEST RICERCA CONTENUTI ===");
        $this->info("Tipo: {$type}");
        $this->info("Query: '{$query}'");

        switch ($type) {
            case 'video':
                $this->testVideoSearch($query);
                break;
            case 'event':
                $this->testEventSearch($query);
                break;
            case 'user':
                $this->testUserSearch($query);
                break;
            case 'snap':
                $this->testSnapSearch($query);
                break;
            default:
                $this->error("Tipo non valido. Usa: video, event, user, snap");
        }
    }

    private function testVideoSearch($query)
    {
        $videos = Video::where('moderation_status', 'approved')
            ->where('is_public', true)
            ->when($query, function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('user')
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        $this->info("\nRisultati Video ({$videos->count()}):");
        foreach ($videos as $video) {
            $this->line("- ID: {$video->id}");
            $this->line("  Titolo: {$video->title}");
            $this->line("  Utente: {$video->user->getDisplayName()}");
            $this->line("  Visualizzazioni: {$video->view_count}");
            $this->line("  Thumbnail: {$video->thumbnail_url}");
            $this->line("");
        }
    }

    private function testEventSearch($query)
    {
        $events = Event::where('status', 'published')
            ->when($query, function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('organizer')
            ->orderBy('start_datetime', 'desc')
            ->limit(5)
            ->get();

        $this->info("\nRisultati Eventi ({$events->count()}):");
        foreach ($events as $event) {
            $this->line("- ID: {$event->id}");
            $this->line("  Titolo: {$event->title}");
            $this->line("  Organizzatore: {$event->organizer->getDisplayName()}");
            $this->line("  Data: {$event->start_datetime->format('d/m/Y H:i')}");
            $this->line("  Città: {$event->city}");
            $this->line("");
        }
    }

    private function testUserSearch($query)
    {
        $users = User::when($query, function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('nickname', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->withCount(['videos' => function($q) {
                $q->where('moderation_status', 'approved');
            }])
            ->orderBy('videos_count', 'desc')
            ->limit(5)
            ->get();

        $this->info("\nRisultati Utenti ({$users->count()}):");
        foreach ($users as $user) {
            $this->line("- ID: {$user->id}");
            $this->line("  Nome: {$user->getDisplayName()}");
            $this->line("  Email: {$user->email}");
            $this->line("  Video: {$user->videos_count}");
            $this->line("  Località: {$user->location}");
            $this->line("  Foto: {$user->profile_photo_url}");
            $this->line("");
        }
    }

    private function testSnapSearch($query)
    {
        $snaps = VideoSnap::where('status', 'approved')
            ->when($query, function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['video', 'user'])
            ->orderBy('like_count', 'desc')
            ->limit(5)
            ->get();

        $this->info("\nRisultati Snap ({$snaps->count()}):");
        foreach ($snaps as $snap) {
            $this->line("- ID: {$snap->id}");
            $this->line("  Titolo: " . ($snap->title ?: "Snap di {$snap->video->title}"));
            $this->line("  Video: {$snap->video->title}");
            $this->line("  Utente: {$snap->user->getDisplayName()}");
            $this->line("  Like: {$snap->like_count}");
            $this->line("  Timestamp: " . gmdate('i:s', $snap->timestamp));
            $this->line("");
        }
    }
}
