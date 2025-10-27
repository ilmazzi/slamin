<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleTranslationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:articles.manage_news');
    }

    /**
     * Display a listing of articles that can be translated
     */
    public function index()
    {
        $articles = Article::with(['user', 'category', 'translations'])
            ->published()
            ->whereNotNull('id') // Ensure article exists
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Inizializza translation_status per articoli che non ce l'hanno
        foreach ($articles as $article) {
            if (!$article->translation_status) {
                $article->translation_status = [
                    'it' => 'pending',
                    'en' => 'pending',
                    'fr' => 'pending',
                    'es' => 'pending',
                    'de' => 'pending',
                    'pt' => 'pending',
                    'ru' => 'pending'
                ];
                $article->save();
            }
        }

        return view('admin.articles.translations.index', compact('articles'));
    }

    /**
     * Show the form for creating translations for a specific article
     */
    public function create(Article $article)
    {
        $supportedLanguages = ['it', 'en', 'fr', 'es', 'de', 'pt', 'ru'];

        // Mostriamo tutte le lingue tranne quella originale
        $originalLanguage = $article->original_language ?? 'it';
        $translationLanguages = array_diff($supportedLanguages, [$originalLanguage]);

        // DEBUG: Log per vedere cosa stiamo passando
        \Log::info('Translation Languages Debug:', [
            'original' => $originalLanguage,
            'supported' => $supportedLanguages,
            'translationLanguages' => $translationLanguages,
            'type' => gettype($translationLanguages),
            'is_array' => is_array($translationLanguages),
            'count' => count($translationLanguages)
        ]);

        return view('admin.articles.translations.create', compact('article', 'translationLanguages'));
    }

    /**
     * Store a newly created translation
     */
    public function store(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required|string|in:it,en,fr,es,de,pt,ru',
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'translation_type' => 'required|in:manual',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['article_id'] = $article->id;
        $data['translator_id'] = Auth::id();
        $data['translated_at'] = now();
        $data['translation_type'] = 'manual'; // Force manual translation

        // Controlla se esiste già una traduzione per questa lingua
        $existingTranslation = ArticleTranslation::where('article_id', $article->id)
            ->where('language', $data['language'])
            ->first();

        if ($existingTranslation) {
            // Aggiorna la traduzione esistente
            $existingTranslation->update($data);
            $translation = $existingTranslation;
        } else {
            // Crea una nuova traduzione
            $translation = ArticleTranslation::create($data);
        }

        // Update translation status in the article
        $this->updateArticleTranslationStatus($article, $data['language'], 'completed');

        return redirect()->route('admin.articles.translations.index')
            ->with('success', "Traduzione in {$data['language']} creata con successo!");
    }

    /**
     * Show the form for editing a translation
     */
    public function edit(Article $article, ArticleTranslation $translation)
    {
        return view('admin.articles.translations.edit', compact('article', 'translation'));
    }

    /**
     * Update a translation
     */
    public function update(Request $request, Article $article, ArticleTranslation $translation)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $translation->update($validator->validated());

        return redirect()->route('admin.articles.translations.index')
            ->with('success', 'Traduzione aggiornata con successo!');
    }

    /**
     * REMOVED: Automatic translation functionality disabled
     */
    public function generateTranslation(Request $request, Article $article)
    {
        return response()->json([
            'success' => false,
            'message' => 'Traduzione automatica disabilitata. Le traduzioni devono essere create manualmente.'
        ], 403);
    }

    /**
     * REMOVED: Bulk automatic translation functionality disabled
     */
    public function generateAllTranslations($articleId)
    {
        return response()->json([
            'success' => false,
            'message' => 'Traduzione automatica disabilitata. Le traduzioni devono essere create manualmente.'
        ], 403);
    }

    /**
     * Remove a translation
     */
    public function destroy(Article $article, ArticleTranslation $translation)
    {
        $language = $translation->language;
        $translation->delete();

        // Update translation status
        $translationStatus = $article->translation_status ?? [];
        $translationStatus[$language] = 'pending';
        $article->update(['translation_status' => $translationStatus]);

        return redirect()->route('admin.articles.translations.index')
            ->with('success', 'Traduzione eliminata con successo!');
    }

    /**
     * Mark an article as needing translation (editor decision)
     */
    public function markForTranslation($articleId)
    {
        try {
            $article = Article::find($articleId);

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Articolo non trovato (ID: ' . $articleId . ')'
                ], 404);
            }

            $article->update([
                'needs_translation' => true,
                'translation_status' => ['it' => 'pending', 'en' => 'pending', 'fr' => 'pending', 'es' => 'pending', 'de' => 'pending', 'pt' => 'pending', 'ru' => 'pending']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Articolo marcato per traduzione'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nel marcare l\'articolo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unmark an article from translation
     */
    public function unmarkFromTranslation($articleId)
    {
        try {
            $article = Article::find($articleId);

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Articolo non trovato (ID: ' . $articleId . ')'
                ], 404);
            }

            $article->update([
                'needs_translation' => false,
                'translation_status' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Articolo rimosso dalla lista traduzioni'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nel rimuovere l\'articolo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update translation status in the article
     */
    private function updateArticleTranslationStatus(Article $article, $language, $status)
    {
        $translationStatus = $article->translation_status ?? [];
        $translationStatus[$language] = $status;

        $article->update(['translation_status' => $translationStatus]);

        // Check if all translations are completed
        $allCompleted = true;
        foreach (['it', 'en', 'fr', 'es', 'de', 'pt', 'ru'] as $lang) {
            if (($translationStatus[$lang] ?? 'pending') !== 'completed') {
                $allCompleted = false;
                break;
            }
        }

        if ($allCompleted) {
            $article->update([
                'needs_translation' => false,
                'translated_at' => now()
            ]);
        }
    }
}
