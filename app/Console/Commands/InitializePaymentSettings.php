<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemSetting;

class InitializePaymentSettings extends Command
{
    protected $signature = 'settings:init-payment';
    protected $description = 'Inizializza le impostazioni di pagamento nel database';

    public function handle()
    {
        $this->info('Inizializzazione impostazioni di pagamento...');

        $settings = [
            [
                'key' => 'translation_commission_percentage',
                'value' => '0.10',
                'type' => 'float',
                'group' => 'payment',
                'display_name' => 'Commissione traduzioni (%)',
                'description' => 'Percentuale di commissione per i pagamenti di traduzione (es: 0.10 = 10%)'
            ],
            [
                'key' => 'translation_commission_fixed',
                'value' => '0.00',
                'type' => 'float',
                'group' => 'payment',
                'display_name' => 'Commissione fissa traduzioni (€)',
                'description' => 'Commissione fissa in euro per i pagamenti di traduzione'
            ],
            [
                'key' => 'payment_methods_enabled',
                'value' => json_encode(['stripe', 'paypal']),
                'type' => 'json',
                'group' => 'payment',
                'display_name' => 'Metodi di pagamento abilitati',
                'description' => 'Metodi di pagamento disponibili: stripe, paypal'
            ],
            [
                'key' => 'stripe_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'display_name' => 'Stripe abilitato',
                'description' => 'Abilita pagamenti con Stripe (carte di credito)'
            ],
            [
                'key' => 'paypal_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'payment',
                'display_name' => 'PayPal abilitato',
                'description' => 'Abilita pagamenti con PayPal'
            ],
            [
                'key' => 'stripe_public_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'Stripe Public Key (pk_test_...)',
                'description' => 'Chiave pubblica di Stripe per i pagamenti (inizia con pk_test_ o pk_live_)'
            ],
            [
                'key' => 'stripe_secret_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'Stripe Secret Key (sk_test_...)',
                'description' => 'Chiave segreta di Stripe per i pagamenti (inizia con sk_test_ o sk_live_)'
            ],
            [
                'key' => 'stripe_webhook_secret',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'Stripe Webhook Secret (whsec_...)',
                'description' => 'Chiave segreta per i webhook di Stripe (inizia con whsec_)'
            ],
            [
                'key' => 'stripe_mode',
                'value' => 'test',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'Modalità Stripe',
                'description' => 'Modalità di Stripe: test (sviluppo) o live (produzione)'
            ],
            [
                'key' => 'paypal_client_id',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'PayPal Client ID',
                'description' => 'Client ID di PayPal per i pagamenti'
            ],
            [
                'key' => 'paypal_client_secret',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'PayPal Client Secret',
                'description' => 'Client Secret di PayPal per i pagamenti'
            ],
            [
                'key' => 'paypal_mode',
                'value' => 'sandbox',
                'type' => 'string',
                'group' => 'payment',
                'display_name' => 'Modalità PayPal',
                'description' => 'Modalità di PayPal: sandbox (sviluppo) o live (produzione)'
            ]
        ];

        $created = 0;
        $updated = 0;

        foreach ($settings as $settingData) {
            $setting = SystemSetting::firstOrNew(['key' => $settingData['key']]);

            if ($setting->exists) {
                $this->line("Impostazione '{$settingData['key']}' già esistente - aggiornata");
                $updated++;
            } else {
                $this->line("Creata impostazione '{$settingData['key']}'");
                $created++;
            }

            $setting->fill($settingData);
            $setting->save();
        }

        $this->info("Completato! Create: {$created}, Aggiornate: {$updated}");

        return 0;
    }
}
