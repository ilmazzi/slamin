<?php

namespace App\Http\Controllers\Translator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TranslationPayment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Exception\ApiErrorException;

class PayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Mostra la dashboard payout del traduttore
     */
    public function index()
    {
        $user = Auth::user();

        // Pagamenti ricevuti
        $payments = TranslationPayment::with(['poem', 'client', 'gigApplication.gig'])
            ->where('translator_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistiche
        $stats = [
            'total_earned' => TranslationPayment::where('translator_id', $user->id)
                ->where('status', 'completed')
                ->sum('translator_amount'),
            'total_paid_out' => TranslationPayment::where('translator_id', $user->id)
                ->where('payout_status', 'transferred')
                ->sum('translator_amount'),
            'pending_payout' => TranslationPayment::where('translator_id', $user->id)
                ->where('payout_status', 'pending')
                ->sum('translator_amount'),
        ];

        return view('translator.payouts.index', compact('payments', 'stats', 'user'));
    }

    /**
     * Mostra la configurazione del metodo di payout
     */
    public function setup()
    {
        $user = Auth::user();

        return view('translator.payouts.setup', compact('user'));
    }

    /**
     * Crea un account Stripe Connect per il traduttore
     */
    public function createStripeAccount()
    {
        try {
            $user = Auth::user();

            // Crea account Stripe Connect
            $account = Account::create([
                'type' => 'express',
                'country' => 'IT', // O rileva automaticamente
                'email' => $user->email,
                'capabilities' => [
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'individual' => [
                    'email' => $user->email,
                    'first_name' => explode(' ', $user->name)[0] ?? '',
                    'last_name' => explode(' ', $user->name)[1] ?? '',
                ],
            ]);

            // Salva l'account ID
            $user->update([
                'stripe_connect_account_id' => $account->id,
            ]);

            // Crea link di onboarding
            $accountLink = AccountLink::create([
                'account' => $account->id,
                'refresh_url' => route('translator.payouts.setup'),
                'return_url' => route('translator.payouts.setup'),
                'type' => 'account_onboarding',
            ]);

            return redirect($accountLink->url);

        } catch (ApiErrorException $e) {
            Log::error('Stripe Connect account creation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error',
                'Errore nella creazione dell\'account Stripe: ' . $e->getMessage()
            );
        }
    }

    /**
     * Aggiorna lo stato dell'account Stripe
     */
    public function updateStripeStatus()
    {
        try {
            $user = Auth::user();

            if (!$user->stripe_connect_account_id) {
                return redirect()->back()->with('error', 'Account Stripe non configurato');
            }

            $account = Account::retrieve($user->stripe_connect_account_id);

            // Verifica se l'account è completo
            $isComplete = $account->details_submitted &&
                          $account->charges_enabled &&
                          $account->payouts_enabled;

            $user->update([
                'payout_method_configured' => $isComplete,
            ]);

            if ($isComplete) {
                return redirect()->back()->with('success',
                    'Account Stripe configurato con successo! Ora puoi ricevere i pagamenti automaticamente.'
                );
            } else {
                return redirect()->back()->with('warning',
                    'Account Stripe non ancora completo. Completa la configurazione per ricevere i pagamenti.'
                );
            }

        } catch (ApiErrorException $e) {
            Log::error('Stripe account status check failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error',
                'Errore nel controllo dello stato dell\'account: ' . $e->getMessage()
            );
        }
    }

    /**
     * Configura PayPal come metodo di payout
     */
    public function setupPayPal(Request $request)
    {
        $request->validate([
            'paypal_email' => 'required|email',
        ]);

        $user = Auth::user();
        $user->update([
            'paypal_email' => $request->paypal_email,
            'payout_method_configured' => true,
        ]);

        return redirect()->back()->with('success',
            'Email PayPal configurata con successo!'
        );
    }

    /**
     * Mostra i dettagli di un pagamento
     */
    public function show(TranslationPayment $payment)
    {
        // Verifica che il pagamento appartenga al traduttore
        if ($payment->translator_id !== Auth::id()) {
            abort(403, 'Non hai i permessi per visualizzare questo pagamento');
        }

        $payment->load(['poem', 'client', 'gigApplication.gig']);

        return view('translator.payouts.show', compact('payment'));
    }

    /**
     * Richiedi payout manuale
     */
    public function requestManualPayout(Request $request, TranslationPayment $payment)
    {
        // Verifica che il pagamento appartenga al traduttore
        if ($payment->translator_id !== Auth::id()) {
            abort(403, 'Non hai i permessi per questo pagamento');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $payment->update([
            'payout_status' => 'manual_requested',
            'payout_notes' => $request->notes ?? 'Richiesta payout manuale',
        ]);

        // Qui potresti inviare una notifica all'admin
        // o creare un task per il payout manuale

        return redirect()->back()->with('success',
            'Richiesta di payout manuale inviata. L\'admin la esaminerà a breve.'
        );
    }
}
