<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\ArticleLayout;
use App\Services\AutoTranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    protected $translationService;

    public function __construct(AutoTranslationService $translationService)
    {
        $this->translationService = $translationService;
        $this->middleware('auth')->except(['index', 'show', 'search']);
        $this->middleware('permission:articles.create')->only(['create', 'store']);
        $this->middleware('permission:articles.edit')->only(['edit', 'update']);
        $this->middleware('permission:articles.delete')->only(['destroy']);
        $this->middleware('permission:articles.publish')->only(['publish', 'unpublish']);
    }

    /**
     * Display a listing of articles (public page)
     */
    public function index(Request $request)
    {
        $query = Article::with(['user', 'category', 'tags'])
            ->published()
            ->withCount(['likes', 'comments']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->byTag($request->tag);
        }

        // Sort
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'popular':
                $query->popular();
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            default:
                $query->recent();
        }

        $articles = $query->paginate(12);

        // Get layout articles for the page
        $layoutArticles = $this->getLayoutArticles();

        // Get sidebar articles
        $sidebarArticles = $this->getSidebarArticles($request->get('sidebar_sort', 'new'));

        // Get categories and tags for filters
        $categories = ArticleCategory::active()->ordered()->get();
        $tags = ArticleTag::active()->popular()->limit(20)->get();

        return view('articles.index', compact(
            'articles',
            'layoutArticles',
            'sidebarArticles',
            'categories',
            'tags'
        ));
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        $categories = ArticleCategory::active()->ordered()->get();
        $tags = ArticleTag::active()->ordered()->get();

        return view('articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:article_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:article_tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date|after:now',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        // Set published_at if publishing now
        if ($data['status'] === 'published' && !$request->filled('published_at')) {
            $data['published_at'] = now();
        }

        // Auto-translate if enabled
        if (config('articles.auto_translate', false)) {
            $data = $this->autoTranslateArticle($data);
        }

        $article = Article::create($data);

        // Sync tags
        if ($request->filled('tags')) {
            $article->tags()->sync($request->tags);
        }

        // Increment tag usage
        if ($request->filled('tags')) {
            foreach ($request->tags as $tagId) {
                $tag = ArticleTag::find($tagId);
                if ($tag) {
                    $tag->incrementUsage();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('articles.article_created'),
            'article' => $article->load(['user', 'category', 'tags']),
            'redirect' => route('articles.show', $article)
        ]);
    }

    /**
     * Display the specified article
     */
    public function show(Article $article)
    {
        // Check if user can view this article
        if (!$article->isPublished && !$this->canViewArticle($article)) {
            abort(404);
        }

        // Increment view count
        $article->incrementViews();

        // Load relationships
        $article->load([
            'user',
            'category',
            'tags',
            'comments' => function ($query) {
                $query->approved()->topLevel()->with(['user', 'replies.user']);
            }
        ]);

        // Get related articles
        $relatedArticles = $this->getRelatedArticles($article);

        return view('articles.show', compact('article', 'relatedArticles'));
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit(Article $article)
    {
        if (!$this->canEditArticle($article)) {
            abort(403);
        }

        $categories = ArticleCategory::active()->ordered()->get();
        $tags = ArticleTag::active()->ordered()->get();

        return view('articles.edit', compact('article', 'categories', 'tags'));
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, Article $article)
    {
        if (!$this->canEditArticle($article)) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:article_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:article_tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($article->featured_image) {
                Storage::delete($article->featured_image);
            }
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        // Set published_at if publishing now
        if ($data['status'] === 'published' && !$request->filled('published_at') && !$article->isPublished) {
            $data['published_at'] = now();
        }

        // Auto-translate if enabled
        if (config('articles.auto_translate', false)) {
            $data = $this->autoTranslateArticle($data);
        }

        $article->update($data);

        // Sync tags
        $oldTags = $article->tags->pluck('id')->toArray();
        $newTags = $request->tags ?? [];
        
        $article->tags()->sync($newTags);

        // Update tag usage counts
        $this->updateTagUsage($oldTags, $newTags);

        return response()->json([
            'success' => true,
            'message' => __('articles.article_updated'),
            'article' => $article->load(['user', 'category', 'tags'])
        ]);
    }

    /**
     * Remove the specified article
     */
    public function destroy(Article $article)
    {
        if (!$this->canDeleteArticle($article)) {
            abort(403);
        }

        // Delete featured image
        if ($article->featured_image) {
            Storage::delete($article->featured_image);
        }

        // Decrement tag usage
        $this->updateTagUsage($article->tags->pluck('id')->toArray(), []);

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => __('articles.article_deleted')
        ]);
    }

    /**
     * Search articles
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $sort = $request->get('sort', 'recent');

        $articles = Article::with(['user', 'category', 'tags'])
            ->published()
            ->search($query)
            ->withCount(['likes', 'comments']);

        switch ($sort) {
            case 'popular':
                $articles->popular();
                break;
            case 'oldest':
                $articles->orderBy('published_at', 'asc');
                break;
            default:
                $articles->recent();
        }

        $articles = $articles->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'articles' => $articles,
                'html' => view('articles.partials.articles-list', compact('articles'))->render()
            ]);
        }

        return view('articles.search', compact('articles', 'query'));
    }

    /**
     * Publish article
     */
    public function publish(Article $article)
    {
        if (!$this->canPublishArticle($article)) {
            abort(403);
        }

        $article->publish();

        return response()->json([
            'success' => true,
            'message' => __('articles.article_published')
        ]);
    }

    /**
     * Unpublish article
     */
    public function unpublish(Article $article)
    {
        if (!$this->canPublishArticle($article)) {
            abort(403);
        }

        $article->unpublish();

        return response()->json([
            'success' => true,
            'message' => __('articles.article_unpublished')
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Article $article)
    {
        if (!Auth::user()->hasPermissionTo('articles.feature')) {
            abort(403);
        }

        $article->toggleFeatured();

        return response()->json([
            'success' => true,
            'message' => $article->featured ? __('articles.article_featured') : __('articles.article_unfeatured'),
            'featured' => $article->featured
        ]);
    }

    /**
     * Show user's articles
     */
    public function myArticles(Request $request)
    {
        $query = Article::where('user_id', auth()->id())
            ->with(['category', 'tags', 'user'])
            ->withCount(['likes', 'comments', 'views']);

        // Filtri
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = ArticleCategory::all();

        return view('articles.my-articles', compact('articles', 'categories'));
    }

    /**
     * Get layout articles for the main page
     */
    private function getLayoutArticles()
    {
        $positions = ['banner', 'column1', 'column2', 'horizontal1', 'horizontal2'];
        $layoutArticles = [];

        foreach ($positions as $position) {
            $layout = ArticleLayout::getLayoutForPosition($position);
            if ($layout->isNotEmpty()) {
                $layoutArticles[$position] = $layout->first()->article;
            }
        }

        return $layoutArticles;
    }

    /**
     * Get sidebar articles
     */
    private function getSidebarArticles($sort = 'new')
    {
        $query = Article::with(['user', 'category'])
            ->published()
            ->withCount(['likes', 'comments']);

        switch ($sort) {
            case 'popular':
                $query->popular();
                break;
            default:
                $query->recent();
        }

        return $query->limit(5)->get();
    }

    /**
     * Get related articles
     */
    private function getRelatedArticles(Article $article)
    {
        return Article::with(['user', 'category'])
            ->published()
            ->where('id', '!=', $article->id)
            ->where(function ($query) use ($article) {
                $query->where('category_id', $article->category_id)
                      ->orWhereHas('tags', function ($q) use ($article) {
                          $q->whereIn('article_tags.id', $article->tags->pluck('id'));
                      });
            })
            ->withCount(['likes', 'comments'])
            ->popular()
            ->limit(6)
            ->get();
    }

    /**
     * Upload image
     */
    private function uploadImage($file)
    {
        $path = $file->store('articles/images', 'public');
        return $path;
    }

    /**
     * Auto-translate article
     */
    private function autoTranslateArticle($data)
    {
        $locales = config('app.available_locales', ['en', 'es', 'fr', 'de', 'pt']);
        $sourceLocale = config('app.locale', 'it');

        foreach ($locales as $locale) {
            if ($locale === $sourceLocale) continue;

            if (isset($data['title'])) {
                $data['title'][$locale] = $this->translationService->translate($data['title'], $sourceLocale, $locale);
            }

            if (isset($data['content'])) {
                $data['content'][$locale] = $this->translationService->translate($data['content'], $sourceLocale, $locale);
            }

            if (isset($data['excerpt'])) {
                $data['excerpt'][$locale] = $this->translationService->translate($data['excerpt'], $sourceLocale, $locale);
            }
        }

        return $data;
    }

    /**
     * Update tag usage counts
     */
    private function updateTagUsage($oldTags, $newTags)
    {
        $removedTags = array_diff($oldTags, $newTags);
        $addedTags = array_diff($newTags, $oldTags);

        foreach ($removedTags as $tagId) {
            $tag = ArticleTag::find($tagId);
            if ($tag) {
                $tag->decrementUsage();
            }
        }

        foreach ($addedTags as $tagId) {
            $tag = ArticleTag::find($tagId);
            if ($tag) {
                $tag->incrementUsage();
            }
        }
    }

    /**
     * Check if user can view article
     */
    private function canViewArticle(Article $article)
    {
        $user = Auth::user();
        if (!$user) return false;

        return $user->id === $article->user_id || 
               $user->hasRole(['admin', 'editor']) ||
               $user->hasPermissionTo('articles.view');
    }

    /**
     * Check if user can edit article
     */
    private function canEditArticle(Article $article)
    {
        $user = Auth::user();
        if (!$user) return false;

        return $user->id === $article->user_id || 
               $user->hasRole(['admin', 'editor']) ||
               $user->hasPermissionTo('articles.edit');
    }

    /**
     * Check if user can delete article
     */
    private function canDeleteArticle(Article $article)
    {
        $user = Auth::user();
        if (!$user) return false;

        return $user->id === $article->user_id || 
               $user->hasRole(['admin', 'editor']) ||
               $user->hasPermissionTo('articles.delete');
    }

    /**
     * Check if user can publish article
     */
    private function canPublishArticle(Article $article)
    {
        $user = Auth::user();
        if (!$user) return false;

        return $user->hasRole(['admin', 'editor']) ||
               $user->hasPermissionTo('articles.publish');
    }
}
