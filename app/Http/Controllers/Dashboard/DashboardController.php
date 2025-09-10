<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use App\Services\ActivityService;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware auth è gestito nelle route
    }

    /**
     * Display the user dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user statistics
        $stats = $this->getUserStats($user);

        // Get recent activity
        $recentActivity = $this->getRecentActivity($user);

        // Get upcoming events
        $upcomingEvents = $this->getUpcomingEvents($user);

        // Get quick actions based on user roles
        $quickActions = $this->getQuickActions($user);

        // Get role-specific content
        $roleContent = $this->getRoleSpecificContent($user);

        return view('dashboard.index', compact(
            'user',
            'stats',
            'recentActivity',
            'upcomingEvents',
            'quickActions',
            'roleContent'
        ));
    }

    /**
     * Get user statistics for dashboard
     */
    private function getUserStats($user)
    {
        $stats = [
            'past_events' => 0,
            'future_events' => 0,
            'organized_events' => 0,
            'pending_invitations' => 0,
            'pending_group_invitations' => 0,
            'total_events' => 0,
            'pending_requests' => 0,
            'unread_notifications' => 0,
            'total_poems' => 0,
            'published_poems' => 0,
            'draft_poems' => 0,
        ];

        // Past events (organized + participated)
        $pastOrganized = $user->organizedEvents()->where('start_datetime', '<', now())->count();
        $pastParticipated = $user->receivedInvitations()
                                 ->where('status', EventInvitation::STATUS_ACCEPTED)
                                 ->whereHas('event', function($q) {
                                     $q->where('start_datetime', '<', now());
                                 })->count();
        $pastRequests = $user->eventRequests()
                             ->where('status', EventRequest::STATUS_ACCEPTED)
                             ->whereHas('event', function($q) {
                                 $q->where('start_datetime', '<', now());
                             })->count();
        $stats['past_events'] = $pastOrganized + $pastParticipated + $pastRequests;

        // Future events (organized + participated)
        $futureOrganized = $user->organizedEvents()->where('start_datetime', '>=', now())->count();
        $futureParticipated = $user->receivedInvitations()
                                   ->where('status', EventInvitation::STATUS_ACCEPTED)
                                   ->whereHas('event', function($q) {
                                       $q->where('start_datetime', '>=', now());
                                   })->count();
        $futureRequests = $user->eventRequests()
                               ->where('status', EventRequest::STATUS_ACCEPTED)
                               ->whereHas('event', function($q) {
                                   $q->where('start_datetime', '>=', now());
                               })->count();
        $stats['future_events'] = $futureOrganized + $futureParticipated + $futureRequests;

        // Events organized (all time)
        $stats['organized_events'] = $user->organizedEvents()->count();

        // Pending invitations (received + sent)
        $pendingReceived = $user->receivedInvitations()
                                ->where('status', EventInvitation::STATUS_PENDING)
                                ->count();
        $pendingSent = $user->sentInvitations()
                            ->where('status', EventInvitation::STATUS_PENDING)
                            ->count();
        $stats['pending_invitations'] = $pendingReceived + $pendingSent;

        // Pending group invitations (received + sent)
        $pendingGroupReceived = $user->groupInvitations()
                                    ->where('status', 'pending')
                                    ->count();
        $pendingGroupSent = $user->sentGroupInvitations()
                                 ->where('status', 'pending')
                                 ->count();
        $stats['pending_group_invitations'] = $pendingGroupReceived + $pendingGroupSent;

        $stats['total_events'] = $stats['past_events'] + $stats['future_events'];

        // Pending requests to own events (if organizer)
        if ($user->hasRole('organizer')) {
            $stats['pending_requests'] = EventRequest::whereHas('event', function ($query) use ($user) {
                $query->where('organizer_id', $user->id);
            })->where('status', EventRequest::STATUS_PENDING)->count();
        }

        // Unread notifications
        $stats['unread_notifications'] = $user->notifications()->where('is_read', false)->count();

        // Poems statistics
        $stats['total_poems'] = $user->poems()->count();
        $stats['published_poems'] = $user->poems()->where('is_public', true)->where('is_draft', false)->count();
        $stats['draft_poems'] = $user->poems()->where('is_draft', true)->count();

        return $stats;
    }

    /**
     * Get recent activity for user
     */
    private function getRecentActivity($user)
    {
        $activities = ActivityService::getRecentActivities($user, 10);

        return $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'message' => $activity->formatted_description,
                'time' => $activity->created_at->diffForHumans(),
                'icon' => $activity->icon,
                'color' => $activity->color_class,
                'url' => $activity->metadata['url'] ?? null,
                'type' => $activity->type,
                'action' => $activity->action,
                'content_type' => $activity->content_type_badge,
                'content_type_color' => $activity->content_type_color,
                'thumbnail' => $activity->thumbnail_url,
                'has_thumbnail' => $activity->has_thumbnail,
                'title' => $activity->metadata['title'] ?? 'Contenuto',
            ];
        })->toArray();
    }

    /**
     * Get upcoming events for user
     */
    private function getUpcomingEvents($user)
    {
        // Get user's upcoming events (organized + participating)
        $events = [];

        // Events organized by user
        $organizedEvents = $user->organizedEvents()
                               ->upcoming()
                               ->published()
                               ->orderBy('start_datetime')
                               ->limit(5)
                               ->get();

        foreach ($organizedEvents as $event) {
            $events[] = [
                'title' => $event->title,
                'date' => $event->start_datetime ? $event->start_datetime->format('d M Y, H:i') : 'Data non disponibile',
                'venue' => $event->venue_name,
                'type' => 'organized',
                'url' => route('events.show', $event),
                'city' => $event->city,
            ];
        }

        // Events where user is participating (accepted invitations/requests)
        $participatingEvents = $user->participatingEvents()
                                   ->upcoming()
                                   ->published()
                                   ->orderBy('start_datetime')
                                   ->limit(5)
                                   ->get();

        foreach ($participatingEvents as $event) {
            $events[] = [
                'title' => $event->title,
                'date' => $event->start_datetime ? $event->start_datetime->format('d M Y, H:i') : 'Data non disponibile',
                'venue' => $event->venue_name,
                'type' => 'participating',
                'url' => route('events.show', $event),
                'city' => $event->city,
            ];
        }

        // Events in user's wishlist
        $wishlistedEvents = $user->wishlistedEvents()
                                ->upcoming()
                                ->published()
                                ->orderBy('start_datetime')
                                ->limit(5)
                                ->get();

        foreach ($wishlistedEvents as $event) {
            $events[] = [
                'title' => $event->title,
                'date' => $event->start_datetime ? $event->start_datetime->format('d M Y, H:i') : 'Data non disponibile',
                'venue' => $event->venue_name,
                'type' => 'wishlisted',
                'url' => route('events.show', $event),
                'city' => $event->city,
            ];
        }

        // Sort by date and limit to 5
        usort($events, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        return array_slice($events, 0, 5);
    }

    /**
     * Get quick actions based on user roles
     */
    private function getQuickActions($user)
    {
        $actions = [];

        // Ordine richiesto: Scrivi poesia, Crea evento, Carica video, Scrivi articolo

        // 1. Scrivi Poesia - per poeti e admin
        if ($user->can('poems.create')) {
            $actions[] = [
                'key' => 'write_poem',
                'icon' => 'ph ph-pen-nib',
                'color' => 'info',
                'url' => route('poems.create')
            ];
        }

        // 2. Crea Evento - per organizer e admin
        if ($user->can('events.create.public') || $user->can('events.create.private')) {
            $actions[] = [
                'key' => 'organize_event',
                'icon' => 'ph ph-calendar-plus',
                'color' => 'success',
                'url' => route('events.create')
            ];
        }

        // 3. Carica Video - per poeti e admin
        if ($user->can('videos.upload')) {
            $actions[] = [
                'key' => 'upload_performance',
                'icon' => 'ph ph-upload',
                'color' => 'warning',
                'url' => route('videos.upload')
            ];
        }

        // 4. Scrivi Articolo - per organizer, venue_owner e admin
        if ($user->can('articles.create')) {
            $actions[] = [
                'key' => 'write_article',
                'icon' => 'ph ph-newspaper',
                'color' => 'primary',
                'url' => '#' // TODO: Creare route per articles.create
            ];
        }

        return $actions;
    }

    /**
     * Get role-specific dashboard content with real data
     */
    private function getRoleSpecificContent($user)
    {
        $content = [];

        if ($user->hasRole('poet')) {
            $content['poet'] = [
                'upcoming_events' => $user->participatingEvents()
                                         ->upcoming()
                                         ->published()
                                         ->limit(3)
                                         ->get(),
                'recent_invitations' => $user->receivedInvitations()
                                            ->with('event')
                                            ->where('status', EventInvitation::STATUS_PENDING)
                                            ->limit(3)
                                            ->get(),
                'performance_stats' => [
                    'total_events' => $user->eventRequests()
                                          ->where('status', EventRequest::STATUS_ACCEPTED)
                                          ->count(),
                    'pending_applications' => $user->eventRequests()
                                                  ->where('status', EventRequest::STATUS_PENDING)
                                                  ->count(),
                ],
            ];
        }

        if ($user->hasRole('organizer')) {
            $content['organizer'] = [
                'my_events' => $user->organizedEvents()
                                   ->upcoming()
                                   ->orderBy('start_datetime')
                                   ->limit(5)
                                   ->get(),
                'pending_requests' => EventRequest::whereHas('event', function ($query) use ($user) {
                    $query->where('organizer_id', $user->id);
                })->where('status', EventRequest::STATUS_PENDING)
                  ->with(['user', 'event'])
                  ->limit(5)
                  ->get(),
                'organizer_stats' => [
                    'total_events' => $user->organizedEvents()->count(),
                    'published_events' => $user->organizedEvents()
                                              ->where('status', Event::STATUS_PUBLISHED)
                                              ->count(),
                    'total_participants' => EventInvitation::whereHas('event', function ($query) use ($user) {
                        $query->where('organizer_id', $user->id);
                    })->where('status', EventInvitation::STATUS_ACCEPTED)->count() +
                    EventRequest::whereHas('event', function ($query) use ($user) {
                        $query->where('organizer_id', $user->id);
                    })->where('status', EventRequest::STATUS_ACCEPTED)->count(),
                ],
            ];
        }

        if ($user->hasRole('venue_owner')) {
            $content['venue_owner'] = [
                'hosted_events' => $user->venueEvents()
                                       ->upcoming()
                                       ->published()
                                       ->orderBy('start_datetime')
                                       ->limit(5)
                                       ->get(),
                'venue_stats' => [
                    'total_hosted' => $user->venueEvents()->count(),
                    'upcoming_events' => $user->venueEvents()
                                             ->upcoming()
                                             ->published()
                                             ->count(),
                ],
            ];
        }

        if ($user->hasRole('audience')) {
            $content['audience'] = [
                'discovered_events' => Event::public()
                                           ->upcoming()
                                           ->published()
                                           ->orderBy('start_datetime')
                                           ->limit(5)
                                           ->get(),
                'audience_stats' => [
                    'events_attended' => $user->eventRequests()
                                             ->where('status', EventRequest::STATUS_ACCEPTED)
                                             ->count(),
                    'invitations_received' => $user->receivedInvitations()->count(),
                ],
            ];
        }

        return $content;
    }

    /**
     * Switch language
     */
    public function switchLanguage(Request $request)
    {
        $locale = $request->input('locale');

        if (in_array($locale, ['it', 'en', 'fr', 'es', 'de'])) {
            session(['locale' => $locale]);

            // Update user preference if logged in
            if (Auth::check()) {
                $user = Auth::user();
                // TODO: Add preferred_language field to users table
                // $user->update(['preferred_language' => $locale]);
            }
        }

        return redirect()->back();
    }
}
