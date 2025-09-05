<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class AvailabilitySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'availability_options_limit',
                'value' => '10',
                'type' => 'integer',
                'group' => 'availability',
                'display_name' => 'Limite Opzioni Gratuite',
                'description' => 'Numero massimo di opzioni di disponibilità per utenti gratuiti',
                'is_public' => false,
            ],
            [
                'key' => 'availability_options_premium_limit',
                'value' => '50',
                'type' => 'integer',
                'group' => 'availability',
                'display_name' => 'Limite Opzioni Premium',
                'description' => 'Numero massimo di opzioni di disponibilità per utenti premium/organizzazioni',
                'is_public' => false,
            ],
            [
                'key' => 'availability_default_deadline_days',
                'value' => '7',
                'type' => 'integer',
                'group' => 'availability',
                'display_name' => 'Scadenza Default (giorni)',
                'description' => 'Numero di giorni di default per la scadenza delle risposte di disponibilità',
                'is_public' => false,
            ],
            [
                'key' => 'availability_reminder_days',
                'value' => '2',
                'type' => 'integer',
                'group' => 'availability',
                'display_name' => 'Giorni Promemoria',
                'description' => 'Giorni prima della scadenza per inviare promemoria ai partecipanti',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
