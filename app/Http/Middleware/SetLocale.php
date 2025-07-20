<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Handle language switch if requested via URL parameter
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if (in_array($locale, ['it', 'en', 'fr', 'es', 'de'])) {
                session(['locale' => $locale]);

                // Redirect to clean URL without lang parameter
                return redirect()->to($request->url())->with('language_switched', true);
            }
        }

        // Check for language in session or use default
        $locale = session('locale', config('app.locale'));

        // Validate locale
        if (in_array($locale, ['it', 'en', 'fr', 'es', 'de'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
