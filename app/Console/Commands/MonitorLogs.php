<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use Carbon\Carbon;

class MonitorLogs extends Command
{
    protected $signature = 'logs:monitor {--hours=24 : Hours to look back} {--level=error : Minimum log level} {--channel=all : Specific log channel}';
    protected $description = 'Monitor logs for errors and critical issues';

    public function handle()
    {
        $hours = $this->option('hours');
        $level = $this->option('level');
        $channel = $this->option('channel');

        $this->info("=== MONITORING LOGS (Last {$hours} hours) ===");

        // Monitor Laravel logs
        $this->monitorLaravelLogs($hours, $level, $channel);

        // Monitor Activity Logs
        $this->monitorActivityLogs($hours, $level);

        // Monitor specific log files
        $this->monitorLogFiles($hours, $level, $channel);

        $this->info("Log monitoring completed.");
    }

    private function monitorLaravelLogs($hours, $level, $channel)
    {
        $this->info("\n--- Laravel Logs ---");

        $logPath = storage_path('logs');
        $files = File::files($logPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                $this->analyzeLogFile($file->getPathname(), $hours, $level, $channel);
            }
        }
    }

    private function monitorActivityLogs($hours, $level)
    {
        $this->info("\n--- Activity Logs (Database) ---");

        $since = Carbon::now()->subHours($hours);

        $query = ActivityLog::where('created_at', '>=', $since);

        // Filter by level
        $levels = ['info', 'warning', 'error', 'critical'];
        $levelIndex = array_search($level, $levels);
        if ($levelIndex !== false) {
            $query->whereIn('level', array_slice($levels, $levelIndex));
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        if ($logs->isEmpty()) {
            $this->info("No activity logs found in the last {$hours} hours.");
            return;
        }

        $this->table(
            ['Time', 'Level', 'Category', 'Action', 'User', 'Description'],
            $logs->map(function ($log) {
                return [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $this->colorizeLevel($log->level),
                    $log->category,
                    $log->action,
                    $log->user_id ?? 'Guest',
                    substr($log->description, 0, 50) . '...',
                ];
            })->toArray()
        );

        // Summary
        $this->info("\nSummary:");
        $this->info("- Total logs: " . $logs->count());
        $this->info("- Errors: " . $logs->where('level', 'error')->count());
        $this->info("- Critical: " . $logs->where('level', 'critical')->count());
        $this->info("- Warnings: " . $logs->where('level', 'warning')->count());
    }

    private function monitorLogFiles($hours, $level, $channel)
    {
        $this->info("\n--- Specific Log Files ---");

        $logFiles = [
            'errors' => storage_path('logs/errors.log'),
            'security' => storage_path('logs/security.log'),
            'api' => storage_path('logs/api.log'),
            'user_activity' => storage_path('logs/user_activity.log'),
        ];

        foreach ($logFiles as $name => $path) {
            if ($channel !== 'all' && $channel !== $name) {
                continue;
            }

            if (File::exists($path)) {
                $this->info("\n--- {$name} Log ---");
                $this->analyzeLogFile($path, $hours, $level, $name);
            }
        }
    }

    private function analyzeLogFile($filePath, $hours, $level, $channel)
    {
        if (!File::exists($filePath)) {
            $this->warn("Log file not found: {$filePath}");
            return;
        }

        $content = File::get($filePath);
        $lines = explode("\n", $content);

        $since = Carbon::now()->subHours($hours);
        $errorCount = 0;
        $criticalCount = 0;
        $warningCount = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            // Check if line is within time range
            if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                $logTime = Carbon::parse($matches[1]);
                if ($logTime->lt($since)) continue;
            }

            // Count by level
            if (strpos($line, '.CRITICAL') !== false || strpos($line, 'CRITICAL') !== false) {
                $criticalCount++;
                if ($this->shouldShowLine($level, 'critical')) {
                    $this->error($line);
                }
            } elseif (strpos($line, '.ERROR') !== false || strpos($line, 'ERROR') !== false) {
                $errorCount++;
                if ($this->shouldShowLine($level, 'error')) {
                    $this->error($line);
                }
            } elseif (strpos($line, '.WARNING') !== false || strpos($line, 'WARNING') !== false) {
                $warningCount++;
                if ($this->shouldShowLine($level, 'warning')) {
                    $this->warn($line);
                }
            }
        }

        $this->info("Summary for {$channel}:");
        $this->info("- Critical: {$criticalCount}");
        $this->info("- Errors: {$errorCount}");
        $this->info("- Warnings: {$warningCount}");
    }

    private function shouldShowLine($minLevel, $lineLevel)
    {
        $levels = ['info', 'warning', 'error', 'critical'];
        $minIndex = array_search($minLevel, $levels);
        $lineIndex = array_search($lineLevel, $levels);

        return $lineIndex >= $minIndex;
    }

    private function colorizeLevel($level)
    {
        switch ($level) {
            case 'critical':
                return "<fg=red>{$level}</>";
            case 'error':
                return "<fg=red>{$level}</>";
            case 'warning':
                return "<fg=yellow>{$level}</>";
            case 'info':
                return "<fg=green>{$level}</>";
            default:
                return $level;
        }
    }
}
