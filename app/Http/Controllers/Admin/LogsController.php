<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

        /**
     * Mostra la dashboard principale dei log
     */
        public function index(Request $request)
    {
        try {
            $hours = $request->get('hours', 168); // Default: ultima settimana
            $level = $request->get('level', 'all'); // Default: tutti i livelli
            $channel = $request->get('channel', 'all');

            $stats = $this->getLogStats($hours);
            $recentLogs = $this->getRecentLogs($hours, $level, $channel);

            return view('admin.logs.index', compact('stats', 'recentLogs', 'hours', 'level', 'channel'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Mostra i log di attività (database)
     */
    public function activity(Request $request)
    {
        $hours = $request->get('hours', 24);
        $level = $request->get('level', 'all');
        $category = $request->get('category', 'all');
        $user = $request->get('user', 'all');

        $query = ActivityLog::with('user');

        // Filtro per tempo
        if ($hours > 0) {
            $since = Carbon::now()->subHours($hours);
            $query->where('created_at', '>=', $since);
        }

        // Filtro per livello
        if ($level !== 'all') {
            $query->where('level', $level);
        }

        // Filtro per categoria
        if ($category !== 'all') {
            $query->where('category', $category);
        }

        // Filtro per utente
        if ($user !== 'all') {
            $query->where('user_id', $user);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        $stats = $this->getActivityLogStats($hours);
        $categories = ActivityLog::distinct()->pluck('category')->sort();
        $users = ActivityLog::with('user')
            ->whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id')
            ->map(function($userId) {
                $log = ActivityLog::where('user_id', $userId)->first();
                return [
                    'id' => $userId,
                    'name' => $log->user->name ?? 'Unknown'
                ];
            })
            ->sortBy('name');

        return view('admin.logs.activity', compact('logs', 'stats', 'categories', 'users', 'hours', 'level', 'category', 'user'));
    }

    /**
     * Mostra i log di errore (file)
     */
    public function errors(Request $request)
    {
        $hours = $request->get('hours', 24);
        $level = $request->get('level', 'error');
        $file = $request->get('file', 'all');

        $logFiles = [
            'errors' => storage_path('logs/errors.log'),
            'laravel' => storage_path('logs/laravel.log'),
            'security' => storage_path('logs/security.log'),
            'api' => storage_path('logs/api.log'),
        ];

        $logs = [];
        $errorLogs = [];

        foreach ($logFiles as $name => $path) {
            if (File::exists($path)) {
                $fileLogs = $this->parseLogFile($path, $hours, $level);
                $logs[$name] = $fileLogs;

                // Se non è specificato un file o è quello selezionato, aggiungi i log
                if ($file === 'all' || $file === $name) {
                    $errorLogs = array_merge($errorLogs, $fileLogs);
                }
            }
        }

        $stats = $this->getErrorLogStats($hours);
        $stats['files_analyzed'] = count($logs);

        return view('admin.logs.errors', compact('errorLogs', 'stats', 'hours', 'level', 'file'));
    }

    /**
     * Mostra i dettagli di un log specifico
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        return view('admin.logs.show', compact('log'));
    }

    /**
     * Scarica i log come file
     */
    public function download(Request $request)
    {
        $type = $request->get('type', 'activity');
        $hours = $request->get('hours', 24);
        $level = $request->get('level', 'all');

        if ($type === 'activity') {
            return $this->downloadActivityLogs($hours, $level);
        } else {
            return $this->downloadFileLogs($type, $hours, $level);
        }
    }

    /**
     * Pulisce i log vecchi
     */
    public function clear(Request $request)
    {
        $days = $request->get('days', 30);
        $type = $request->get('type', 'all');
        $deleted = 0;

        if ($type === 'activity' || $type === 'all') {
            $deleted = ActivityLog::where('created_at', '<', Carbon::now()->subDays($days))->delete();
        }

        if ($type === 'files' || $type === 'all') {
            // Pulizia file log (implementazione base)
            $this->clearOldLogFiles($days);
        }

        return redirect()->back()->with('success', "Log puliti con successo. Eliminati {$deleted} record.");
    }

    /**
     * Ottiene statistiche dei log
     */
    private function getLogStats($hours)
    {
        $since = Carbon::now()->subHours($hours);

        return [
            'total_errors' => ActivityLog::where('level', 'error')->where('created_at', '>=', $since)->count(),
            'total_critical' => ActivityLog::where('level', 'critical')->where('created_at', '>=', $since)->count(),
            'total_warnings' => ActivityLog::where('level', 'warning')->where('created_at', '>=', $since)->count(),
            'total_info' => ActivityLog::where('level', 'info')->where('created_at', '>=', $since)->count(),
            'top_categories' => ActivityLog::where('created_at', '>=', $since)
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'top_actions' => ActivityLog::where('created_at', '>=', $since)
                ->select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Ottiene log recenti
     */
    private function getRecentLogs($hours, $level, $channel)
    {
        $since = Carbon::now()->subHours($hours);

        $query = ActivityLog::with('user')->where('created_at', '>=', $since);

        if ($level !== 'all') {
            $query->where('level', $level);
        }

        if ($channel !== 'all') {
            $query->where('category', $channel);
        }

        return $query->orderBy('created_at', 'desc')->limit(20)->get();
    }

    /**
     * Ottiene statistiche dei log di attività
     */
    private function getActivityLogStats($hours)
    {
        $since = Carbon::now()->subHours($hours);

        return [
            'total' => ActivityLog::where('created_at', '>=', $since)->count(),
            'by_level' => ActivityLog::where('created_at', '>=', $since)
                ->select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->get(),
            'by_category' => ActivityLog::where('created_at', '>=', $since)
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get(),
        ];
    }

    /**
     * Ottiene statistiche dei log di errore
     */
    private function getErrorLogStats($hours)
    {
        $since = Carbon::now()->subHours($hours);

        return [
            'total_errors' => ActivityLog::where('level', 'error')->where('created_at', '>=', $since)->count(),
            'total_critical' => ActivityLog::where('level', 'critical')->where('created_at', '>=', $since)->count(),
            'total_warnings' => ActivityLog::where('level', 'warning')->where('created_at', '>=', $since)->count(),
            'file_errors' => $this->countFileErrors($hours),
        ];
    }

    /**
     * Conta errori nei file
     */
    private function countFileErrors($hours)
    {
        $count = 0;
        $logFiles = [
            storage_path('logs/errors.log'),
            storage_path('logs/laravel.log'),
        ];

        foreach ($logFiles as $file) {
            if (File::exists($file)) {
                $content = File::get($file);
                $lines = explode("\n", $content);

                foreach ($lines as $line) {
                    if (strpos($line, '.ERROR') !== false || strpos($line, '.CRITICAL') !== false) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Parsa un file di log
     */
    private function parseLogFile($filePath, $hours, $level)
    {
        if (!File::exists($filePath)) {
            return [];
        }

        $content = File::get($filePath);
        $lines = explode("\n", $content);
        $since = Carbon::now()->subHours($hours);
        $parsed = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            // Estrai timestamp
            if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                $logTime = Carbon::parse($matches[1]);
                if ($logTime->lt($since)) continue;

                $parsed[] = [
                    'datetime' => $logTime->format('Y-m-d H:i:s'),
                    'level' => $this->extractLogLevel($line),
                    'message' => $this->extractLogMessage($line),
                    'file' => basename($filePath),
                    'full_line' => $line,
                ];
            }
        }

        // Filtra per livello
        if ($level !== 'all') {
            $parsed = array_filter($parsed, function($log) use ($level) {
                return $log['level'] === $level;
            });
        }

        return array_slice($parsed, -100); // Ultimi 100 log
    }

    /**
     * Estrae il livello del log
     */
    private function extractLogLevel($line)
    {
        if (strpos($line, '.CRITICAL') !== false) return 'critical';
        if (strpos($line, '.ERROR') !== false) return 'error';
        if (strpos($line, '.WARNING') !== false) return 'warning';
        if (strpos($line, '.INFO') !== false) return 'info';
        return 'unknown';
    }

    /**
     * Estrae il messaggio del log
     */
    private function extractLogMessage($line)
    {
        // Rimuovi timestamp e livello
        $message = preg_replace('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?\.(CRITICAL|ERROR|WARNING|INFO):\s*/', '', $line);
        return substr($message, 0, 200) . (strlen($message) > 200 ? '...' : '');
    }

    /**
     * Scarica log di attività
     */
    private function downloadActivityLogs($hours, $level)
    {
        $query = ActivityLog::with('user');

        if ($hours > 0) {
            $since = Carbon::now()->subHours($hours);
            $query->where('created_at', '>=', $since);
        }

        if ($level !== 'all') {
            $query->where('level', $level);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = "activity_logs_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Timestamp', 'Level', 'Category', 'Action', 'User', 'Description', 'IP', 'URL']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->level,
                    $log->category,
                    $log->action,
                    $log->user->name ?? 'Guest',
                    $log->description,
                    $log->ip_address,
                    $log->url,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Scarica log di file
     */
    private function downloadFileLogs($type, $hours, $level)
    {
        $logFiles = [
            'errors' => storage_path('logs/errors.log'),
            'laravel' => storage_path('logs/laravel.log'),
            'security' => storage_path('logs/security.log'),
            'api' => storage_path('logs/api.log'),
        ];

        if (!isset($logFiles[$type])) {
            abort(404);
        }

        $filePath = $logFiles[$type];
        if (!File::exists($filePath)) {
            abort(404);
        }

        $filename = "{$type}_logs_" . date('Y-m-d_H-i-s') . ".txt";

        return response()->download($filePath, $filename);
    }

    /**
     * Pulisce file log vecchi
     */
    private function clearOldLogFiles($days)
    {
        $logPath = storage_path('logs');
        $files = File::files($logPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                $fileTime = Carbon::createFromTimestamp($file->getMTime());
                if ($fileTime->lt(Carbon::now()->subDays($days))) {
                    File::delete($file->getPathname());
                }
            }
        }
    }
}
