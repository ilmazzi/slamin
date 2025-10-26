<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log all exceptions in production
            if (app()->environment('production')) {
                $this->logException($e);
            }
        });
    }

    /**
     * Log exception with detailed information
     */
    protected function logException(Throwable $e): void
    {
        try {
            $request = request();
            $user = \Illuminate\Support\Facades\Auth::user();

            $details = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'exception_class' => get_class($e),
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'request_data' => $request->except(['password', 'password_confirmation', 'token', '_token']),
                'user_id' => $user?->id,
                'user_email' => $user?->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => $request->session()->getId(),
            ];

            // Determine log level based on exception type
            $logLevel = 'error';
            if ($e instanceof \Illuminate\Database\QueryException) {
                $logLevel = 'critical';
                LoggingService::logCritical('database_error', $details);
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                $logLevel = 'warning';
                LoggingService::logError('validation_failed', $details);
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $logLevel = 'info';
                LoggingService::logError('not_found', $details);
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
                $logLevel = 'warning';
                LoggingService::logSecurity('permission_violation', $details);
            } else {
                LoggingService::logError('unhandled_exception', $details);
            }

            // Also log to Laravel's default logging system
            Log::channel('errors')->{$logLevel}('Exception caught by global handler', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $user?->id,
                'url' => $request->fullUrl(),
            ]);

        } catch (\Exception $loggingException) {
            // Fallback logging if our logging fails
            Log::error('Failed to log exception', [
                'original_exception' => $e->getMessage(),
                'logging_exception' => $loggingException->getMessage(),
            ]);
        }
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Check if user is admin (with multiple fallbacks)
        $isAdmin = false;
        
        try {
            $user = \Illuminate\Support\Facades\Auth::user();
            
            if ($user) {
                // Try hasRole method (Spatie)
                if (method_exists($user, 'hasRole')) {
                    $isAdmin = $user->hasRole('admin');
                }
                // Fallback: check roles relationship
                elseif (method_exists($user, 'roles')) {
                    $isAdmin = $user->roles()->where('name', 'admin')->exists();
                }
                // Fallback: check is_admin column
                elseif (isset($user->is_admin)) {
                    $isAdmin = (bool) $user->is_admin;
                }
            }
        } catch (\Exception $authException) {
            // If auth check fails, assume not admin
            $isAdmin = false;
        }
        
        // Debug log
        Log::info('Exception Handler Debug', [
            'environment' => app()->environment(),
            'is_admin' => $isAdmin,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'will_show_details' => !app()->environment('production') || $isAdmin
        ]);
        
        // In production, don't show detailed error information (unless admin)
        if (app()->environment('production') && !$isAdmin) {
            // Log the exception before rendering
            $this->logException($e);

            // Return a generic error response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Internal Server Error',
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);
            }

            // For web requests, show a generic error page
            return response()->view('errors.500', [], 500);
        }

        // For admin or non-production, show detailed error
        return parent::render($request, $e);
    }
}
