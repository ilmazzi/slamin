<?php

namespace App\Services;

use App\Models\TranslationPayment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Transfer;
use Stripe\Exception\ApiErrorException;

class PayoutService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Trasferisce i soldi al traduttore dopo un pagamento completato
     */
    public function transferToTranslator(TranslationPayment $payment)
    {
        try {
            // Verifica che il pagamento sia completato
            if ($payment->status !== 'completed') {
                throw new \Exception('Pagamento non completato');
            }

            // Verifica che non sia già stato trasferito
            if ($payment->payout_status === 'transferred') {
                return ['success' => true, 'message' => 'Già trasferito'];
            }

            $translator = User::find($payment->translator_id);
            if (!$translator) {
                throw new \Exception('Traduttore non trovato');
            }

            // Verifica che il traduttore abbia un account Stripe Connect
            if (!$translator->stripe_connect_account_id) {
                // Per ora, segna come "da trasferire manualmente"
                $payment->update([
                    'payout_status' => 'pending_manual',
                    'payout_notes' => 'Traduttore senza account Stripe Connect'
                ]);

                return [
                    'success' => false,
                    'message' => 'Traduttore senza account Stripe Connect - trasferimento manuale richiesto'
                ];
            }

            // Trasferisci via Stripe Connect
            $transfer = Transfer::create([
                'amount' => $payment->translator_amount * 100, // Stripe usa centesimi
                'currency' => 'eur',
                'destination' => $translator->stripe_connect_account_id,
                'transfer_group' => 'translation_payment_' . $payment->id,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'poem_title' => $payment->poem->title ?? 'N/A',
                    'translator_id' => $translator->id,
                ],
            ]);

            // Aggiorna il pagamento
            $payment->update([
                'payout_status' => 'transferred',
                'payout_transfer_id' => $transfer->id,
                'payout_date' => now(),
            ]);

            Log::info('Payment transferred to translator', [
                'payment_id' => $payment->id,
                'translator_id' => $translator->id,
                'amount' => $payment->translator_amount,
                'transfer_id' => $transfer->id,
            ]);

            return [
                'success' => true,
                'message' => 'Trasferimento completato',
                'transfer_id' => $transfer->id,
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe transfer failed', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'translator_id' => $payment->translator_id,
            ]);

            $payment->update([
                'payout_status' => 'failed',
                'payout_notes' => 'Errore Stripe: ' . $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Errore nel trasferimento: ' . $e->getMessage(),
            ];

        } catch (\Exception $e) {
            Log::error('Transfer failed', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);

            return [
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Trasferisce tutti i pagamenti pending
     */
    public function processPendingPayouts()
    {
        $pendingPayments = TranslationPayment::where('status', 'completed')
            ->where('payout_status', 'pending')
            ->with(['translator', 'poem'])
            ->get();

        $results = [];
        foreach ($pendingPayments as $payment) {
            $results[] = $this->transferToTranslator($payment);
        }

        return $results;
    }

    /**
     * Crea un payout manuale (per traduttori senza Stripe Connect)
     */
    public function createManualPayout(TranslationPayment $payment, $notes = null)
    {
        $payment->update([
            'payout_status' => 'manual_required',
            'payout_notes' => $notes ?? 'Trasferimento manuale richiesto',
        ]);

        // Qui potresti inviare una notifica all'admin
        // o creare un task per il trasferimento manuale

        return [
            'success' => true,
            'message' => 'Payout manuale creato',
        ];
    }
}
