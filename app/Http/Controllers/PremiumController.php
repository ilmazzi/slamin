<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\UserSubscription;
use Carbon\Carbon;

class PremiumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la lista dei pacchetti premium
     */
    public function index()
    {
        $user = Auth::user();
        $packages = Package::active()->ordered()->get();
        $currentSubscription = $user->activeSubscription;

        return view('premium.index', compact('packages', 'user', 'currentSubscription'));
    }

    /**
     * Mostra i dettagli di un pacchetto
     */
    public function show(Package $package)
    {
        $user = Auth::user();
        $currentSubscription = $user->activeSubscription;

        return view('premium.show', compact('package', 'user', 'currentSubscription'));
    }

    /**
     * Mostra il form di acquisto
     */
    public function checkout(Package $package)
    {
        $user = Auth::user();

        // Verifica se l'utente ha già un abbonamento attivo
        if ($user->hasPremiumSubscription()) {
            return redirect()->route('premium.index')
                ->with('info', 'Hai già un abbonamento attivo. Puoi aggiornare il tuo piano dalla dashboard.');
        }

        return view('premium.checkout', compact('package', 'user'));
    }

    /**
     * Processa l'acquisto (placeholder per Stripe)
     */
    public function processPurchase(Request $request, Package $package)
    {
        $user = Auth::user();

        // Validazione
        $request->validate([
            'payment_method' => 'required|string',
            'terms_accepted' => 'required|accepted',
        ]);

        try {
            // TODO: Integrazione con Stripe
            // Per ora creiamo un abbonamento di test

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'start_date' => now(),
                'end_date' => now()->addDays($package->duration_days),
                'status' => 'active',
                'stripe_subscription_id' => 'test_sub_' . time(),
                'stripe_customer_id' => 'test_cust_' . $user->id,
            ]);

            return redirect()->route('premium.success', $subscription)
                ->with('success', 'Abbonamento attivato con successo! Ora puoi caricare più video.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Errore durante l\'acquisto: ' . $e->getMessage());
        }
    }

    /**
     * Pagina di successo acquisto
     */
    public function success(UserSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('premium.success', compact('subscription'));
    }

    /**
     * Gestione abbonamento utente
     */
    public function manage()
    {
        $user = Auth::user();
        $subscriptions = $user->subscriptions()->with('package')->orderBy('created_at', 'desc')->get();

        return view('premium.manage', compact('user', 'subscriptions'));
    }

    /**
     * Cancella abbonamento
     */
    public function cancel(UserSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        if ($subscription->status !== 'active') {
            return back()->with('error', 'Questo abbonamento non è attivo.');
        }

        try {
            $subscription->update([
                'status' => 'cancelled',
                'end_date' => now(),
            ]);

            return back()->with('success', 'Abbonamento cancellato con successo.');

        } catch (\Exception $e) {
            return back()->with('error', 'Errore durante la cancellazione: ' . $e->getMessage());
        }
    }

    /**
     * Rinnova abbonamento
     */
    public function renew(UserSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        if ($subscription->status !== 'expired') {
            return back()->with('error', 'Questo abbonamento non è scaduto.');
        }

        try {
            $subscription->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($subscription->package->duration_days),
            ]);

            return back()->with('success', 'Abbonamento rinnovato con successo.');

        } catch (\Exception $e) {
            return back()->with('error', 'Errore durante il rinnovo: ' . $e->getMessage());
        }
    }

    /**
     * Confronto pacchetti
     */
    public function compare()
    {
        $packages = Package::active()->ordered()->get();
        $user = Auth::user();

        return view('premium.compare', compact('packages', 'user'));
    }

    /**
     * FAQ e supporto
     */
    public function faq()
    {
        return view('premium.faq');
    }
}
