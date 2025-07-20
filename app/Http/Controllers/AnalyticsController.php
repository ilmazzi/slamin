<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Show analytics dashboard
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $timeframe = $request->get('timeframe', '30days');

        $analytics = [
            'overview' => $this->getOverviewMetrics($user, $timeframe),
            'events' => $this->getEventAnalytics($user, $timeframe),
            'engagement' => $this->getEngagementMetrics($user, $timeframe),
            'geographical' => $this->getGeographicalData($user, $timeframe),
            'trends' => $this->getTrendAnalysis($user, $timeframe),
            'performance' => $this->getPerformanceMetrics($user, $timeframe),
            'notifications' => $this->getNotificationAnalytics($user, $timeframe),
        ];

        return view('analytics.index', compact('analytics', 'timeframe'));
    }

    /**
     * Get overview metrics
     */
    private function getOverviewMetrics(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        return [
            // Event Metrics
            'total_events' => $user->organizedEvents()->count(),
            'events_this_period' => $user->organizedEvents()
                ->whereBetween('created_at', $dateRange)
                ->count(),
            'upcoming_events' => $user->organizedEvents()
                ->where('start_datetime', '>', now())
                ->count(),
            'completed_events' => $user->organizedEvents()
                ->where('end_datetime', '<', now())
                ->count(),

            // Participation Metrics
            'total_participants' => $this->getTotalParticipants($user),
            'avg_participants_per_event' => $this->getAverageParticipants($user),
            'invitation_response_rate' => $this->getInvitationResponseRate($user),
            'request_approval_rate' => $this->getRequestApprovalRate($user),

            // Engagement Metrics
            'total_invitations_sent' => $user->sentInvitations()->count(),
            'total_requests_received' => EventRequest::whereHas('event', function($q) use ($user) {
                $q->where('organizer_id', $user->id);
            })->count(),

            // Growth Metrics
            'growth_rate' => $this->getGrowthRate($user, $timeframe),
            'repeat_participants' => $this->getRepeatParticipants($user),
        ];
    }

    /**
     * Get detailed event analytics
     */
    private function getEventAnalytics(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        return [
            // Event Performance
            'top_events' => $this->getTopEvents($user, $timeframe),
            'event_success_rate' => $this->getEventSuccessRate($user),
            'cancellation_rate' => $this->getCancellationRate($user),

            // Timing Analysis
            'best_days_of_week' => $this->getBestDaysOfWeek($user),
            'best_times_of_day' => $this->getBestTimesOfDay($user),
            'optimal_duration' => $this->getOptimalDuration($user),

            // Financial Analytics
            'total_revenue' => $this->getTotalRevenue($user),
            'avg_ticket_price' => $this->getAverageTicketPrice($user),
            'revenue_by_month' => $this->getRevenueByMonth($user, $timeframe),

            // Venue Analytics
            'venue_performance' => $this->getVenuePerformance($user),
            'city_distribution' => $this->getCityDistribution($user),
        ];
    }

    /**
     * Get engagement metrics
     */
    private function getEngagementMetrics(User $user, string $timeframe): array
    {
        return [
            // Invitation Metrics
            'invitation_stats' => $this->getInvitationStats($user, $timeframe),
            'response_time_analysis' => $this->getResponseTimeAnalysis($user),
            'invitation_effectiveness' => $this->getInvitationEffectiveness($user),

            // Request Metrics
            'request_stats' => $this->getRequestStats($user, $timeframe),
            'request_quality_score' => $this->getRequestQualityScore($user),

            // Community Engagement
            'user_retention' => $this->getUserRetention($user),
            'network_growth' => $this->getNetworkGrowth($user),
            'collaboration_frequency' => $this->getCollaborationFrequency($user),
        ];
    }

    /**
     * Get geographical data
     */
    private function getGeographicalData(User $user, string $timeframe): array
    {
        return [
            'events_by_city' => $this->getEventsByCity($user),
            'participant_origins' => $this->getParticipantOrigins($user),
            'coverage_area' => $this->getCoverageArea($user),
            'expansion_opportunities' => $this->getExpansionOpportunities($user),
            'travel_distance_analysis' => $this->getTravelDistanceAnalysis($user),
        ];
    }

    /**
     * Get trend analysis
     */
    private function getTrendAnalysis(User $user, string $timeframe): array
    {
        return [
            'event_creation_trend' => $this->getEventCreationTrend($user, $timeframe),
            'participation_trend' => $this->getParticipationTrend($user, $timeframe),
            'seasonal_patterns' => $this->getSeasonalPatterns($user),
            'growth_predictions' => $this->getGrowthPredictions($user),
            'market_saturation' => $this->getMarketSaturation($user),
        ];
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(User $user, string $timeframe): array
    {
        return [
            'efficiency_score' => $this->getEfficiencyScore($user),
            'organization_quality' => $this->getOrganizationQuality($user),
            'participant_satisfaction' => $this->getParticipantSatisfaction($user),
            'event_completion_rate' => $this->getEventCompletionRate($user),
            'planning_effectiveness' => $this->getPlanningEffectiveness($user),
        ];
    }

    /**
     * Get notification analytics
     */
    private function getNotificationAnalytics(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        return [
            'notifications_sent' => Notification::whereHas('user.organizedEvents', function($q) use ($user) {
                $q->where('organizer_id', $user->id);
            })->whereBetween('created_at', $dateRange)->count(),

            'notification_types' => Notification::whereHas('user.organizedEvents', function($q) use ($user) {
                $q->where('organizer_id', $user->id);
            })->select('type', DB::raw('count(*) as count'))
              ->groupBy('type')
              ->pluck('count', 'type'),

            'read_rate' => $this->getNotificationReadRate($user),
            'response_times' => $this->getNotificationResponseTimes($user),
        ];
    }

    /**
     * Export analytics data
     */
    public function export(Request $request): JsonResponse
    {
        $user = Auth::user();
        $format = $request->get('format', 'json');
        $timeframe = $request->get('timeframe', '30days');

        $data = [
            'overview' => $this->getOverviewMetrics($user, $timeframe),
            'events' => $this->getEventAnalytics($user, $timeframe),
            'engagement' => $this->getEngagementMetrics($user, $timeframe),
            'geographical' => $this->getGeographicalData($user, $timeframe),
            'generated_at' => now()->toISOString(),
        ];

        if ($format === 'csv') {
            return $this->exportToCsv($data);
        }

        return response()->json($data);
    }

    /**
     * Get real-time analytics
     */
    public function realtime(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'active_events' => $user->organizedEvents()
                ->where('start_datetime', '<=', now())
                ->where('end_datetime', '>=', now())
                ->count(),

            'pending_responses' => $user->sentInvitations()
                ->where('status', 'pending')
                ->count(),

            'new_requests_today' => EventRequest::whereHas('event', function($q) use ($user) {
                $q->where('organizer_id', $user->id);
            })->whereDate('created_at', today())->count(),

            'notifications_unread' => $user->notifications()
                ->where('is_read', false)
                ->count(),

            'events_next_7_days' => $user->organizedEvents()
                ->whereBetween('start_datetime', [now(), now()->addDays(7)])
                ->count(),

            'last_update' => now()->toISOString(),
        ]);
    }

    // Helper methods for calculations

    private function getDateRange(string $timeframe): array
    {
        switch ($timeframe) {
            case '7days':
                return [now()->subDays(7), now()];
            case '30days':
                return [now()->subDays(30), now()];
            case '90days':
                return [now()->subDays(90), now()];
            case '1year':
                return [now()->subYear(), now()];
            default:
                return [now()->subDays(30), now()];
        }
    }

    private function getTotalParticipants(User $user): int
    {
        $invitations = EventInvitation::whereHas('event', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->where('status', 'accepted')->count();

        $requests = EventRequest::whereHas('event', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->where('status', 'accepted')->count();

        return $invitations + $requests;
    }

    private function getAverageParticipants(User $user): float
    {
        $events = $user->organizedEvents()->count();
        if ($events === 0) return 0;

        return round($this->getTotalParticipants($user) / $events, 2);
    }

    private function getInvitationResponseRate(User $user): float
    {
        $totalInvitations = $user->sentInvitations()->count();
        if ($totalInvitations === 0) return 0;

        $responded = $user->sentInvitations()
            ->whereIn('status', ['accepted', 'declined'])
            ->count();

        return round(($responded / $totalInvitations) * 100, 2);
    }

    private function getRequestApprovalRate(User $user): float
    {
        $totalRequests = EventRequest::whereHas('event', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->count();

        if ($totalRequests === 0) return 0;

        $approved = EventRequest::whereHas('event', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->where('status', 'accepted')->count();

        return round(($approved / $totalRequests) * 100, 2);
    }

    private function getGrowthRate(User $user, string $timeframe): float
    {
        $dateRange = $this->getDateRange($timeframe);
        $previousRange = [
            Carbon::parse($dateRange[0])->subDays(Carbon::parse($dateRange[1])->diffInDays($dateRange[0])),
            $dateRange[0]
        ];

        $currentPeriod = $user->organizedEvents()
            ->whereBetween('created_at', $dateRange)
            ->count();

        $previousPeriod = $user->organizedEvents()
            ->whereBetween('created_at', $previousRange)
            ->count();

        if ($previousPeriod === 0) return $currentPeriod > 0 ? 100 : 0;

        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 2);
    }

    private function getRepeatParticipants(User $user): int
    {
        // Logic to find users who participated in multiple events
        $participants = DB::table('event_invitations')
            ->join('events', 'event_invitations.event_id', '=', 'events.id')
            ->where('events.organizer_id', $user->id)
            ->where('event_invitations.status', 'accepted')
            ->select('event_invitations.invited_user_id')
            ->groupBy('event_invitations.invited_user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        return $participants;
    }

    private function getTopEvents(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        return $user->organizedEvents()
            ->whereBetween('created_at', $dateRange)
            ->withCount(['invitations as accepted_invitations' => function($q) {
                $q->where('status', 'accepted');
            }])
            ->withCount(['requests as accepted_requests' => function($q) {
                $q->where('status', 'accepted');
            }])
            ->orderByDesc('accepted_invitations')
            ->limit(5)
            ->get()
            ->map(function($event) {
                return [
                    'title' => $event->title,
                    'participants' => $event->accepted_invitations + $event->accepted_requests,
                    'date' => $event->start_datetime->format('d/m/Y'),
                    'city' => $event->city,
                ];
            })
            ->toArray();
    }

    private function getEventSuccessRate(User $user): float
    {
        $totalEvents = $user->organizedEvents()->count();
        if ($totalEvents === 0) return 0;

        $successfulEvents = $user->organizedEvents()
            ->where('status', 'published')
            ->where('end_datetime', '<', now())
            ->count();

        return round(($successfulEvents / $totalEvents) * 100, 2);
    }

    private function getCancellationRate(User $user): float
    {
        $totalEvents = $user->organizedEvents()->count();
        if ($totalEvents === 0) return 0;

        $cancelledEvents = $user->organizedEvents()
            ->where('status', 'cancelled')
            ->count();

        return round(($cancelledEvents / $totalEvents) * 100, 2);
    }

    private function getBestDaysOfWeek(User $user): array
    {
        return $user->organizedEvents()
            ->selectRaw('DAYOFWEEK(start_datetime) as day_of_week, COUNT(*) as event_count')
            ->groupBy('day_of_week')
            ->orderByDesc('event_count')
            ->get()
            ->mapWithKeys(function($item) {
                $days = ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];
                return [$days[$item->day_of_week - 1] => $item->event_count];
            })
            ->toArray();
    }

    private function getBestTimesOfDay(User $user): array
    {
        return $user->organizedEvents()
            ->selectRaw('HOUR(start_datetime) as hour, COUNT(*) as event_count')
            ->groupBy('hour')
            ->orderByDesc('event_count')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->hour . ':00' => $item->event_count];
            })
            ->toArray();
    }

    private function getOptimalDuration(User $user): float
    {
        $avgDuration = $user->organizedEvents()
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, start_datetime, end_datetime)) as avg_duration')
            ->value('avg_duration');

        return round($avgDuration ?: 0, 1);
    }

    private function getTotalRevenue(User $user): float
    {
        return $user->organizedEvents()
            ->sum('entry_fee') ?: 0;
    }

    private function getAverageTicketPrice(User $user): float
    {
        return $user->organizedEvents()
            ->where('entry_fee', '>', 0)
            ->avg('entry_fee') ?: 0;
    }

    private function getRevenueByMonth(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        return $user->organizedEvents()
            ->whereBetween('start_datetime', $dateRange)
            ->selectRaw('MONTH(start_datetime) as month, SUM(entry_fee) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function($item) {
                $months = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
                return [$months[$item->month - 1] => $item->revenue ?: 0];
            })
            ->toArray();
    }

    private function getVenuePerformance(User $user): array
    {
        return $user->organizedEvents()
            ->selectRaw('venue_name, COUNT(*) as events, AVG(entry_fee) as avg_price')
            ->groupBy('venue_name')
            ->orderByDesc('events')
            ->limit(10)
            ->get()
            ->map(function($venue) {
                return [
                    'name' => $venue->venue_name,
                    'events' => $venue->events,
                    'avg_price' => round($venue->avg_price ?: 0, 2),
                ];
            })
            ->toArray();
    }

    private function getCityDistribution(User $user): array
    {
        return $user->organizedEvents()
            ->selectRaw('city, COUNT(*) as event_count')
            ->groupBy('city')
            ->orderByDesc('event_count')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->city => $item->event_count];
            })
            ->toArray();
    }

    private function getInvitationStats(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        return [
            'total_sent' => $user->sentInvitations()
                ->whereBetween('created_at', $dateRange)
                ->count(),
            'accepted' => $user->sentInvitations()
                ->whereBetween('created_at', $dateRange)
                ->where('status', 'accepted')
                ->count(),
            'declined' => $user->sentInvitations()
                ->whereBetween('created_at', $dateRange)
                ->where('status', 'declined')
                ->count(),
            'pending' => $user->sentInvitations()
                ->whereBetween('created_at', $dateRange)
                ->where('status', 'pending')
                ->count(),
        ];
    }

    private function getResponseTimeAnalysis(User $user): array
    {
        $avgResponseTime = $user->sentInvitations()
            ->whereNotNull('responded_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, responded_at)) as avg_hours')
            ->value('avg_hours');

        return [
            'average_hours' => round($avgResponseTime ?: 0, 1),
            'quick_responses' => $user->sentInvitations()
                ->whereNotNull('responded_at')
                ->whereRaw('TIMESTAMPDIFF(HOUR, created_at, responded_at) <= 24')
                ->count(),
        ];
    }

    private function getInvitationEffectiveness(User $user): float
    {
        $totalInvitations = $user->sentInvitations()->count();
        if ($totalInvitations === 0) return 0;

        $accepted = $user->sentInvitations()->where('status', 'accepted')->count();

        return round(($accepted / $totalInvitations) * 100, 2);
    }

    private function getRequestStats(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        $requests = EventRequest::whereHas('event', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->whereBetween('created_at', $dateRange);

        return [
            'total_received' => $requests->count(),
            'accepted' => $requests->where('status', 'accepted')->count(),
            'declined' => $requests->where('status', 'declined')->count(),
            'pending' => $requests->where('status', 'pending')->count(),
        ];
    }

    private function getRequestQualityScore(User $user): float
    {
        // Custom algorithm to score request quality based on various factors
        $totalRequests = EventRequest::whereHas('event', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->count();

        if ($totalRequests === 0) return 0;

        $qualityRequests = EventRequest::whereHas('event', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->where(function($q) {
            $q->whereNotNull('experience')
              ->orWhereNotNull('portfolio_links');
        })->count();

        return round(($qualityRequests / $totalRequests) * 100, 2);
    }

    private function getUserRetention(User $user): float
    {
        // Calculate user retention rate
        $totalParticipants = collect();
        $returningParticipants = 0;

        $user->organizedEvents->each(function($event) use ($totalParticipants, &$returningParticipants) {
            $participants = collect();

            $event->invitations->where('status', 'accepted')->each(function($invitation) use ($participants) {
                $participants->push($invitation->invited_user_id);
            });

            $event->requests->where('status', 'accepted')->each(function($request) use ($participants) {
                $participants->push($request->user_id);
            });

            $returning = $participants->intersect($totalParticipants);
            $returningParticipants += $returning->count();

            $totalParticipants = $totalParticipants->merge($participants)->unique();
        });

        if ($totalParticipants->count() === 0) return 0;

        return round(($returningParticipants / $totalParticipants->count()) * 100, 2);
    }

    private function getNetworkGrowth(User $user): array
    {
        // Track network growth over time
        return $user->organizedEvents()
            ->selectRaw('MONTH(created_at) as month, COUNT(DISTINCT id) as events')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function($item) {
                $months = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
                return [$months[$item->month - 1] => $item->events];
            })
            ->toArray();
    }

    private function getCollaborationFrequency(User $user): float
    {
        $totalEvents = $user->organizedEvents()->count();
        if ($totalEvents === 0) return 0;

        $collaborativeEvents = $user->organizedEvents()
            ->whereNotNull('venue_owner_id')
            ->count();

        return round(($collaborativeEvents / $totalEvents) * 100, 2);
    }

    private function getEventsByCity(User $user): array
    {
        return $this->getCityDistribution($user);
    }

    private function getParticipantOrigins(User $user): array
    {
        // This would require location data for participants
        return ['analysis' => 'Requires participant location data'];
    }

    private function getCoverageArea(User $user): array
    {
        $events = $user->organizedEvents()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['latitude', 'longitude', 'city']);

        if ($events->count() === 0) return [];

        $centerLat = $events->avg('latitude');
        $centerLng = $events->avg('longitude');

        return [
            'center' => ['lat' => $centerLat, 'lng' => $centerLng],
            'events' => $events->count(),
            'cities' => $events->pluck('city')->unique()->count(),
        ];
    }

    private function getExpansionOpportunities(User $user): array
    {
        $currentCities = $user->organizedEvents()->pluck('city')->unique();

        // Find nearby cities with events from other organizers
        $opportunities = Event::whereNotIn('city', $currentCities)
            ->where('organizer_id', '!=', $user->id)
            ->selectRaw('city, COUNT(*) as market_size')
            ->groupBy('city')
            ->orderByDesc('market_size')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'city' => $item->city,
                    'market_size' => $item->market_size,
                    'opportunity_score' => $item->market_size * 10, // Simple scoring
                ];
            })
            ->toArray();

        return $opportunities;
    }

    private function getTravelDistanceAnalysis(User $user): array
    {
        // Analyze average distances between venues
        return ['analysis' => 'Requires detailed geographical calculations'];
    }

    private function getEventCreationTrend(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        return $user->organizedEvents()
            ->whereBetween('created_at', $dateRange)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as events')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->date => $item->events];
            })
            ->toArray();
    }

    private function getParticipationTrend(User $user, string $timeframe): array
    {
        $dateRange = $this->getDateRange($timeframe);

        $invitations = EventInvitation::whereHas('event', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->whereBetween('created_at', $dateRange)
          ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
          ->groupBy('date')
          ->get()
          ->mapWithKeys(function($item) {
              return [$item->date => $item->count];
          });

        return $invitations->toArray();
    }

    private function getSeasonalPatterns(User $user): array
    {
        return $user->organizedEvents()
            ->selectRaw('QUARTER(start_datetime) as quarter, COUNT(*) as events')
            ->groupBy('quarter')
            ->orderBy('quarter')
            ->get()
            ->mapWithKeys(function($item) {
                $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
                return [$quarters[$item->quarter - 1] => $item->events];
            })
            ->toArray();
    }

    private function getGrowthPredictions(User $user): array
    {
        // Simple linear prediction based on historical data
        $monthlyEvents = $user->organizedEvents()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as events')
            ->groupBy('year', 'month')
            ->orderBy('year', 'month')
            ->get();

        if ($monthlyEvents->count() < 3) {
            return ['prediction' => 'Insufficient data for prediction'];
        }

        $trend = $monthlyEvents->slice(-3)->avg('events');

        return [
            'next_month_prediction' => round($trend, 1),
            'confidence' => 'Low', // Would need more sophisticated algorithm
        ];
    }

    private function getMarketSaturation(User $user): array
    {
        $userEvents = $user->organizedEvents()->count();
        $totalEventsInCities = Event::whereIn('city', $user->organizedEvents()->pluck('city'))
            ->count();

        if ($totalEventsInCities === 0) return ['saturation' => 0];

        $marketShare = round(($userEvents / $totalEventsInCities) * 100, 2);

        return [
            'market_share' => $marketShare,
            'saturation_level' => $marketShare > 50 ? 'High' : ($marketShare > 25 ? 'Medium' : 'Low'),
        ];
    }

    private function getEfficiencyScore(User $user): float
    {
        // Composite score based on various efficiency metrics
        $responseRate = $this->getInvitationResponseRate($user);
        $approvalRate = $this->getRequestApprovalRate($user);
        $cancellationRate = $this->getCancellationRate($user);

        $efficiency = ($responseRate + $approvalRate + (100 - $cancellationRate)) / 3;

        return round($efficiency, 2);
    }

    private function getOrganizationQuality(User $user): float
    {
        // Score based on event details completeness and quality
        $events = $user->organizedEvents();
        $totalEvents = $events->count();

        if ($totalEvents === 0) return 0;

        $qualityScore = 0;
        $qualityScore += $events->whereNotNull('description')->count() / $totalEvents * 25;
        $qualityScore += $events->whereNotNull('requirements')->count() / $totalEvents * 25;
        $qualityScore += $events->whereNotNull('image_url')->count() / $totalEvents * 25;
        $qualityScore += $events->whereNotNull('venue_owner_id')->count() / $totalEvents * 25;

        return round($qualityScore, 2);
    }

    private function getParticipantSatisfaction(User $user): float
    {
        // Based on repeat participation and response patterns
        $retention = $this->getUserRetention($user);
        $responseRate = $this->getInvitationResponseRate($user);

        return round(($retention + $responseRate) / 2, 2);
    }

    private function getEventCompletionRate(User $user): float
    {
        $totalEvents = $user->organizedEvents()->count();
        if ($totalEvents === 0) return 0;

        $completedEvents = $user->organizedEvents()
            ->where('end_datetime', '<', now())
            ->where('status', '!=', 'cancelled')
            ->count();

        return round(($completedEvents / $totalEvents) * 100, 2);
    }

    private function getPlanningEffectiveness(User $user): float
    {
        // Based on advance planning time and success rate
        $avgPlanningTime = $user->organizedEvents()
            ->selectRaw('AVG(DATEDIFF(start_datetime, created_at)) as avg_days')
            ->value('avg_days');

        $planningScore = min(($avgPlanningTime / 30) * 100, 100); // 30 days = 100%

        return round($planningScore ?: 0, 2);
    }

    private function getNotificationReadRate(User $user): float
    {
        $totalNotifications = Notification::whereHas('user.organizedEvents', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->count();

        if ($totalNotifications === 0) return 0;

        $readNotifications = Notification::whereHas('user.organizedEvents', function($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->where('is_read', true)->count();

        return round(($readNotifications / $totalNotifications) * 100, 2);
    }

    private function getNotificationResponseTimes(User $user): array
    {
        // This would require tracking when notifications lead to actions
        return ['average_response_time' => 'Requires action tracking implementation'];
    }

    private function exportToCsv(array $data): JsonResponse
    {
        // Convert data to CSV format
        $csv = "Metric,Value\n";

        foreach ($data['overview'] as $key => $value) {
            $csv .= "\"{$key}\",\"{$value}\"\n";
        }

        return response()->json([
            'format' => 'csv',
            'data' => $csv,
            'filename' => 'analytics_' . now()->format('Y-m-d_H-i-s') . '.csv'
        ]);
    }
}
