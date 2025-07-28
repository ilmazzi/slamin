<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display the activity logs
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filtri
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Paginazione
        $logs = $query->paginate(50);

        // Statistiche
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week_logs' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_logs' => ActivityLog::whereMonth('created_at', now()->month)->count(),
            'error_logs' => ActivityLog::where('level', ActivityLog::LEVEL_ERROR)->count(),
            'warning_logs' => ActivityLog::where('level', ActivityLog::LEVEL_WARNING)->count(),
        ];

        // Filtri disponibili
        $categories = ActivityLog::getCategories();
        $levels = ActivityLog::getLevels();
        $users = User::orderBy('name')->get();

        // Top actions
        $topActions = ActivityLog::select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Top users
        $topUsers = ActivityLog::select('user_id', DB::raw('count(*) as count'))
            ->with('user')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.logs.index', compact(
            'logs',
            'stats',
            'categories',
            'levels',
            'users',
            'topActions',
            'topUsers'
        ));
    }

    /**
     * Get logs for AJAX requests
     */
    public function getLogs(Request $request): JsonResponse
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Applica filtri
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        return response()->json([
            'success' => true,
            'logs' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }

    /**
     * Get log details
     */
    public function show(ActivityLog $log): JsonResponse
    {
        $log->load('user');

        return response()->json([
            'success' => true,
            'log' => $log
        ]);
    }

    /**
     * Export logs
     */
    public function export(Request $request): JsonResponse
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Applica filtri
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        // Prepara i dati per l'export
        $exportData = $logs->map(function ($log) {
            return [
                'ID' => $log->id,
                'Data' => $log->created_at->format('d/m/Y H:i:s'),
                'Utente' => $log->user ? $log->user->name : 'Sistema',
                'Email' => $log->user ? $log->user->email : '-',
                'Azione' => $log->action,
                'Categoria' => $log->category,
                'Livello' => $log->level,
                'Descrizione' => $log->description,
                'Dettagli' => json_encode($log->details, JSON_UNESCAPED_UNICODE),
                'IP' => $log->ip_address,
                'URL' => $log->url,
                'Metodo' => $log->method,
                'Status Code' => $log->status_code,
                'Tempo Risposta' => $log->response_time ? $log->response_time . 'ms' : '-',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $exportData,
            'filename' => 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv'
        ]);
    }

    /**
     * Clear old logs
     */
    public function clearOldLogs(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $date = now()->subDays($days);

        $deletedCount = ActivityLog::where('created_at', '<', $date)->delete();

        return response()->json([
            'success' => true,
            'message' => "Eliminati {$deletedCount} log più vecchi di {$days} giorni",
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getStats(): JsonResponse
    {
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week_logs' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_logs' => ActivityLog::whereMonth('created_at', now()->month)->count(),
            'error_logs' => ActivityLog::where('level', ActivityLog::LEVEL_ERROR)->count(),
            'warning_logs' => ActivityLog::where('level', ActivityLog::LEVEL_WARNING)->count(),
        ];

        // Log per categoria (ultimi 7 giorni)
        $categoryStats = ActivityLog::select('category', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        // Log per livello (ultimi 7 giorni)
        $levelStats = ActivityLog::select('level', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('level')
            ->orderBy('count', 'desc')
            ->get();

        // Log per giorno (ultimi 30 giorni)
        $dailyStats = ActivityLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'category_stats' => $categoryStats,
            'level_stats' => $levelStats,
            'daily_stats' => $dailyStats
        ]);
    }
}
