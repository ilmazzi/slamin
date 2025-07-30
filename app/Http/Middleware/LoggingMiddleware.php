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

            // Add error details for error responses (4xx and 5xx)
            if ($statusCode >= 400) {
                $errorDetails = [];

                // Try to get error message from response content
                $content = $response->getContent();
                if ($content) {
                    $decoded = json_decode($content, true);
                    if ($decoded) {
                        // Handle JSON responses
                        if (isset($decoded['message'])) {
                            $errorDetails['error_message'] = $decoded['message'];
                            $errorDetails['error_body'] = $decoded['message'];
                        } elseif (isset($decoded['error'])) {
                            $errorDetails['error_message'] = $decoded['error'];
                            $errorDetails['error_body'] = $decoded['error'];
                        } elseif (isset($decoded['errors'])) {
                            $errorDetails['error_message'] = is_array($decoded['errors']) ? implode('; ', $decoded['errors']) : $decoded['errors'];
                            $errorDetails['error_body'] = $errorDetails['error_message'];
                        }
                    } elseif (is_string($content)) {
                        // Extract error message from HTML content
                        if (preg_match('/<title[^>]*>(.*?)<\/title>/i', $content, $matches)) {
                            $errorDetails['error_title'] = $matches[1];
                        }
                        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
                            $bodyContent = $this->cleanErrorContent($matches[1]);
                            
                            // Try to extract only the error message, not the entire body
                            if (preg_match('/error.*?:\s*(.*?)(?:\n|$)/i', $bodyContent, $errorMatches)) {
                                $errorDetails['error_body'] = trim($errorMatches[1]);
                            } elseif (preg_match('/exception.*?:\s*(.*?)(?:\n|$)/i', $bodyContent, $exceptionMatches)) {
                                $errorDetails['error_body'] = trim($exceptionMatches[1]);
                            } elseif (preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $content, $h1Matches)) {
                                $errorDetails['error_body'] = strip_tags($h1Matches[1]);
                            } elseif (preg_match('/<h2[^>]*>(.*?)<\/h2>/i', $content, $h2Matches)) {
                                $errorDetails['error_body'] = strip_tags($h2Matches[1]);
                            } else {
                                // If no specific error found, take only the first 200 characters
                                $errorDetails['error_body'] = substr($bodyContent, 0, 200);
                                if (strlen($bodyContent) > 200) {
                                    $errorDetails['error_body'] .= '...';
                                }
                            }
                        }
                    }
                }

                // Get exception details if available
                if (app()->bound('exception')) {
                    $exception = app('exception');
                    if ($exception) {
                        $errorDetails['exception_message'] = $exception->getMessage();
                        $errorDetails['exception_file'] = $exception->getFile();
                        $errorDetails['exception_line'] = $exception->getLine();
                        $errorDetails['exception_trace'] = $exception->getTraceAsString();
                        
                        // If we have exception details, use them as the primary error message
                        if (!isset($errorDetails['error_body']) || empty($errorDetails['error_body']) || $errorDetails['error_body'] === '500') {
                            $errorDetails['error_body'] = $exception->getMessage();
                        }
                    }
                }
                
                // If we still don't have a meaningful error message, try to get it from Laravel's error handler
                if (!isset($errorDetails['error_body']) || empty($errorDetails['error_body']) || $errorDetails['error_body'] === '500') {
                    // Try to get error from session flash data
                    if ($request->session()->has('errors')) {
                        $errors = $request->session()->get('errors');
                        if ($errors && method_exists($errors, 'all')) {
                            $errorMessages = $errors->all();
                            if (!empty($errorMessages)) {
                                $errorDetails['error_body'] = implode('; ', $errorMessages);
                            }
                        }
                    }
                    
                    // Try to get error from session
                    if ($request->session()->has('error')) {
                        $errorDetails['error_body'] = $request->session()->get('error');
                    }
                    
                    // Try to get validation errors from request
                    if ($request->has('errors')) {
                        $requestErrors = $request->get('errors');
                        if (is_array($requestErrors)) {
                            $errorDetails['error_body'] = implode('; ', $requestErrors);
                        }
                    }
                    
                    // Check for database-related errors in the exception
                    if (isset($errorDetails['exception_message'])) {
                        $exceptionMsg = $errorDetails['exception_message'];
                        if (str_contains($exceptionMsg, 'SQLSTATE') || 
                            str_contains($exceptionMsg, 'database') || 
                            str_contains($exceptionMsg, 'table') ||
                            str_contains($exceptionMsg, 'column')) {
                            $errorDetails['error_body'] = "Database error: " . $exceptionMsg;
                        }
                    }
                    
                    // Try to get error from Laravel's log file (last few lines)
                    if (!isset($errorDetails['error_body']) || empty($errorDetails['error_body']) || $errorDetails['error_body'] === '500') {
                        $logFile = storage_path('logs/laravel.log');
                        if (file_exists($logFile)) {
                            $logLines = file($logFile);
                            $lastLines = array_slice($logLines, -10); // Last 10 lines
                            foreach (array_reverse($lastLines) as $line) {
                                if (str_contains($line, 'ERROR') || str_contains($line, 'Exception')) {
                                    // Extract error message from log line
                                    if (preg_match('/\[.*?\] (.*?): (.*?)(?:\n|$)/', $line, $matches)) {
                                        $errorDetails['error_body'] = trim($matches[2]);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    
                    // If still no meaningful error, provide a generic but helpful message
                    if (!isset($errorDetails['error_body']) || empty($errorDetails['error_body']) || $errorDetails['error_body'] === '500') {
                        $errorDetails['error_body'] = "Server error occurred while processing {$request->method()} request to {$request->path()}";
                    }
                }

                $details['error_details'] = $errorDetails;
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

    /**
     * Clean error content by removing JavaScript, CSS, and excessive HTML
     */
    private function cleanErrorContent(string $content): string
    {
        // Remove script tags and their content
        $content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $content);
        
        // Remove style tags and their content
        $content = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $content);
        
        // Remove other potentially large HTML elements
        $content = preg_replace('/<nav[^>]*>.*?<\/nav>/is', '', $content);
        $content = preg_replace('/<footer[^>]*>.*?<\/footer>/is', '', $content);
        $content = preg_replace('/<header[^>]*>.*?<\/header>/is', '', $content);
        
        // Remove excessive whitespace and newlines
        $content = preg_replace('/\s+/', ' ', $content);
        
        // Strip HTML tags
        $content = strip_tags($content);
        
        // Trim and clean up
        $content = trim($content);
        
        return $content;
    }
}
