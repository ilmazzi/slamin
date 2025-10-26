<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceDebugForAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is admin
        $user = auth()->user();
        
        if ($user) {
            try {
                $isAdmin = false;
                
                // Try hasRole method (Spatie)
                if (method_exists($user, 'hasRole')) {
                    $isAdmin = $user->hasRole('admin');
                }
                // Fallback: check roles relationship
                elseif (method_exists($user, 'roles')) {
                    $isAdmin = $user->roles()->where('name', 'admin')->exists();
                }
                
                // If admin, force debug mode for this request
                if ($isAdmin) {
                    config(['app.debug' => true]);
                }
            } catch (\Exception $e) {
                // Silently fail, don't break the request
            }
        }
        
        return $next($request);
    }
}
