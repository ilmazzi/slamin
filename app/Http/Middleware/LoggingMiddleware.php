<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\LoggingService;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class LoggingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // Process the request
        $response = $next($request);

        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000); // Convert to milliseconds

        // Only log certain types of requests to avoid spam
        if ($this->shouldLogRequest($request)) {
            $this->logRequest($request, $response, $responseTime);
        }

        return $response;
    }

    /**
     * Determine if the request should be logged
     */
    private function shouldLogRequest(Request $request): bool
    {
        // Skip logging for certain paths to avoid spam
        $skipPaths = [
            '/css/',
            '/js/',
            '/images/',
            '/fonts/',
            '/favicon.ico',
            '/_debugbar/',
            '/storage/',
            '/vendor/',
        ];

        $path = $request->path();
        foreach ($skipPaths as $skipPath) {
            if (str_starts_with($path, trim($skipPath, '/'))) {
                return false;
            }
        }

        // Skip GET requests to static assets
        if ($request->isMethod('GET') && $this->isStaticAsset($request)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the request is for a static asset
     */
    private function isStaticAsset(Request $request): bool
    {
        $path = $request->path();
        $staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];

        foreach ($staticExtensions as $extension) {
            if (str_ends_with($path, '.' . $extension)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the request details
     */
    private function logRequest(Request $request, Response $response, int $responseTime): void
    {
        try {
            $statusCode = $response->getStatusCode();
            $method = $request->method();
            $path = $request->path();

            // Determine category based on path
            $category = $this->determineCategory($path);
            
            // Determine action based on method and path
            $action = $this->determineAction($method, $path);
            
            // Determine level based on status code
            $level = $this->determineLevel($statusCode);

            // Create description
            $description = $this->createDescription($method, $path, $statusCode, $responseTime);

            // Prepare details
            $details = [
                'method' => $method,
                'path' => $path,
                'status_code' => $statusCode,
                'response_time_ms' => $responseTime,
                'query_params' => $request->query(),
                'request_size' => $request->header('content-length'),
                'user_agent' => $request->userAgent(),
            ];

            // Add request data for non-GET requests (excluding sensitive data)
            if (!$request->isMethod('GET')) {
                $requestData = $request->except([
                    'password',
                    'password_confirmation',
                    'current_password',
                    'new_password',
                    'token',
                    '_token',
                ]);
                $details['request_data'] = $requestData;
            }

            // Log the activity
            LoggingService::log(
                $action,
                $category,
                $description,
                $details,
                $level
            );

        } catch (\Exception $e) {
            // If logging fails, don't break the application
            // Just log to Laravel's default logger
            Log::error('Failed to log request in middleware', [
                'error' => $e->getMessage(),
                'path' => $request->path(),
                'method' => $request->method(),
            ]);
        }
    }

    /**
     * Determine the category based on the request path
     */
    private function determineCategory(string $path): string
    {
        if (str_starts_with($path, 'admin')) {
            return ActivityLog::CATEGORY_ADMIN;
        }
        
        if (str_starts_with($path, 'auth') || str_starts_with($path, 'login') || str_starts_with($path, 'register')) {
            return ActivityLog::CATEGORY_AUTH;
        }
        
        if (str_starts_with($path, 'events')) {
            return ActivityLog::CATEGORY_EVENTS;
        }
        
        if (str_starts_with($path, 'videos')) {
            return ActivityLog::CATEGORY_VIDEOS;
        }
        
        if (str_starts_with($path, 'profile')) {
            return ActivityLog::CATEGORY_USERS;
        }
        
        if (str_starts_with($path, 'premium')) {
            return ActivityLog::CATEGORY_PREMIUM;
        }
        
        if (str_starts_with($path, 'permissions')) {
            return ActivityLog::CATEGORY_PERMISSIONS;
        }
        
        if (str_starts_with($path, 'media')) {
            return ActivityLog::CATEGORY_MEDIA;
        }
        
        if (str_starts_with($path, 'dashboard')) {
            return ActivityLog::CATEGORY_ADMIN;
        }

        return ActivityLog::CATEGORY_SYSTEM;
    }

    /**
     * Determine the action based on method and path
     */
    private function determineAction(string $method, string $path): string
    {
        $action = strtolower($method);
        
        // Add more specific actions based on path patterns
        if (str_contains($path, 'create')) {
            $action .= '.create';
        } elseif (str_contains($path, 'edit') || str_contains($path, 'update')) {
            $action .= '.update';
        } elseif (str_contains($path, 'delete')) {
            $action .= '.delete';
        } elseif (str_contains($path, 'upload')) {
            $action .= '.upload';
        } elseif (str_contains($path, 'download')) {
            $action .= '.download';
        } elseif (str_contains($path, 'like')) {
            $action .= '.like';
        } elseif (str_contains($path, 'comment')) {
            $action .= '.comment';
        } else {
            $action .= '.access';
        }

        return $action;
    }

    /**
     * Determine the log level based on status code
     */
    private function determineLevel(int $statusCode): string
    {
        if ($statusCode >= 500) {
            return ActivityLog::LEVEL_ERROR;
        }
        
        if ($statusCode >= 400) {
            return ActivityLog::LEVEL_WARNING;
        }
        
        return ActivityLog::LEVEL_INFO;
    }

    /**
     * Create a human-readable description
     */
    private function createDescription(string $method, string $path, int $statusCode, int $responseTime): string
    {
        $methodText = strtoupper($method);
        $pathText = $path === '/' ? 'homepage' : $path;
        
        $statusText = match(true) {
            $statusCode >= 500 => 'Server Error',
            $statusCode >= 400 => 'Client Error',
            $statusCode >= 300 => 'Redirect',
            $statusCode >= 200 => 'Success',
            default => 'Unknown',
        };

        return "{$methodText} request to {$pathText} - {$statusText} ({$statusCode}) - {$responseTime}ms";
    }
} 