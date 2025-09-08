<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\TranslationPayment;
use App\Models\Notification;
use App\Services\PayoutService;
use App\Models\SystemSetting;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    public function __construct()
    {
        // Configura Stripe con le impostazioni dal database
        $stripeSecretKey = SystemSetting::get('stripe_secret_key');
        if ($stripeSecretKey) {
            Stripe::setApiKey($stripeSecretKey);
        }
    }

    /**
     * Gestisce i webhook di Stripe
     */
    public function stripe(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook.secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook: Invalid payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook: Invalid signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        // Gestisci l'evento
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
            default:
                Log::info('Stripe webhook: Unhandled event type', ['type' => $event->type]);
        }

        return response('OK', 200);
    }

    /**
     * Gestisce il successo di un PaymentIntent
     */
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        try {
            $payment = TranslationPayment::where('stripe_payment_intent_id', $paymentIntent->id)->first();

            if (!$payment) {
                Log::warning('Stripe webhook: Payment not found', ['payment_intent_id' => $paymentIntent->id]);
                return;
            }

            // Aggiorna il pagamento
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                'stripe_metadata' => $paymentIntent->metadata->toArray(),
            ]);

            // Carica le relazioni necessarie
            $payment->load(['gigApplication.gig.poem', 'translator']);

            // Crea notifica per il traduttore
            Notification::create([
                'user_id' => $payment->translator_id,
                'type' => 'translation_payment_received',
                'title' => 'Pagamento Ricevuto!',
                'message' => "Hai ricevuto il pagamento di €{$payment->amount} per la traduzione di '{$payment->gigApplication->gig->poem->title}'",
                'action_url' => route('translations.payment.success', $payment),
                'data' => [
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'poem_title' => $payment->gigApplication->gig->poem->title,
                ],
                'is_read' => false,
            ]);

            // Avvia il trasferimento automatico al traduttore
            $payoutService = new PayoutService();
            $payoutResult = $payoutService->transferToTranslator($payment);

            Log::info('Stripe webhook: Payment completed successfully', [
                'payment_id' => $payment->id,
                'payment_intent_id' => $paymentIntent->id,
                'payout_result' => $payoutResult,
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe webhook: Error handling payment success', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }

    /**
     * Gestisce il fallimento di un PaymentIntent
     */
    private function handlePaymentIntentFailed($paymentIntent)
    {
        try {
            $payment = TranslationPayment::where('stripe_payment_intent_id', $paymentIntent->id)->first();

            if (!$payment) {
                Log::warning('Stripe webhook: Payment not found for failed payment', ['payment_intent_id' => $paymentIntent->id]);
                return;
            }

            // Aggiorna il pagamento
            $payment->update([
                'status' => 'failed',
                'stripe_metadata' => $paymentIntent->metadata->toArray(),
            ]);

            Log::info('Stripe webhook: Payment failed', [
                'payment_id' => $payment->id,
                'payment_intent_id' => $paymentIntent->id,
                'failure_reason' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe webhook: Error handling payment failure', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }
}
