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
            
            // Verifica se esiste una vista personalizzata per questo codice di errore
            $errorView = "errors.error_{$statusCode}";
            
            if (view()->exists($errorView)) {
                return response()->view($errorView, [], $statusCode);
            }
        }

        return $response;
    }
}
