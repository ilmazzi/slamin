<?php

namespace App\Providers;

use App\Models\Video;
use App\Observers\VideoObserver;
use App\Services\LoggingService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registra l'observer per i video
        Video::observe(VideoObserver::class);

        // Registra un handler globale per le eccezioni non gestite
        $this->registerGlobalExceptionHandler();
    }

    /**
     * Registra un handler globale per le eccezioni
     */
    private function registerGlobalExceptionHandler(): void
    {
        // Handler per eccezioni non gestite
        set_exception_handler(function ($exception) {
            try {
                LoggingService::logError('global_exception', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                    'exception_class' => get_class($exception),
                    'request_url' => request()->fullUrl() ?? 'CLI',
                    'request_method' => request()->method() ?? 'CLI',
                    'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
                ]);

                // Backup log
                Log::error('Global exception handler caught error', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]);
            } catch (\Exception $e) {
                // Fallback se il logging fallisce
                Log::error('Failed to log exception in global handler', [
                    'original_error' => $exception->getMessage(),
                    'logging_error' => $e->getMessage(),
                ]);
            }
        });

        // Handler per errori fatali
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                try {
                    LoggingService::logError('fatal_error', [
                        'message' => $error['message'],
                        'file' => $error['file'],
                        'line' => $error['line'],
                        'type' => $error['type'],
                        'request_url' => request()->fullUrl() ?? 'CLI',
                        'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
                    ]);

                    Log::error('Fatal error occurred', $error);
                } catch (\Exception $e) {
                    Log::error('Failed to log fatal error', [
                        'original_error' => $error,
                        'logging_error' => $e->getMessage(),
                    ]);
                }
            }
        });
    }
}
