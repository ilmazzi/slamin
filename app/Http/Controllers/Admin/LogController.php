<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\LoggingService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display the logs index page
     */
    public function index(Request $request)
    {
        // Log that admin is viewing logs
        LoggingService::logAdmin('log_view', [
            'filters' => $request->all()
        ]);

        $query = ActivityLog::with('user');

        // Apply filters
        $query = $this->applyFilters($query, $request);

        // Get paginated results
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get statistics
        $stats = $this->getStats($request);

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        return view('admin.logs.index', compact('logs', 'stats', 'filterOptions'));
    }

    /**
     * Show a specific log entry
     */
    public function show(ActivityLog $log)
    {
        LoggingService::logAdmin('log_detail_view', [
            'log_id' => $log->id,
            'log_action' => $log->action,
            'log_category' => $log->category,
        ]);

        return view('admin.logs.show', compact('log'));
    }

    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        LoggingService::logAdmin('log_export', [
            'filters' => $request->all()
        ]);

        $query = ActivityLog::with('user');
        $query = $this->applyFilters($query, $request);

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Date',
                'User',
                'Action',
                'Category',
                'Description',
                'Level',
                'IP Address',
                'User Agent',
                'URL',
                'Method',
                'Status Code',
                'Response Time (ms)',
                'Details'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'Guest',
                    $log->action,
                    $log->category,
                    $log->description,
                    $log->level,
                    $log->ip_address,
                    $log->user_agent,
                    $log->url,
                    $log->method,
                    $log->status_code,
                    $log->response_time,
                    json_encode($log->details)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get logs statistics
     */
    public function stats(Request $request)
    {
        $stats = $this->getStats($request);

        return response()->json($stats);
    }

    /**
     * Clear old logs
     */
    public function clear(Request $request)
    {
        $days = $request->input('days', 30);
        $category = $request->input('category');
        $level = $request->input('level');

        $query = ActivityLog::where('created_at', '<', now()->subDays($days));

        if ($category) {
            $query->where('category', $category);
        }

        if ($level) {
            $query->where('level', $level);
        }

        $deletedCount = $query->count();
        $query->delete();

        LoggingService::logAdmin('log_clear', [
            'deleted_count' => $deletedCount,
            'days' => $days,
            'category' => $category,
            'level' => $level,
        ]);

        return redirect()->route('admin.logs.index')
            ->with('success', "Successfully deleted {$deletedCount} log entries older than {$days} days.");
    }

    /**
     * Get real-time logs (for AJAX requests)
     */
    public function realtime(Request $request)
    {
        $lastId = $request->input('last_id', 0);
        $limit = $request->input('limit', 10);

        $logs = ActivityLog::with('user')
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'logs' => $logs,
            'last_id' => $logs->max('id') ?? $lastId,
            'count' => $logs->count(),
        ]);
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request)
    {
        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Level filter
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Action filter
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Status code filter
        if ($request->filled('status_code')) {
            $query->where('status_code', $request->status_code);
        }

        return $query;
    }

    /**
     * Get statistics for the logs
     */
    private function getStats(Request $request)
    {
        $baseQuery = ActivityLog::query();
        $baseQuery = $this->applyFilters($baseQuery, $request);

        $totalLogs = $baseQuery->count();
        $todayLogs = $baseQuery->whereDate('created_at', today())->count();
        $thisWeekLogs = $baseQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // Level distribution - create fresh query
        $levelQuery = ActivityLog::query();
        $levelQuery = $this->applyFilters($levelQuery, $request);
        $levelStats = $levelQuery->select('level', DB::raw('count(*) as count'))
            ->groupBy('level')
            ->pluck('count', 'level')
            ->toArray();

        // Category distribution - create fresh query
        $categoryQuery = ActivityLog::query();
        $categoryQuery = $this->applyFilters($categoryQuery, $request);
        $categoryStats = $categoryQuery->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Top users - create fresh query
        $topUsersQuery = ActivityLog::query();
        $topUsersQuery = $this->applyFilters($topUsersQuery, $request);
        $topUsers = $topUsersQuery->select('user_id', DB::raw('count(*) as count'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->with('user')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Average response time - create fresh query
        $responseTimeQuery = ActivityLog::query();
        $responseTimeQuery = $this->applyFilters($responseTimeQuery, $request);
        $avgResponseTime = $responseTimeQuery->whereNotNull('response_time')
            ->avg('response_time');

        // Error rate - create fresh query
        $errorQuery = ActivityLog::query();
        $errorQuery = $this->applyFilters($errorQuery, $request);
        $errorCount = $errorQuery->whereIn('level', ['error', 'critical'])->count();
        $errorRate = $totalLogs > 0 ? round(($errorCount / $totalLogs) * 100, 2) : 0;

        return [
            'total_logs' => $totalLogs,
            'today_logs' => $todayLogs,
            'this_week_logs' => $thisWeekLogs,
            'level_stats' => $levelStats,
            'category_stats' => $categoryStats,
            'top_users' => $topUsers,
            'avg_response_time' => round($avgResponseTime ?? 0, 2),
            'error_rate' => $errorRate,
            'error_count' => $errorCount,
        ];
    }

    /**
     * Get filter options for the form
     */
    private function getFilterOptions()
    {
        return [
            'categories' => ActivityLog::getCategories(),
            'levels' => ActivityLog::getLevels(),
            'users' => User::orderBy('name')->pluck('name', 'id'),
            'status_codes' => [
                200 => '200 - OK',
                201 => '201 - Created',
                301 => '301 - Moved Permanently',
                302 => '302 - Found',
                400 => '400 - Bad Request',
                401 => '401 - Unauthorized',
                403 => '403 - Forbidden',
                404 => '404 - Not Found',
                422 => '422 - Unprocessable Entity',
                500 => '500 - Internal Server Error',
                502 => '502 - Bad Gateway',
                503 => '503 - Service Unavailable',
            ],
        ];
    }
} 