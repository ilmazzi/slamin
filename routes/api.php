<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route di test per toggle-featured (bypassa middleware web)
Route::post('/test-toggle-featured/{article}', function($article) {
    try {
        // Bypassa completamente il controller e fa la logica direttamente
        $articleModel = \App\Models\Article::findOrFail($article);
        $featured = request()->input('featured', !$articleModel->featured);

        // Check limite featured
        if ($featured && !$articleModel->featured) {
            $currentFeaturedCount = \App\Models\Article::where('featured', true)->count();
            if ($currentFeaturedCount >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Limite massimo di 3 articoli featured raggiunto.'
                ], 400);
            }
        }

        $articleModel->update(['featured' => $featured]);

        $message = $featured ? 'Articolo aggiunto ai featured con successo!' : 'Articolo rimosso dai featured con successo!';

        return response()->json([
            'success' => true,
            'message' => $message,
            'featured' => $featured,
            'debug' => 'Route API funziona!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Errore: ' . $e->getMessage(),
            'debug' => 'Errore nella route API'
        ], 500);
    }
})->name('api.test.toggle-featured');

