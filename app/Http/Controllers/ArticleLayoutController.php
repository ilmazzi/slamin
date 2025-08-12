<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleLayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:articles.manage_layout');
    }

    /**
     * Show layout management page
     */
    public function index()
    {
        $positions = ArticleLayout::getPositions();
        $layoutData = [];

        foreach ($positions as $key => $name) {
            $layout = ArticleLayout::getLayoutForPosition($key);
            $layoutData[$key] = [
                'name' => $name,
                'article' => $layout->isNotEmpty() ? $layout->first()->article : null,
                'layout' => $layout->first()
            ];
        }

        $articles = Article::published()
            ->with(['user', 'category'])
            ->orderBy('title')
            ->get();

        return view('articles.layout.index', compact('layoutData', 'articles', 'positions'));
    }

    /**
     * Update layout position
     */
    public function update(Request $request)
    {
        $request->validate([
            'position' => 'required|string',
            'article_id' => 'nullable|exists:articles,id'
        ]);

        $position = $request->position;
        $articleId = $request->article_id;

        // Check if position is valid
        $positions = ArticleLayout::getPositions();
        if (!array_key_exists($position, $positions)) {
            return response()->json([
                'success' => false,
                'message' => 'Posizione non valida'
            ], 422);
        }

        // Update layout
        $layout = ArticleLayout::updateLayout($position, $articleId);

        return response()->json([
            'success' => true,
            'message' => __('articles.layout_updated'),
            'layout' => $layout ? $layout->load('article') : null
        ]);
    }

    /**
     * Get articles for layout selection
     */
    public function getArticles(Request $request)
    {
        $query = Article::with(['user', 'category'])
            ->published()
            ->orderBy('title');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $articles = $query->limit(20)->get();

        return response()->json([
            'success' => true,
            'articles' => $articles
        ]);
    }

    /**
     * Get current layout data
     */
    public function getLayout()
    {
        $positions = ArticleLayout::getPositions();
        $layoutData = [];

        foreach ($positions as $key => $name) {
            $layout = ArticleLayout::getLayoutForPosition($key);
            $layoutData[$key] = [
                'name' => $name,
                'article' => $layout->isNotEmpty() ? $layout->first()->article : null,
                'layout' => $layout->first()
            ];
        }

        return response()->json([
            'success' => true,
            'layout' => $layoutData
        ]);
    }

    /**
     * Clear layout position
     */
    public function clear(Request $request)
    {
        $request->validate([
            'position' => 'required|string'
        ]);

        $position = $request->position;

        // Check if position is valid
        $positions = ArticleLayout::getPositions();
        if (!array_key_exists($position, $positions)) {
            return response()->json([
                'success' => false,
                'message' => 'Posizione non valida'
            ], 422);
        }

        // Clear layout
        ArticleLayout::where('position', $position)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Layout posizione cancellata'
        ]);
    }

    /**
     * Bulk update layout
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'layout' => 'required|array',
            'layout.*.position' => 'required|string',
            'layout.*.article_id' => 'nullable|exists:articles,id'
        ]);

        $positions = ArticleLayout::getPositions();
        $updated = 0;

        foreach ($request->layout as $item) {
            $position = $item['position'];
            $articleId = $item['article_id'] ?? null;

            // Check if position is valid
            if (!array_key_exists($position, $positions)) {
                continue;
            }

            // Update layout
            ArticleLayout::updateLayout($position, $articleId);
            $updated++;
        }

        return response()->json([
            'success' => true,
            'message' => "Layout aggiornato per {$updated} posizioni"
        ]);
    }

    /**
     * Preview layout
     */
    public function preview()
    {
        $layoutArticles = $this->getLayoutArticles();
        $sidebarArticles = $this->getSidebarArticles();

        return view('articles.layout.preview', compact('layoutArticles', 'sidebarArticles'));
    }

    /**
     * Get layout articles for preview
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
     * Get sidebar articles for preview
     */
    private function getSidebarArticles()
    {
        return Article::with(['user', 'category'])
            ->published()
            ->withCount(['likes', 'comments'])
            ->recent()
            ->limit(5)
            ->get();
    }
}
