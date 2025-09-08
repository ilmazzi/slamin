<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\GigApplication;
use App\Models\TranslationPayment;
use App\Models\Notification;
use App\Services\PaymentService;
use App\Models\SystemSetting;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class TranslationPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Configura Stripe con le impostazioni dal database
        $stripeSecretKey = SystemSetting::get('stripe_secret_key');
        if ($stripeSecretKey) {
            // Gestisci il caso in cui la chiave sia salvata come JSON
            if (is_string($stripeSecretKey) && $this->isJson($stripeSecretKey)) {
                $decoded = json_decode($stripeSecretKey, true);
                if (isset($decoded['value'])) {
                    $stripeSecretKey = $decoded['value'];
                }
            }
            Stripe::setApiKey($stripeSecretKey);
        }
    }

    /**
     * Controlla se una stringa è JSON valido
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Mostra il form di pagamento per una traduzione accettata
     */
    public function show(GigApplication $application)
    {
        // Carica le relazioni necessarie
        $application->load(['gig.poem', 'user', 'gig.user']);

        // Verifica che l'utente sia coinvolto nella candidatura (proprietario del gig o candidato)
        if (!in_array(Auth::id(), [$application->gig->user_id, $application->user_id])) {
            abort(403, 'Non hai i permessi per accedere a questa pagina');
        }

        // Verifica che la candidatura sia accettata
        if ($application->status !== 'accepted') {
            abort(403, 'Questa candidatura non è stata ancora accettata');
        }

        // Verifica che non ci sia già un pagamento per questa candidatura
        $existingPayment = TranslationPayment::where('gig_application_id', $application->id)->first();
        if ($existingPayment && $existingPayment->isCompleted()) {
            return redirect()->route('translations.payment.success', $existingPayment)
                ->with('info', 'Pagamento già completato per questa traduzione');
        }

        // Se è il traduttore, mostra solo i dettagli (non può pagare)
        if ($application->user_id === Auth::id()) {
            return view('translations.payment-details', compact('application', 'existingPayment'));
        }

        // Se è il proprietario del gig, mostra il form di pagamento
        return view('translations.payment', compact('application', 'existingPayment'));
    }

    /**
     * Crea un PaymentIntent Stripe per la traduzione
     */
    public function createPaymentIntent(Request $request, GigApplication $application)
    {
        // Verifica che l'utente sia il proprietario del gig
        if ($application->gig->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorizzato'], 403);
        }

        // Verifica che la candidatura sia accettata
        if ($application->status !== 'accepted') {
            return response()->json(['error' => 'Candidatura non accettata'], 400);
        }

        try {
            // Calcola l'importo e le commissioni
            $amount = $application->gig->compensation;
            $commissionData = PaymentService::calculateCommission($amount);

            // Crea il record di pagamento
            $payment = TranslationPayment::create([
                'gig_application_id' => $application->id,
                'poem_id' => $application->gig->poem_id,
                'client_id' => Auth::id(),
                'translator_id' => $application->user_id,
                'amount' => $amount,
                'currency' => 'EUR',
                'status' => 'pending',
                'commission_rate' => $commissionData['commission_rate'],
                'commission_fixed' => $commissionData['commission_fixed'],
                'commission_total' => $commissionData['commission_total'],
                'translator_amount' => $commissionData['translator_amount'],
                'platform_amount' => $commissionData['platform_amount'],
            ]);

            // Crea PaymentIntent con Stripe
            try {
                // Assicurati che Stripe sia configurato correttamente
                $stripeSecretKey = SystemSetting::get('stripe_secret_key');
                if ($stripeSecretKey) {
                    // Gestisci il caso in cui la chiave sia salvata come JSON
                    if (is_string($stripeSecretKey) && $this->isJson($stripeSecretKey)) {
                        $decoded = json_decode($stripeSecretKey, true);
                        if (isset($decoded['value'])) {
                            $stripeSecretKey = $decoded['value'];
                        }
                    }
                    Stripe::setApiKey($stripeSecretKey);
                }

                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount * 100, // Stripe usa centesimi
                    'currency' => 'eur',
                    'metadata' => [
                        'gig_application_id' => $application->id,
                        'poem_id' => $application->gig->poem_id,
                        'client_id' => Auth::id(),
                        'translator_id' => $application->user_id,
                        'payment_id' => $payment->id,
                    ],
                    'description' => "Traduzione: {$application->gig->poem->title}",
                ]);

                // Aggiorna il record con l'ID del pagamento
                $payment->update([
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => 'processing',
                    'payment_method' => 'stripe',
                ]);

                return response()->json([
                    'success' => true,
                    'payment_intent_id' => $paymentIntent->id,
                    'client_secret' => $paymentIntent->client_secret,
                    'amount' => $amount * 100,
                    'currency' => 'eur',
                ]);

            } catch (ApiErrorException $e) {
                Log::error('Stripe PaymentIntent creation failed', [
                    'error' => $e->getMessage(),
                    'application_id' => $application->id,
                    'amount' => $amount,
                ]);

                return response()->json([
                    'error' => 'Errore durante la creazione del pagamento Stripe: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore durante la creazione del pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Conferma il pagamento completato
     */
    public function confirmPayment(Request $request, GigApplication $application)
    {
        $request->validate([
            'payment_intent_id' => 'nullable|string',
            'payment_method' => 'required|string|in:stripe,paypal',
            'paypal_order_id' => 'nullable|string',
            'paypal_payer_id' => 'nullable|string',
        ]);

        try {
            // Trova il pagamento in base al metodo
            if ($request->payment_method === 'stripe') {
                $payment = TranslationPayment::where('gig_application_id', $application->id)
                    ->where('stripe_payment_intent_id', $request->payment_intent_id)
                    ->first();
            } else {
                // Per PayPal, cerchiamo l'ultimo pagamento pending per questa applicazione
                $payment = TranslationPayment::where('gig_application_id', $application->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();
            }

            if (!$payment) {
                return response()->json(['error' => 'Pagamento non trovato'], 404);
            }

            // Verifica il pagamento con il provider
            $updateData = [
                'status' => 'completed',
                'paid_at' => now(),
                'payment_method' => $request->payment_method,
            ];

            if ($request->payment_method === 'stripe') {
                // Verifica con Stripe
                try {
                    // Assicurati che Stripe sia configurato correttamente
                    $stripeSecretKey = SystemSetting::get('stripe_secret_key');
                    if ($stripeSecretKey) {
                        // Gestisci il caso in cui la chiave sia salvata come JSON
                        if (is_string($stripeSecretKey) && $this->isJson($stripeSecretKey)) {
                            $decoded = json_decode($stripeSecretKey, true);
                            if (isset($decoded['value'])) {
                                $stripeSecretKey = $decoded['value'];
                            }
                        }
                        Stripe::setApiKey($stripeSecretKey);
                    }

                    $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

                    if ($paymentIntent->status === 'succeeded') {
                        $updateData['stripe_charge_id'] = $paymentIntent->charges->data[0]->id ?? null;
                        $updateData['stripe_metadata'] = $paymentIntent->metadata->toArray();
                    } else {
                        return response()->json(['error' => 'Pagamento non completato'], 400);
                    }
                } catch (ApiErrorException $e) {
                    Log::error('Stripe payment verification failed', [
                        'error' => $e->getMessage(),
                        'payment_intent_id' => $request->payment_intent_id,
                    ]);
                    return response()->json(['error' => 'Errore nella verifica del pagamento Stripe'], 500);
                }
            } else {
                // Per PayPal, verifica con l'API PayPal
                $updateData['stripe_metadata'] = [
                    'paypal_order_id' => $request->paypal_order_id,
                    'paypal_payer_id' => $request->paypal_payer_id,
                ];

                // TODO: Implementare verifica PayPal reale
                Log::info('PayPal payment confirmed', [
                    'order_id' => $request->paypal_order_id,
                    'payer_id' => $request->paypal_payer_id,
                ]);
            }

            $payment->update($updateData);

            // Crea notifica per il traduttore
            Notification::create([
                'user_id' => $application->user_id,
                'type' => 'translation_payment_received',
                'title' => 'Pagamento Ricevuto!',
                'message' => "Hai ricevuto il pagamento di €{$payment->amount} per la traduzione di '{$application->gig->poem->title}'",
                'action_url' => route('translations.payment.success', $payment),
                'data' => [
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'poem_title' => $application->gig->poem->title,
                ],
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pagamento confermato con successo',
                'redirect_url' => route('translations.payment.success', $payment),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore durante la conferma del pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pagina di successo pagamento
     */
    public function success(TranslationPayment $payment)
    {
        // Verifica che l'utente sia coinvolto nel pagamento
        if ($payment->client_id !== Auth::id() && $payment->translator_id !== Auth::id()) {
            abort(403, 'Non hai i permessi per accedere a questa pagina');
        }

        // Carica le relazioni necessarie
        $payment->load(['poem', 'client', 'translator', 'gigApplication.gig']);

        return view('translations.payment-success', compact('payment'));
    }

    /**
     * Lista pagamenti dell'utente
     */
    public function index()
    {
        $user = Auth::user();

        // Pagamenti come cliente
        $paymentsAsClient = TranslationPayment::with(['poem', 'translator', 'gigApplication'])
            ->where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'client_page');

        // Pagamenti come traduttore
        $paymentsAsTranslator = TranslationPayment::with(['poem', 'client', 'gigApplication'])
            ->where('translator_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'translator_page');

        return view('translations.payments', compact('paymentsAsClient', 'paymentsAsTranslator'));
    }
}
