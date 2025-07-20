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
            'total_events' => 0,
            'organized_events' => 0,
            'participated_events' => 0,
            'pending_invitations' => 0,
            'pending_requests' => 0,
            'unread_notifications' => 0,
        ];

        // Events organized
        $stats['organized_events'] = $user->organizedEvents()->count();

        // Events participated (accepted invitations + requests)
        $acceptedInvitations = $user->receivedInvitations()
                                   ->where('status', EventInvitation::STATUS_ACCEPTED)
                                   ->count();

        $acceptedRequests = $user->eventRequests()
                                ->where('status', EventRequest::STATUS_ACCEPTED)
                                ->count();

        $stats['participated_events'] = $acceptedInvitations + $acceptedRequests;
        $stats['total_events'] = $stats['organized_events'] + $stats['participated_events'];

        // Pending invitations received
        $stats['pending_invitations'] = $user->receivedInvitations()
                                            ->where('status', EventInvitation::STATUS_PENDING)
                                            ->count();

        // Pending requests to own events (if organizer)
        if ($user->hasRole('organizer')) {
            $stats['pending_requests'] = EventRequest::whereHas('event', function ($query) use ($user) {
                $query->where('organizer_id', $user->id);
            })->where('status', EventRequest::STATUS_PENDING)->count();
        }

        // Unread notifications
        $stats['unread_notifications'] = $user->notifications()->where('is_read', false)->count();

        return $stats;
    }

    /**
     * Get recent activity for user
     */
    private function getRecentActivity($user)
    {
        // TODO: Implementare sistema attività
        // Per ora return array vuoto
        return [];
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
                'date' => $event->start_datetime->format('d M Y, H:i'),
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
                'date' => $event->start_datetime->format('d M Y, H:i'),
                'venue' => $event->venue_name,
                'type' => 'participating',
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

        // Azioni base per tutti
        $actions[] = [
            'key' => 'create_post',
            'icon' => 'ph ph-plus',
            'color' => 'primary'
        ];

        // Azioni specifiche per ruolo
        if ($user->hasRole('poet')) {
            $actions[] = [
                'key' => 'write_poem',
                'icon' => 'ph ph-pen-nib',
                'color' => 'info'
            ];
            $actions[] = [
                'key' => 'upload_performance',
                'icon' => 'ph ph-upload',
                'color' => 'warning'
            ];
        }

        if ($user->hasRole('organizer')) {
            $actions[] = [
                'key' => 'organize_event',
                'icon' => 'ph ph-calendar-plus',
                'color' => 'success'
            ];
        }

        if ($user->hasRole('venue_owner')) {
            $actions[] = [
                'key' => 'manage_venue',
                'icon' => 'ph ph-buildings',
                'color' => 'danger'
            ];
        }

        $actions[] = [
            'key' => 'find_events',
            'icon' => 'ph ph-magnifying-glass',
            'color' => 'secondary'
        ];

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
