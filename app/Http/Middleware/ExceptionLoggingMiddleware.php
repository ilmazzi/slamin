<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Log;

class ExceptionLoggingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (\Throwable $e) {
            // Log the exception with detailed information
            LoggingService::logError('unhandled_exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'exception_class' => get_class($e),
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'request_data' => $request->except(['password', 'password_confirmation', 'token', '_token']),
                'request_headers' => $request->headers->all(),
                'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'session_data' => $request->session()->all(),
            ]);
            
            // Also log to Laravel's default logging system for backup
            Log::error('Unhandled exception in ExceptionLoggingMiddleware', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_url' => $request->fullUrl(),
                'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
            ]);
            
            // Re-throw the exception so Laravel can handle it normally
            throw $e;
        }
    }
}
