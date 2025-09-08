<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\SystemSetting;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
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
     * Mostra la pagina di gestione conti di pagamento
     */
    public function index()
    {
        $user = Auth::user();

        return view('profile.payment-accounts', compact('user'));
    }

    /**
     * Crea un account Stripe Connect
     */
    public function createStripeAccount()
    {
        try {
            $user = Auth::user();

            Log::info('Creating Stripe Connect account', [
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);

            // Verifica se Stripe Connect è abilitato
            if (!SystemSetting::get('stripe_connect_enabled', 'false')) {
                return redirect()->back()->with('error', 'Stripe Connect non è abilitato. Contatta l\'amministratore per abilitarlo.');
            }

            // Crea account Stripe Connect Express
            $account = Account::create([
                'type' => 'express',
                'country' => 'IT',
                'email' => $user->email,
                'capabilities' => [
                    'transfers' => ['requested' => true],
                    'card_payments' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'individual' => [
                    'email' => $user->email,
                    'first_name' => explode(' ', $user->name)[0] ?? '',
                    'last_name' => explode(' ', $user->name)[1] ?? '',
                ],
                'settings' => [
                    'payouts' => [
                        'schedule' => [
                            'interval' => 'daily',
                        ],
                    ],
                ],
            ]);

            // Salva l'account ID
            $user->update([
                'stripe_connect_account_id' => $account->id,
                'stripe_connect_status' => 'pending',
                'stripe_connected_at' => now(),
            ]);

            Log::info('Stripe account created successfully', [
                'user_id' => $user->id,
                'stripe_account_id' => $account->id,
            ]);

            // Crea link di onboarding
            $accountLink = AccountLink::create([
                'account' => $account->id,
                'refresh_url' => route('profile.payment-accounts.index'),
                'return_url' => route('profile.payment-accounts.index'),
                'type' => 'account_onboarding',
            ]);

            Log::info('Stripe onboarding link created', [
                'user_id' => $user->id,
                'onboarding_url' => $accountLink->url,
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
        } catch (\Exception $e) {
            Log::error('Stripe Connect account creation failed - General error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error',
                'Errore nella configurazione di Stripe. Riprova più tardi.'
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

            if ($status === 'active') {
                return redirect()->back()->with('success',
                    'Account Stripe configurato con successo! Ora puoi ricevere i pagamenti automaticamente.'
                );
            } elseif ($status === 'pending') {
                return redirect()->back()->with('warning',
                    'Account Stripe non ancora completo. Completa la configurazione per ricevere i pagamenti.'
                );
            } else {
                return redirect()->back()->with('error',
                    'Account Stripe limitato. Contatta il supporto per assistenza.'
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
     * Configura PayPal
     */
    public function setupPayPal(Request $request)
    {
        $request->validate([
            'paypal_email' => 'required|email',
        ]);

        $user = Auth::user();
        $user->update([
            'paypal_email' => $request->paypal_email,
            'paypal_verified' => false, // Da verificare manualmente
            'paypal_connected_at' => now(),
        ]);

        return redirect()->back()->with('success',
            'Email PayPal configurata. L\'admin verificherà l\'account a breve.'
        );
    }

    /**
     * Configura dettagli bancari per payout manuali
     */
    public function setupBankDetails(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_iban' => 'required|string|max:34',
            'bank_swift' => 'nullable|string|max:11',
            'bank_account_holder' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'bank_name' => $request->bank_name,
            'bank_iban' => $request->bank_iban,
            'bank_swift' => $request->bank_swift,
            'bank_account_holder' => $request->bank_account_holder,
        ]);

        return redirect()->back()->with('success',
            'Dettagli bancari configurati con successo.'
        );
    }

    /**
     * Imposta il metodo di payout preferito
     */
    public function setPreferredPayoutMethod(Request $request)
    {
        $request->validate([
            'preferred_payout_method' => 'required|in:stripe,paypal,manual',
        ]);

        $user = Auth::user();
        $user->update([
            'preferred_payout_method' => $request->preferred_payout_method,
        ]);

        return redirect()->back()->with('success',
            'Metodo di payout preferito aggiornato.'
        );
    }

    /**
     * Disconnette un account
     */
    public function disconnectAccount(Request $request)
    {
        $request->validate([
            'account_type' => 'required|in:stripe,paypal',
        ]);

        $user = Auth::user();

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

        return redirect()->back()->with('success',
            'Account disconnesso con successo.'
        );
    }

    /**
     * Crea link per completare onboarding Stripe
     */
    public function createStripeOnboardingLink()
    {
        try {
            $user = Auth::user();

            if (!$user->stripe_connect_account_id) {
                return redirect()->back()->with('error', 'Account Stripe non configurato');
            }

            $accountLink = AccountLink::create([
                'account' => $user->stripe_connect_account_id,
                'refresh_url' => route('profile.payment-accounts.index'),
                'return_url' => route('profile.payment-accounts.index'),
                'type' => 'account_onboarding',
            ]);

            return redirect($accountLink->url);

        } catch (ApiErrorException $e) {
            Log::error('Stripe onboarding link creation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()->with('error',
                'Errore nella creazione del link di onboarding: ' . $e->getMessage()
            );
        }
    }
}
