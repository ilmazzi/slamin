<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Log;

class PerformanceLoggingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = $endMemory - $startMemory;
        $peakMemory = memory_get_peak_usage(true);

        // Log slow requests (> 1000ms)
        if ($executionTime > 1000) {
            LoggingService::logError('slow_request', [
                'execution_time_ms' => round($executionTime, 2),
                'memory_usage_bytes' => $memoryUsage,
                'peak_memory_bytes' => $peakMemory,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
                'response_status' => $response->getStatusCode(),
            ]);

            Log::channel('errors')->warning('Slow request detected', [
                'execution_time_ms' => round($executionTime, 2),
                'url' => $request->fullUrl(),
                'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
            ]);
        }

        // Log very slow requests (> 5000ms) as critical
        if ($executionTime > 5000) {
            LoggingService::logCritical('very_slow_request', [
                'execution_time_ms' => round($executionTime, 2),
                'memory_usage_bytes' => $memoryUsage,
                'peak_memory_bytes' => $peakMemory,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
                'response_status' => $response->getStatusCode(),
            ]);
        }

        // Log high memory usage (> 50MB)
        if ($peakMemory > 50 * 1024 * 1024) {
            LoggingService::logError('high_memory_usage', [
                'peak_memory_mb' => round($peakMemory / 1024 / 1024, 2),
                'execution_time_ms' => round($executionTime, 2),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
            ]);
        }

        // Add performance headers for debugging
        if (app()->environment('local')) {
            $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
            $response->headers->set('X-Memory-Usage', round($memoryUsage / 1024 / 1024, 2) . 'MB');
            $response->headers->set('X-Peak-Memory', round($peakMemory / 1024 / 1024, 2) . 'MB');
        }

        return $response;
    }
}
