<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\TranslationPayment;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Group;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la dashboard admin con statistiche
     */
    public function index()
    {
        // Verifica che l'utente sia admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Accesso negato');
        }

        // Statistiche generali
        $stats = $this->getGeneralStats();

        // Statistiche utenti
        $userStats = $this->getUserStats();

        // Statistiche eventi
        $eventStats = $this->getEventStats();

        // Statistiche pagamenti
        $paymentStats = $this->getPaymentStats();

        // Statistiche contenuti
        $contentStats = $this->getContentStats();

        // Attività recente
        $recentActivity = $this->getRecentActivity();

        // Utenti online
        $onlineUsers = $this->getOnlineUsers();

        return view('admin.dashboard', compact(
            'stats',
            'userStats',
            'eventStats',
            'paymentStats',
            'contentStats',
            'recentActivity',
            'onlineUsers'
        ));
    }

    /**
     * Statistiche generali
     */
    private function getGeneralStats()
    {
        return [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'total_gigs' => Gig::count(),
            'total_payments' => TranslationPayment::count(),
            'total_videos' => Video::count(),
            'total_poems' => Poem::count(),
            'total_groups' => Group::count(),
            'total_messages' => ChatMessage::count(),
        ];
    }

    /**
     * Statistiche utenti
     */
    private function getUserStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'new_today' => User::whereDate('created_at', $today)->count(),
            'new_this_week' => User::where('created_at', '>=', $thisWeek)->count(),
            'new_this_month' => User::where('created_at', '>=', $thisMonth)->count(),
            'active_users' => User::where('updated_at', '>=', Carbon::now()->subDays(7))->count(),
            'premium_users' => User::whereHas('subscriptions', function($query) {
                $query->where('status', 'active');
            })->count(),
            'translators' => User::whereHas('roles', function($query) {
                $query->where('name', 'translator');
            })->count(),
        ];
    }

    /**
     * Statistiche eventi
     */
    private function getEventStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'events_today' => Event::whereDate('start_datetime', $today)->count(),
            'events_this_week' => Event::whereBetween('start_datetime', [$thisWeek, Carbon::now()->endOfWeek()])->count(),
            'events_this_month' => Event::whereMonth('start_datetime', Carbon::now()->month)->count(),
            'upcoming_events' => Event::where('start_datetime', '>=', $today)->count(),
            'past_events' => Event::where('start_datetime', '<', $today)->count(),
            'active_gigs' => Gig::where('is_closed', false)->count(),
        ];
    }

    /**
     * Statistiche pagamenti
     */
    private function getPaymentStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $totalRevenue = TranslationPayment::where('status', 'completed')->sum('amount');
        $todayRevenue = TranslationPayment::where('status', 'completed')
            ->whereDate('created_at', $today)->sum('amount');
        $thisWeekRevenue = TranslationPayment::where('status', 'completed')
            ->where('created_at', '>=', $thisWeek)->sum('amount');
        $thisMonthRevenue = TranslationPayment::where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)->sum('amount');

        return [
            'total_revenue' => $totalRevenue,
            'today_revenue' => $todayRevenue,
            'this_week_revenue' => $thisWeekRevenue,
            'this_month_revenue' => $thisMonthRevenue,
            'pending_payments' => TranslationPayment::where('status', 'pending')->count(),
            'completed_payments' => TranslationPayment::where('status', 'completed')->count(),
            'failed_payments' => TranslationPayment::where('status', 'failed')->count(),
        ];
    }

    /**
     * Statistiche contenuti
     */
    private function getContentStats()
    {
        return [
            'total_videos' => Video::count(),
            'total_poems' => Poem::count(),
            'total_groups' => Group::count(),
            'total_messages' => ChatMessage::count(),
            'videos_this_month' => Video::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'poems_this_month' => Poem::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'groups_this_month' => Group::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
            'messages_this_month' => ChatMessage::where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];
    }

    /**
     * Attività recente
     */
    private function getRecentActivity()
    {
        $recentUsers = User::latest()->take(5)->get();
        $recentEvents = Event::latest()->take(5)->get();
        $recentPayments = TranslationPayment::latest()->take(5)->get();
        $recentGigs = Gig::latest()->take(5)->get();

        return [
            'recent_users' => $recentUsers,
            'recent_events' => $recentEvents,
            'recent_payments' => $recentPayments,
            'recent_gigs' => $recentGigs,
        ];
    }

    /**
     * Utenti online
     */
    private function getOnlineUsers()
    {
        return User::where('updated_at', '>=', Carbon::now()->subMinutes(5))->count();
    }
}
