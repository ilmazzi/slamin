<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
        $this->middleware('permission:articles.view_reports')->only(['index', 'show', 'review']);
    }

    /**
     * Report an article
     */
    public function store(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|in:spam,inappropriate,copyright,fake_news,other',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Check if user has already reported this article
        if ($article->isReportedByUser($user)) {
            return response()->json([
                'success' => false,
                'message' => __('articles.already_reported')
            ], 400);
        }

        // Create report
        $report = $article->reportArticle(
            $user,
            $request->reason,
            $request->description
        );

        return response()->json([
            'success' => true,
            'message' => __('articles.report_sent'),
            'report' => $report
        ]);
    }

    /**
     * Show reports list (admin/editor only)
     */
    public function index(Request $request)
    {
        $query = ArticleReport::with(['article', 'user', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by reason
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        // Filter by article
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }

        $reports = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reports' => $reports
            ]);
        }

        return view('articles.reports.index', compact('reports'));
    }

    /**
     * Show a specific report
     */
    public function show(ArticleReport $report)
    {
        $report->load(['article', 'user', 'reviewer']);

        return view('articles.reports.show', compact('report'));
    }

    /**
     * Review a report (admin/editor only)
     */
    public function review(Request $request, ArticleReport $report)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:reviewed,resolved',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        $report->review(
            $request->status,
            $request->admin_notes,
            $user->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Segnalazione aggiornata',
            'report' => $report->load(['article', 'user', 'reviewer'])
        ]);
    }

    /**
     * Get reports for an article
     */
    public function getArticleReports(Request $request, Article $article)
    {
        $reports = $article->reports()
            ->with(['user', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reports' => $reports
            ]);
        }

        return view('articles.reports.article-reports', compact('article', 'reports'));
    }

    /**
     * Get pending reports count
     */
    public function getPendingCount()
    {
        $count = ArticleReport::pending()->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Bulk review reports
     */
    public function bulkReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:article_reports,id',
            'status' => 'required|in:reviewed,resolved',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $updated = 0;

        foreach ($request->report_ids as $reportId) {
            $report = ArticleReport::find($reportId);
            if ($report) {
                $report->review(
                    $request->status,
                    $request->admin_notes,
                    $user->id
                );
                $updated++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$updated} segnalazioni aggiornate"
        ]);
    }

    /**
     * Get report statistics
     */
    public function getStats()
    {
        $stats = [
            'total' => ArticleReport::count(),
            'pending' => ArticleReport::pending()->count(),
            'reviewed' => ArticleReport::reviewed()->count(),
            'resolved' => ArticleReport::resolved()->count(),
            'by_reason' => ArticleReport::selectRaw('reason, COUNT(*) as count')
                ->groupBy('reason')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Check if user has reported an article
     */
    public function checkReport(Request $request, Article $article)
    {
        $user = Auth::user();
        $reported = $user ? $article->isReportedByUser($user) : false;

        return response()->json([
            'success' => true,
            'reported' => $reported
        ]);
    }

    /**
     * Get report reasons
     */
    public function getReasons()
    {
        $reasons = [
            'spam' => __('articles.spam'),
            'inappropriate' => __('articles.inappropriate'),
            'copyright' => __('articles.copyright'),
            'fake_news' => __('articles.fake_news'),
            'other' => __('articles.other')
        ];

        return response()->json([
            'success' => true,
            'reasons' => $reasons
        ]);
    }
}
