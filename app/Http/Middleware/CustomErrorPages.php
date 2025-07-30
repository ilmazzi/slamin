<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomErrorPages
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Se la risposta è un errore HTTP, personalizziamo la vista
        if ($response->getStatusCode() >= 400) {
            $statusCode = $response->getStatusCode();
            
            // Log dell'errore HTTP con dettagli
            \App\Services\LoggingService::logError('http_error', [
                'status_code' => $statusCode,
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'request_data' => $request->except(['password', 'password_confirmation', 'token', '_token']),
                'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'response_content' => $response->getContent(),
            ]);
            
            // Verifica se esiste una vista personalizzata per questo codice di errore
            $errorView = "errors.error_{$statusCode}";
            
            if (view()->exists($errorView)) {
                return response()->view($errorView, [], $statusCode);
            }
        }

        return $response;
    }
}
