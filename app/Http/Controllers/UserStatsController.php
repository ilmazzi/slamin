<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use App\Models\Poem;
use App\Models\PoemComment;
use App\Models\PoemLike;
use App\Models\Video;
use App\Models\VideoComment;
use App\Models\VideoLike;
use App\Models\VideoSnap;
use App\Models\Article;
use App\Models\Group;
use App\Models\GroupMembership;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserStatsController extends Controller
{
    /**
     * Display user statistics page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get time period filter (default: last 12 months)
        $timeframe = $request->get('timeframe', '12_months');
        $dateRange = $this->getDateRange($timeframe);

        // Get all statistics
        $stats = $this->getAllUserStats($user, $dateRange);

        // Get temporal data for charts
        $temporalData = $this->getTemporalData($user, $dateRange);

        // Get group statistics if user is member of groups
        $groupStats = $this->getGroupStats($user, $dateRange);

        return view('user-stats.index', compact(
            'user',
            'stats',
            'temporalData',
            'groupStats',
            'timeframe'
        ));
    }

    /**
     * Get comprehensive user statistics
     */
    private function getAllUserStats($user, $dateRange)
    {
        return [
            // Content Statistics
            'content' => $this->getContentStats($user, $dateRange),

            // Engagement Statistics
            'engagement' => $this->getEngagementStats($user, $dateRange),

            // Event Statistics
            'events' => $this->getEventStats($user, $dateRange),

            // Location Statistics
            'locations' => $this->getLocationStats($user),

            // Performance Statistics (for future poetry slam results)
            'performance' => $this->getPerformanceStats($user),
        ];
    }

    /**
     * Get content creation statistics
     */
    private function getContentStats($user, $dateRange)
    {
        return [
            'poems' => [
                'total' => $user->poems()->count(),
                'published' => $user->poems()->where('is_public', true)->where('is_draft', false)->count(),
                'drafts' => $user->poems()->where('is_draft', true)->count(),
                'this_period' => $user->poems()->whereBetween('created_at', $dateRange)->count(),
                'views' => $user->poems()->sum('view_count'),
                'views_this_period' => $user->poems()->whereBetween('created_at', $dateRange)->sum('view_count'),
            ],
            'videos' => [
                'total' => $user->videos()->count(),
                'published' => $user->videos()->where('status', 'published')->count(),
                'this_period' => $user->videos()->whereBetween('created_at', $dateRange)->count(),
                'views' => $user->videos()->sum('view_count'),
                'views_this_period' => $user->videos()->whereBetween('created_at', $dateRange)->sum('view_count'),
            ],
            'articles' => [
                'total' => $user->articles()->count(),
                'published' => $user->articles()->where('status', 'published')->count(),
                'this_period' => $user->articles()->whereBetween('created_at', $dateRange)->count(),
                'views' => $user->articles()->sum('views_count'),
                'views_this_period' => $user->articles()->whereBetween('created_at', $dateRange)->sum('views_count'),
            ],
        ];
    }

    /**
     * Get engagement statistics (likes, comments, snaps)
     */
    private function getEngagementStats($user, $dateRange)
    {
        return [
            'received' => [
                'poem_likes' => $this->getLikesReceived($user, 'App\Models\Poem', $dateRange),
                'poem_comments' => $this->getCommentsReceived($user, 'App\Models\Poem', $dateRange),
                'video_likes' => $this->getLikesReceived($user, 'App\Models\Video', $dateRange),
                'video_comments' => $this->getCommentsReceived($user, 'App\Models\Video', $dateRange),
                'video_snaps' => $this->getVideoSnapsReceived($user, $dateRange),
            ],
            'given' => [
                'poem_likes' => $this->getLikesGiven($user, 'App\Models\Poem', $dateRange),
                'poem_comments' => $this->getCommentsGiven($user, 'App\Models\Poem', $dateRange),
                'video_likes' => $this->getLikesGiven($user, 'App\Models\Video', $dateRange),
                'video_comments' => $this->getCommentsGiven($user, 'App\Models\Video', $dateRange),
                'video_snaps' => $user->videoSnaps()->whereBetween('created_at', $dateRange)->count(),
            ],
        ];
    }

    /**
     * Get event participation statistics
     */
    private function getEventStats($user, $dateRange)
    {
        return [
            'created' => [
                'total' => $user->organizedEvents()->count(),
                'this_period' => $user->organizedEvents()->whereBetween('created_at', $dateRange)->count(),
                'past' => $user->organizedEvents()->where('start_datetime', '<', now())->count(),
                'upcoming' => $user->organizedEvents()->where('start_datetime', '>=', now())->count(),
            ],
            'participated' => [
                'total' => $this->getTotalParticipatedEvents($user),
                'this_period' => $this->getParticipatedEventsInPeriod($user, $dateRange),
                'past' => $this->getPastParticipatedEvents($user),
                'upcoming' => $this->getUpcomingParticipatedEvents($user),
            ],
        ];
    }

    /**
     * Get unique locations visited
     */
    private function getLocationStats($user)
    {
        // Get all events user has participated in (organized + participated)
        $organizedEvents = $user->organizedEvents()
            ->where('start_datetime', '<', now())
            ->whereNotNull('venue_name')
            ->whereNotNull('city')
            ->get();

        $participatedEvents = $user->participatingEvents()
            ->where('start_datetime', '<', now())
            ->whereNotNull('venue_name')
            ->whereNotNull('city')
            ->get();

        $allEvents = $organizedEvents->merge($participatedEvents);

        // Count unique locations (venue + city combination)
        $uniqueLocations = $allEvents->groupBy(function ($event) {
            return strtolower(trim($event->venue_name . ', ' . $event->city));
        });

        return [
            'unique_venues' => $uniqueLocations->count(),
            'unique_cities' => $allEvents->groupBy('city')->count(),
            'locations' => $uniqueLocations->map(function ($events, $location) {
                return [
                    'location' => $location,
                    'events_count' => $events->count(),
                    'last_visit' => $events->max('start_datetime'),
                ];
            })->sortByDesc('events_count')->take(10),
        ];
    }

    /**
     * Get performance statistics (for future poetry slam results)
     */
    private function getPerformanceStats($user)
    {
        // TODO: Implement when poetry slam results system is ready
        return [
            'slam_wins' => 0,
            'slam_second_places' => 0,
            'slam_third_places' => 0,
            'total_slam_participations' => 0,
            'best_ranking' => null,
            'average_ranking' => null,
        ];
    }

    /**
     * Get temporal data for charts
     */
    private function getTemporalData($user, $dateRange)
    {
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];

        return [
            'poems' => $this->getTemporalPoemsData($user, $startDate, $endDate),
            'videos' => $this->getTemporalVideosData($user, $startDate, $endDate),
            'events' => $this->getTemporalEventsData($user, $startDate, $endDate),
            'engagement' => $this->getTemporalEngagementData($user, $startDate, $endDate),
        ];
    }

    /**
     * Get group statistics
     */
    private function getGroupStats($user, $dateRange)
    {
        $userGroups = $user->groups()->get();

        if ($userGroups->isEmpty()) {
            return null;
        }

        $groupStats = [];

        foreach ($userGroups as $group) {
            $groupStats[] = [
                'group' => $group,
                'role' => $user->getRoleInGroup($group),
                'is_admin' => $user->isAdminOf($group),
                'is_moderator' => $user->isModeratorOf($group),
                'total_events' => $group->events()->count(),
                'members_count' => $group->members()->count(),
                'created_at' => $group->created_at,
            ];
        }

        return $groupStats;
    }

    // Helper methods for specific statistics

    private function getLikesReceived($user, $modelType, $dateRange)
    {
        return DB::table('unified_likes')
            ->join($this->getTableName($modelType), 'unified_likes.likeable_id', '=', $this->getTableName($modelType) . '.id')
            ->where('unified_likes.likeable_type', $modelType)
            ->where($this->getTableName($modelType) . '.user_id', $user->id)
            ->whereBetween('unified_likes.created_at', $dateRange)
            ->count();
    }

    private function getCommentsReceived($user, $modelType, $dateRange)
    {
        return DB::table('unified_comments')
            ->join($this->getTableName($modelType), 'unified_comments.commentable_id', '=', $this->getTableName($modelType) . '.id')
            ->where('unified_comments.commentable_type', $modelType)
            ->where($this->getTableName($modelType) . '.user_id', $user->id)
            ->whereBetween('unified_comments.created_at', $dateRange)
            ->count();
    }

    private function getLikesGiven($user, $modelType, $dateRange)
    {
        return DB::table('unified_likes')
            ->where('user_id', $user->id)
            ->where('likeable_type', $modelType)
            ->whereBetween('created_at', $dateRange)
            ->count();
    }

    private function getCommentsGiven($user, $modelType, $dateRange)
    {
        return DB::table('unified_comments')
            ->where('user_id', $user->id)
            ->where('commentable_type', $modelType)
            ->whereBetween('created_at', $dateRange)
            ->count();
    }

    private function getTableName($modelType)
    {
        switch ($modelType) {
            case 'App\Models\Poem':
                return 'poems';
            case 'App\Models\Video':
                return 'videos';
            case 'App\Models\Article':
                return 'articles';
            default:
                return strtolower(class_basename($modelType)) . 's';
        }
    }

    private function getVideoSnapsReceived($user, $dateRange)
    {
        return DB::table('video_snaps')
            ->join('videos', 'video_snaps.video_id', '=', 'videos.id')
            ->where('videos.user_id', $user->id)
            ->whereBetween('video_snaps.created_at', $dateRange)
            ->count();
    }

    private function getTotalParticipatedEvents($user)
    {
        $acceptedInvitations = $user->receivedInvitations()
            ->where('status', EventInvitation::STATUS_ACCEPTED)
            ->count();

        $acceptedRequests = $user->eventRequests()
            ->where('status', EventRequest::STATUS_ACCEPTED)
            ->count();

        return $acceptedInvitations + $acceptedRequests;
    }

    private function getParticipatedEventsInPeriod($user, $dateRange)
    {
        $acceptedInvitations = $user->receivedInvitations()
            ->where('status', EventInvitation::STATUS_ACCEPTED)
            ->whereBetween('created_at', $dateRange)
            ->count();

        $acceptedRequests = $user->eventRequests()
            ->where('status', EventRequest::STATUS_ACCEPTED)
            ->whereBetween('created_at', $dateRange)
            ->count();

        return $acceptedInvitations + $acceptedRequests;
    }

    private function getPastParticipatedEvents($user)
    {
        $acceptedInvitations = $user->receivedInvitations()
            ->where('status', EventInvitation::STATUS_ACCEPTED)
            ->whereHas('event', function($q) {
                $q->where('start_datetime', '<', now());
            })->count();

        $acceptedRequests = $user->eventRequests()
            ->where('status', EventRequest::STATUS_ACCEPTED)
            ->whereHas('event', function($q) {
                $q->where('start_datetime', '<', now());
            })->count();

        return $acceptedInvitations + $acceptedRequests;
    }

    private function getUpcomingParticipatedEvents($user)
    {
        $acceptedInvitations = $user->receivedInvitations()
            ->where('status', EventInvitation::STATUS_ACCEPTED)
            ->whereHas('event', function($q) {
                $q->where('start_datetime', '>=', now());
            })->count();

        $acceptedRequests = $user->eventRequests()
            ->where('status', EventRequest::STATUS_ACCEPTED)
            ->whereHas('event', function($q) {
                $q->where('start_datetime', '>=', now());
            })->count();

        return $acceptedInvitations + $acceptedRequests;
    }

    // Temporal data methods

    private function getTemporalPoemsData($user, $startDate, $endDate)
    {
        return $user->poems()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    private function getTemporalVideosData($user, $startDate, $endDate)
    {
        return $user->videos()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    private function getTemporalEventsData($user, $startDate, $endDate)
    {
        $organized = $user->organizedEvents()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $participated = $this->getParticipatedEventsTemporalData($user, $startDate, $endDate);

        return [
            'organized' => $organized,
            'participated' => $participated,
        ];
    }

    private function getParticipatedEventsTemporalData($user, $startDate, $endDate)
    {
        $invitations = DB::table('event_invitations')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('invited_user_id', $user->id)
            ->where('status', EventInvitation::STATUS_ACCEPTED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $requests = DB::table('event_requests')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('user_id', $user->id)
            ->where('status', EventRequest::STATUS_ACCEPTED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Merge and sum the data
        $allDates = array_unique(array_merge(array_keys($invitations), array_keys($requests)));
        $result = [];

        foreach ($allDates as $date) {
            $result[$date] = ($invitations[$date] ?? 0) + ($requests[$date] ?? 0);
        }

        return $result;
    }

    private function getTemporalEngagementData($user, $startDate, $endDate)
    {
        return [
            'likes_received' => $this->getTemporalLikesReceived($user, $startDate, $endDate),
            'comments_received' => $this->getTemporalCommentsReceived($user, $startDate, $endDate),
            'likes_given' => $this->getTemporalLikesGiven($user, $startDate, $endDate),
            'comments_given' => $this->getTemporalCommentsGiven($user, $startDate, $endDate),
        ];
    }

    private function getTemporalLikesReceived($user, $startDate, $endDate)
    {
        $poemLikes = DB::table('unified_likes')
            ->join('poems', 'unified_likes.likeable_id', '=', 'poems.id')
            ->selectRaw('DATE(unified_likes.created_at) as date, COUNT(*) as count')
            ->where('unified_likes.likeable_type', 'App\Models\Poem')
            ->where('poems.user_id', $user->id)
            ->whereBetween('unified_likes.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $videoLikes = DB::table('unified_likes')
            ->join('videos', 'unified_likes.likeable_id', '=', 'videos.id')
            ->selectRaw('DATE(unified_likes.created_at) as date, COUNT(*) as count')
            ->where('unified_likes.likeable_type', 'App\Models\Video')
            ->where('videos.user_id', $user->id)
            ->whereBetween('unified_likes.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Merge and sum
        $allDates = array_unique(array_merge(array_keys($poemLikes), array_keys($videoLikes)));
        $result = [];

        foreach ($allDates as $date) {
            $result[$date] = ($poemLikes[$date] ?? 0) + ($videoLikes[$date] ?? 0);
        }

        return $result;
    }

    private function getTemporalCommentsReceived($user, $startDate, $endDate)
    {
        $poemComments = DB::table('unified_comments')
            ->join('poems', 'unified_comments.commentable_id', '=', 'poems.id')
            ->selectRaw('DATE(unified_comments.created_at) as date, COUNT(*) as count')
            ->where('unified_comments.commentable_type', 'App\Models\Poem')
            ->where('poems.user_id', $user->id)
            ->whereBetween('unified_comments.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $videoComments = DB::table('unified_comments')
            ->join('videos', 'unified_comments.commentable_id', '=', 'videos.id')
            ->selectRaw('DATE(unified_comments.created_at) as date, COUNT(*) as count')
            ->where('unified_comments.commentable_type', 'App\Models\Video')
            ->where('videos.user_id', $user->id)
            ->whereBetween('unified_comments.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Merge and sum
        $allDates = array_unique(array_merge(array_keys($poemComments), array_keys($videoComments)));
        $result = [];

        foreach ($allDates as $date) {
            $result[$date] = ($poemComments[$date] ?? 0) + ($videoComments[$date] ?? 0);
        }

        return $result;
    }

    private function getTemporalLikesGiven($user, $startDate, $endDate)
    {
        $poemLikes = DB::table('unified_likes')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('user_id', $user->id)
            ->where('likeable_type', 'App\Models\Poem')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $videoLikes = DB::table('unified_likes')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('user_id', $user->id)
            ->where('likeable_type', 'App\Models\Video')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Merge and sum
        $allDates = array_unique(array_merge(array_keys($poemLikes), array_keys($videoLikes)));
        $result = [];

        foreach ($allDates as $date) {
            $result[$date] = ($poemLikes[$date] ?? 0) + ($videoLikes[$date] ?? 0);
        }

        return $result;
    }

    private function getTemporalCommentsGiven($user, $startDate, $endDate)
    {
        $poemComments = DB::table('unified_comments')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('user_id', $user->id)
            ->where('commentable_type', 'App\Models\Poem')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $videoComments = DB::table('unified_comments')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('user_id', $user->id)
            ->where('commentable_type', 'App\Models\Video')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Merge and sum
        $allDates = array_unique(array_merge(array_keys($poemComments), array_keys($videoComments)));
        $result = [];

        foreach ($allDates as $date) {
            $result[$date] = ($poemComments[$date] ?? 0) + ($videoComments[$date] ?? 0);
        }

        return $result;
    }

    /**
     * Get date range based on timeframe
     */
    private function getDateRange($timeframe)
    {
        $endDate = now();

        switch ($timeframe) {
            case '1_month':
                $startDate = $endDate->copy()->subMonth();
                break;
            case '3_months':
                $startDate = $endDate->copy()->subMonths(3);
                break;
            case '6_months':
                $startDate = $endDate->copy()->subMonths(6);
                break;
            case '1_year':
                $startDate = $endDate->copy()->subYear();
                break;
            case 'all_time':
                $startDate = $endDate->copy()->subYears(10); // Far back enough
                break;
            default: // 12_months
                $startDate = $endDate->copy()->subMonths(12);
                break;
        }

        return [$startDate, $endDate];
    }
}
