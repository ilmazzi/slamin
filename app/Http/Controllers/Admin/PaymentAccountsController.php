<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\TranslationPayment;
use App\Models\SystemSetting;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\Exception\ApiErrorException;

class PaymentAccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Configura Stripe con le impostazioni dal database
        $stripeSecretKey = SystemSetting::get('stripe_secret_key');
        if ($stripeSecretKey) {
            Stripe::setApiKey($stripeSecretKey);
        }
    }

    /**
     * Verifica che l'utente sia admin
     */
    private function checkAdminAccess()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Accesso negato. Solo gli amministratori possono accedere a questa sezione.');
        }
    }

    /**
     * Dashboard principale per la gestione conti
     */
    public function index()
    {
        $this->checkAdminAccess();
        // Statistiche generali
        $stats = [
            'total_users' => User::count(),
            'stripe_connected' => User::where('stripe_connect_status', 'active')->count(),
            'paypal_connected' => User::where('paypal_verified', true)->count(),
            'pending_verification' => User::where('paypal_verified', false)
                ->whereNotNull('paypal_email')
                ->count(),
        ];

        // Utenti con conti Stripe
        $stripeUsers = User::whereNotNull('stripe_connect_account_id')
            ->withCount(['translationPayments as payments_count' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('stripe_connected_at', 'desc')
            ->paginate(10, ['*'], 'stripe_page');

        // Utenti con PayPal
        $paypalUsers = User::whereNotNull('paypal_email')
            ->withCount(['translationPayments as payments_count' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('paypal_connected_at', 'desc')
            ->paginate(10, ['*'], 'paypal_page');

        // Pagamenti recenti
        $recentPayments = TranslationPayment::with(['translator', 'client', 'poem'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.payment-accounts.index', compact(
            'stats',
            'stripeUsers',
            'paypalUsers',
            'recentPayments'
        ));
    }

    /**
     * Dettagli di un utente specifico
     */
    public function show(User $user)
    {
        $this->checkAdminAccess();
        $user->loadCount(['translationPayments as payments_count' => function($query) {
            $query->where('status', 'completed');
        }]);

        $payments = TranslationPayment::with(['poem', 'client'])
            ->where('translator_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.payment-accounts.show', compact('user', 'payments'));
    }

    /**
     * Verifica un account PayPal
     */
    public function verifyPayPal(User $user)
    {
        $this->checkAdminAccess();
        if (!$user->paypal_email) {
            return redirect()->back()->with('error', 'Utente senza email PayPal');
        }

        // Qui potresti implementare una verifica reale con l'API PayPal
        // Per ora simuliamo la verifica
        $user->update([
            'paypal_verified' => true,
        ]);

        Log::info('PayPal account verified by admin', [
            'user_id' => $user->id,
            'paypal_email' => $user->paypal_email,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success',
            'Account PayPal verificato con successo per ' . $user->name
        );
    }

    /**
     * Revoca la verifica PayPal
     */
    public function unverifyPayPal(User $user)
    {
        $this->checkAdminAccess();
        $user->update([
            'paypal_verified' => false,
        ]);

        Log::info('PayPal account unverified by admin', [
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success',
            'Verifica PayPal revocata per ' . $user->name
        );
    }

    /**
     * Aggiorna lo stato di un account Stripe
     */
    public function updateStripeStatus(User $user)
    {
        $this->checkAdminAccess();
        if (!$user->stripe_connect_account_id) {
            return redirect()->back()->with('error', 'Utente senza account Stripe');
        }

        try {
            $account = Account::retrieve($user->stripe_connect_account_id);

            // Determina lo stato
            $status = 'pending';
            if ($account->details_submitted && $account->charges_enabled && $account->payouts_enabled) {
                $status = 'active';
            } elseif ($account->requirements->currently_due || $account->requirements->eventually_due) {
                $status = 'pending';
            } elseif ($account->requirements->disabled_reason) {
                $status = 'restricted';
            }

            $user->update([
                'stripe_connect_status' => $status,
                'stripe_connect_details' => [
                    'charges_enabled' => $account->charges_enabled,
                    'payouts_enabled' => $account->payouts_enabled,
                    'details_submitted' => $account->details_submitted,
                    'requirements' => $account->requirements->toArray(),
                ],
                'payout_method_configured' => $status === 'active',
            ]);

            return redirect()->back()->with('success',
                'Stato Stripe aggiornato per ' . $user->name . ': ' . $status
            );

        } catch (ApiErrorException $e) {
            Log::error('Stripe account status update failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
            ]);

            return redirect()->back()->with('error',
                'Errore nell\'aggiornamento dello stato Stripe: ' . $e->getMessage()
            );
        }
    }

    /**
     * Disconnette un account
     */
    public function disconnectAccount(Request $request, User $user)
    {
        $request->validate([
            'account_type' => 'required|in:stripe,paypal',
        ]);

        if ($request->account_type === 'stripe') {
            $user->update([
                'stripe_connect_account_id' => null,
                'stripe_connect_status' => 'not_connected',
                'stripe_connect_details' => null,
                'stripe_connected_at' => null,
            ]);
        } else {
            $user->update([
                'paypal_email' => null,
                'paypal_merchant_id' => null,
                'paypal_verified' => false,
                'paypal_connected_at' => null,
            ]);
        }

        Log::info('Account disconnected by admin', [
            'user_id' => $user->id,
            'account_type' => $request->account_type,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success',
            'Account ' . $request->account_type . ' disconnesso per ' . $user->name
        );
    }

    /**
     * Lista utenti per verifica PayPal
     */
    public function paypalVerification()
    {
        $users = User::whereNotNull('paypal_email')
            ->where('paypal_verified', false)
            ->orderBy('paypal_connected_at', 'asc')
            ->paginate(20);

        return view('admin.payment-accounts.paypal-verification', compact('users'));
    }

    /**
     * Lista utenti con problemi Stripe
     */
    public function stripeIssues()
    {
        $users = User::whereNotNull('stripe_connect_account_id')
            ->whereIn('stripe_connect_status', ['pending', 'restricted'])
            ->orderBy('stripe_connected_at', 'asc')
            ->paginate(20);

        return view('admin.payment-accounts.stripe-issues', compact('users'));
    }

    /**
     * Statistiche dettagliate
     */
    public function statistics()
    {
        $stats = [
            // Stripe
            'stripe_active' => User::where('stripe_connect_status', 'active')->count(),
            'stripe_pending' => User::where('stripe_connect_status', 'pending')->count(),
            'stripe_restricted' => User::where('stripe_connect_status', 'restricted')->count(),

            // PayPal
            'paypal_verified' => User::where('paypal_verified', true)->count(),
            'paypal_unverified' => User::where('paypal_verified', false)
                ->whereNotNull('paypal_email')
                ->count(),

            // Payout methods
            'preferred_stripe' => User::where('preferred_payout_method', 'stripe')->count(),
            'preferred_paypal' => User::where('preferred_payout_method', 'paypal')->count(),
            'preferred_manual' => User::where('preferred_payout_method', 'manual')->count(),

            // Payments
            'total_payments' => TranslationPayment::where('status', 'completed')->count(),
            'total_amount' => TranslationPayment::where('status', 'completed')->sum('amount'),
            'total_commission' => TranslationPayment::where('status', 'completed')->sum('commission_total'),
        ];

        // Grafici per mese
        $monthlyStats = TranslationPayment::where('status', 'completed')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('admin.payment-accounts.statistics', compact('stats', 'monthlyStats'));
    }

    /**
     * Export dati per contabilità
     */
    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:payments,users,accounts',
            'format' => 'required|in:csv,excel',
        ]);

        // Implementare export basato sul tipo richiesto
        // Per ora restituiamo un messaggio
        return redirect()->back()->with('info',
            'Export ' . $request->type . ' in formato ' . $request->format . ' sarà implementato'
        );
    }
}
