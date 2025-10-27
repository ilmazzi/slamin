<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Providers\LanguageServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetTimezone::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\ForceDebugForAdmin::class, // MUST be before CustomErrorPages
            \App\Http\Middleware\ExceptionLoggingMiddleware::class,
            // \App\Http\Middleware\LoggingMiddleware::class, // Temporaneamente disabilitato per debug
            \App\Http\Middleware\CustomErrorPages::class,
            \App\Http\Middleware\UpdateUserActivity::class,

        ]);

        // Register custom middleware aliases
        $middleware->alias([
            'admin.access' => \App\Http\Middleware\AdminAccess::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // 'logging' => \App\Http\Middleware\LoggingMiddleware::class, // Temporaneamente disabilitato per debug
            // Chat middleware
            'belongsToConversation' => \App\Http\Middleware\Chat\BelongsToConversation::class,
            // Spatie Permission middleware
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Log all exceptions for better debugging
        $exceptions->reportable(function (\Throwable $e) {
            try {
                // Log to our custom logging service
                \App\Services\LoggingService::logError('unhandled_exception', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'request_url' => request()->fullUrl(),
                    'request_method' => request()->method(),
                    'user_id' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->id : null,
                ]);
            } catch (\Throwable $loggingException) {
                // Fallback to basic logging if our custom logging fails
                \Illuminate\Support\Facades\Log::error('Exception logging failed', [
                    'original_exception' => $e->getMessage(),
                    'logging_exception' => $loggingException->getMessage(),
                ]);
            }
        });
    })
    ->withProviders([
        LanguageServiceProvider::class,
        \Barryvdh\TranslationManager\ManagerServiceProvider::class,
    ])
    ->create();
