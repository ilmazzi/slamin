<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of articles for admin
     */
    public function index()
    {
        $articles = Article::with(['user', 'category', 'tags'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        $categories = ArticleCategory::active()->ordered()->get();
        $tags = ArticleTag::active()->ordered()->get();

        return view('admin.articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.it' => 'required|string|max:255',
            'content' => 'required|array',
            'content.it' => 'required|string',
            'excerpt' => 'nullable|array',
            'excerpt.it' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:article_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'featured' => 'boolean',
            'meta_title' => 'nullable|array',
            'meta_description' => 'nullable|array',
            'meta_keywords' => 'nullable|array',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:article_tags,id',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']['it']);
        $validated['moderation_status'] = 'approved'; // Admin articles are auto-approved
        $validated['is_public'] = true;

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('articles', 'public');
            $validated['featured_image'] = $path;
        }

        $article = Article::create($validated);

        if (isset($validated['tag_ids'])) {
            $article->tags()->attach($validated['tag_ids']);
        }

        if ($validated['status'] === 'published') {
            $article->publish();
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Articolo creato con successo!');
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit(Article $article)
    {
        $categories = ArticleCategory::active()->ordered()->get();
        $tags = ArticleTag::active()->ordered()->get();

        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.it' => 'required|string|max:255',
            'content' => 'required|array',
            'content.it' => 'required|string',
            'excerpt' => 'nullable|array',
            'excerpt.it' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:article_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:draft,published,archived',
            'featured' => 'boolean',
            'meta_title' => 'nullable|array',
            'meta_description' => 'nullable|array',
            'meta_keywords' => 'nullable|array',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:article_tags,id',
        ]);

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $path = $request->file('featured_image')->store('articles', 'public');
            $validated['featured_image'] = $path;
        }

        $article->update($validated);

        if (isset($validated['tag_ids'])) {
            $article->tags()->sync($validated['tag_ids']);
        }

        if ($validated['status'] === 'published' && $article->status !== 'published') {
            $article->publish();
        } elseif ($validated['status'] === 'draft' && $article->status === 'published') {
            $article->unpublish();
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Articolo aggiornato con successo!');
    }

    /**
     * Remove the specified article
     */
    public function destroy(Article $article)
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Articolo eliminato con successo!');
    }

    /**
     * Publish the specified article
     */
    public function publish(Article $article)
    {
        $article->publish();
        $article->update(['moderation_status' => 'approved']);

        return redirect()->back()->with('success', 'Articolo pubblicato con successo!');
    }

    /**
     * Unpublish the specified article
     */
    public function unpublish(Article $article)
    {
        $article->unpublish();

        return redirect()->back()->with('success', 'Articolo rimesso in bozza!');
    }

    /**
     * Approve the specified article
     */
    public function approve(Article $article)
    {
        $article->update([
            'moderation_status' => 'approved',
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Articolo approvato con successo!');
    }

    /**
     * Reject the specified article
     */
    public function reject(Request $request, Article $article)
    {
        $validated = $request->validate([
            'moderation_notes' => 'required|string|max:1000',
        ]);

        $article->update([
            'moderation_status' => 'rejected',
            'moderation_notes' => $validated['moderation_notes'],
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Articolo rifiutato con successo!');
    }

        /**
     * Toggle featured status of an article
     */
    public function toggleFeatured(Request $request, Article $article)
    {
        // Debug log
        \Illuminate\Support\Facades\Log::info('Toggle featured called', [
            'article_id' => $article->id,
            'article_title' => $article->title,
            'current_featured' => $article->featured,
            'request_featured' => $request->input('featured'),
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email
        ]);

        try {
            $featured = $request->input('featured', !$article->featured);

            // Check if we're trying to add a featured article and we're at the limit
            if ($featured && !$article->featured) {
                $currentFeaturedCount = Article::where('featured', true)->count();
                if ($currentFeaturedCount >= 3) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Limite massimo di 3 articoli featured raggiunto. Rimuovi un articolo featured prima di aggiungerne un altro.'
                    ], 400);
                }
            }

            $article->update(['featured' => $featured]);

            $message = $featured ? 'Articolo aggiunto ai featured con successo!' : 'Articolo rimosso dai featured con successo!';

            return response()->json([
                'success' => true,
                'message' => $message,
                'featured' => $featured
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiornamento dello stato featured: ' . $e->getMessage()
            ], 500);
        }
    }
}
