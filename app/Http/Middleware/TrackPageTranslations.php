<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\TranslationTracker;

class TrackPageTranslations
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Clear previous page translations
        TranslationTracker::clear();
        
        // Override the __ function to track usage
        if (auth()->check() && auth()->user()->hasRole('admin')) {
            // Store original translator
            $originalTranslator = app('translator');
            
            // Wrap the get method to track translations
            app()->singleton('translator', function ($app) use ($originalTranslator) {
                return new class($originalTranslator) extends \Illuminate\Translation\Translator {
                    protected $wrapped;
                    
                    public function __construct($wrapped)
                    {
                        $this->wrapped = $wrapped;
                        parent::__construct($wrapped->getLoader(), $wrapped->getLocale());
                    }
                    
                    public function get($key, array $replace = [], $locale = null, $fallback = true)
                    {
                        // Track the translation key
                        TranslationTracker::track($key);
                        
                        // Call original method
                        return $this->wrapped->get($key, $replace, $locale, $fallback);
                    }
                    
                    // Proxy other methods
                    public function __call($method, $parameters)
                    {
                        return $this->wrapped->$method(...$parameters);
                    }
                };
            });
        }
        
        return $next($request);
    }
}
