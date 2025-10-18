<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use App\Services\ActivityService;

class DashboardIndex extends Component
{
    use WithPagination;

    // Properties for dynamic content
    public $currentMonth = null;
    public $currentYear = null;
    public $calendarEvents = [];
    public $wishlistEvents = [];

    // Properties for interactions
    public $showInvitationModal = false;
    public $selectedInvitation = null;
    public $showGroupInvitationModal = false;
    public $selectedGroupInvitation = null;

    protected $listeners = [
        'refreshDashboard' => 'refreshData',
        'invitationProcessed' => 'refreshData',
        'groupInvitationProcessed' => 'refreshData',
        'wishlistToggled' => 'refreshData',
    ];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadCalendarData();
    }

    public function render()
    {
        $user = Auth::user();
        
        return view('livewire.dashboard.dashboard-index', [
            'user' => $user,
            'stats' => $this->getUserStats($user),
            'recentActivity' => $this->getRecentActivity($user),
            'upcomingEvents' => $this->getUpcomingEvents($user),
            'quickActions' => $this->getQuickActions($user),
            'roleContent' => $this->getRoleSpecificContent($user),
        ]);
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
     * Get recent activity for user (excluding view activities)
     */
    private function getRecentActivity($user)
    {
        $activities = ActivityService::getRecentActivities($user, 15);

        return $activities->filter(function ($activity) {
            // Escludi le attività di visualizzazione (view)
            return $activity->action !== 'view' && $activity->action !== 'viewed';
        })->take(8)->map(function ($activity) {
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

        // Events in user's wishlist (if method exists)
        $wishlistedEvents = collect([]);
        if (method_exists($user, 'wishlistedEvents')) {
            $wishlistedEvents = $user->wishlistedEvents()
                                    ->upcoming()
                                    ->published()
                                    ->orderBy('start_datetime')
                                    ->limit(5)
                                    ->get();
        }

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
                'url' => route('articles.create')
            ];
        }

        // 5. Help - per tutti gli utenti autenticati
        $actions[] = [
            'key' => 'help',
            'icon' => 'ph ph-question',
            'color' => 'info',
            'url' => route('help.index')
        ];

        // 6. FAQ - per tutti gli utenti autenticati
        $actions[] = [
            'key' => 'faq',
            'icon' => 'ph ph-chat-circle',
            'color' => 'success',
            'url' => route('faq.index')
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
     * Load calendar data
     */
    public function loadCalendarData()
    {
        $user = Auth::user();
        $startDate = now()->setMonth($this->currentMonth)->setYear($this->currentYear)->startOfMonth();
        $endDate = now()->setMonth($this->currentMonth)->setYear($this->currentYear)->endOfMonth();

        // Get user's events for the current month
        $this->calendarEvents = $this->getUserEventsForMonth($user, $startDate, $endDate);
        
        // Get wishlist events if method exists
        if (method_exists($user, 'wishlistedEvents')) {
            $this->wishlistEvents = $this->getWishlistEventsForMonth($user, $startDate, $endDate);
        } else {
            $this->wishlistEvents = [];
        }
    }

    /**
     * Get user events for specific month
     */
    private function getUserEventsForMonth($user, $startDate, $endDate)
    {
        $events = [];

        // Events organized by user
        $organizedEvents = $user->organizedEvents()
                               ->whereBetween('start_datetime', [$startDate, $endDate])
                               ->published()
                               ->get();

        foreach ($organizedEvents as $event) {
            $events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_datetime->format('Y-m-d'),
                'time' => $event->start_datetime->format('H:i'),
                'url' => route('events.show', $event),
                'type' => 'organized',
                'className' => 'event-organized',
                'color' => 'info',
                'venue' => $event->venue_name,
                'city' => $event->city,
            ];
        }

        // Events where user is participating
        $participatingEvents = $user->participatingEvents()
                                   ->whereBetween('start_datetime', [$startDate, $endDate])
                                   ->published()
                                   ->get();

        foreach ($participatingEvents as $event) {
            $events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_datetime->format('Y-m-d'),
                'time' => $event->start_datetime->format('H:i'),
                'url' => route('events.show', $event),
                'type' => 'participating',
                'className' => 'event-participating',
                'color' => 'success',
                'venue' => $event->venue_name,
                'city' => $event->city,
            ];
        }

        return $events;
    }

    /**
     * Get wishlist events for specific month
     */
    private function getWishlistEventsForMonth($user, $startDate, $endDate)
    {
        $events = [];

        $wishlistEvents = $user->wishlistedEvents()
                              ->whereBetween('start_datetime', [$startDate, $endDate])
                              ->published()
                              ->get();

        foreach ($wishlistEvents as $event) {
            $events[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_datetime->format('Y-m-d'),
                'time' => $event->start_datetime->format('H:i'),
                'url' => route('events.show', $event),
                'type' => 'wishlisted',
                'className' => 'event-wishlisted',
                'color' => 'warning',
                'venue' => $event->venue_name,
                'city' => $event->city,
            ];
        }

        return $events;
    }

    /**
     * Refresh dashboard data
     */
    public function refreshData()
    {
        $this->loadCalendarData();
        $this->render();
    }

    /**
     * Navigate to previous month
     */
    public function previousMonth()
    {
        $this->currentMonth--;
        if ($this->currentMonth < 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        }
        $this->loadCalendarData();
    }

    /**
     * Navigate to next month
     */
    public function nextMonth()
    {
        $this->currentMonth++;
        if ($this->currentMonth > 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        }
        $this->loadCalendarData();
    }

    /**
     * Toggle wishlist for an event
     */
    public function toggleWishlist($eventId)
    {
        $user = Auth::user();
        $event = Event::find($eventId);
        
        if (!$event) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'Evento non trovato'
            ]);
            return;
        }

        if (method_exists($user, 'wishlistedEvents')) {
            if ($user->wishlistedEvents()->where('event_id', $eventId)->exists()) {
                $user->wishlistedEvents()->detach($eventId);
                $message = 'Rimosso dalla lista desideri';
                $inWishlist = false;
            } else {
                $user->wishlistedEvents()->attach($eventId);
                $message = 'Aggiunto alla lista desideri';
                $inWishlist = true;
            }
        } else {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'Funzionalità wishlist non disponibile'
            ]);
            return;
        }

        $this->dispatch('showNotification', [
            'type' => $inWishlist ? 'success' : 'info',
            'message' => $message
        ]);

        $this->refreshData();
    }

    /**
     * Process event invitation
     */
    public function processInvitation($invitationId, $action)
    {
        $invitation = EventInvitation::find($invitationId);
        
        if (!$invitation) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'Invito non trovato'
            ]);
            return;
        }

        if ($action === 'accept') {
            $invitation->update(['status' => EventInvitation::STATUS_ACCEPTED]);
            $message = 'Invito accettato';
        } else {
            $invitation->update(['status' => EventInvitation::STATUS_DECLINED]);
            $message = 'Invito rifiutato';
        }

        $this->dispatch('showNotification', [
            'type' => 'success',
            'message' => $message
        ]);

        $this->dispatch('invitationProcessed');
    }

    /**
     * Process group invitation
     */
    public function processGroupInvitation($invitationId, $action)
    {
        $invitation = \App\Models\GroupInvitation::find($invitationId);
        
        if (!$invitation) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'Invito gruppo non trovato'
            ]);
            return;
        }

        if ($action === 'accept') {
            $invitation->update(['status' => 'accepted']);
            $message = 'Invito gruppo accettato';
        } else {
            $invitation->update(['status' => 'declined']);
            $message = 'Invito gruppo rifiutato';
        }

        $this->dispatch('showNotification', [
            'type' => 'success',
            'message' => $message
        ]);

        $this->dispatch('groupInvitationProcessed');
    }

    /**
     * View event details
     */
    public function viewEvent($eventId)
    {
        $event = Event::find($eventId);
        
        if (!$event) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'message' => 'Evento non trovato'
            ]);
            return;
        }

        // Redirect to event details
        return redirect()->route('events.show', $event);
    }
}