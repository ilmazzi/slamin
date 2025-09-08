<?php

namespace App\Services;

use App\Models\SystemSetting;

class PaymentService
{
    /**
     * Calcola la commissione per un pagamento di traduzione
     */
    public static function calculateCommission(float $amount): array
    {
        $commissionRate = SystemSetting::get('translation_commission_rate', 0.10);
        $commissionFixed = SystemSetting::get('translation_commission_fixed', 0.00);

        // Calcola commissione percentuale
        $rateCommission = $amount * $commissionRate;

        // Aggiunge commissione fissa
        $totalCommission = $rateCommission + $commissionFixed;

        // Importo che va al traduttore
        $translatorAmount = $amount - $totalCommission;

        return [
            'total_amount' => $amount,
            'commission_rate' => $commissionRate,
            'commission_fixed' => $commissionFixed,
            'commission_total' => $totalCommission,
            'translator_amount' => max(0, $translatorAmount), // Non può essere negativo
            'platform_amount' => $totalCommission,
        ];
    }

    /**
     * Ottiene i metodi di pagamento abilitati
     */
    public static function getEnabledPaymentMethods(): array
    {
        $enabledMethods = SystemSetting::get('payment_methods_enabled', ['stripe']);
        $availableMethods = [];

        if (in_array('stripe', $enabledMethods) && SystemSetting::get('stripe_enabled', true)) {
            $availableMethods['stripe'] = [
                'name' => 'Carta di Credito',
                'icon' => 'fas fa-credit-card',
                'description' => 'Paga con carta di credito o debito'
            ];
        }

        if (in_array('paypal', $enabledMethods) && SystemSetting::get('paypal_enabled', true)) {
            $availableMethods['paypal'] = [
                'name' => 'PayPal',
                'icon' => 'fab fa-paypal',
                'description' => 'Paga con PayPal'
            ];
        }

        return $availableMethods;
    }

    /**
     * Verifica se un metodo di pagamento è abilitato
     */
    public static function isPaymentMethodEnabled(string $method): bool
    {
        $enabledMethods = SystemSetting::get('payment_methods_enabled', ['stripe']);

        if (!in_array($method, $enabledMethods)) {
            return false;
        }

        return SystemSetting::get($method . '_enabled', true);
    }

    /**
     * Formatta l'importo per la visualizzazione
     */
    public static function formatAmount(float $amount, string $currency = 'EUR'): string
    {
        return number_format($amount, 2) . ' ' . $currency;
    }

    /**
     * Converte l'importo in centesimi (per Stripe)
     */
    public static function toCents(float $amount): int
    {
        return (int) round($amount * 100);
    }

    /**
     * Converte i centesimi in euro
     */
    public static function fromCents(int $cents): float
    {
        return $cents / 100;
    }
}
