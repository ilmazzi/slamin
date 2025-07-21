<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Accesso negato. Devi essere autenticato.');
        }

        if (!Auth::user()->hasRole(['admin', 'moderator'])) {
            abort(403, 'Accesso negato. Solo amministratori e moderatori possono gestire i permessi.');
        }

        return $next($request);
    }
}
