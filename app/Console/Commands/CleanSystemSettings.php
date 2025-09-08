<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemSetting;

class CleanSystemSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulisce le impostazioni di sistema corrotte da JSON annidati';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Pulizia delle impostazioni di sistema...');

        $settings = SystemSetting::all();
        $cleaned = 0;

        foreach ($settings as $setting) {
            $value = $setting->value;
            $originalValue = $value;

            // Gestisci i valori JSON che potrebbero essere stati salvati come stringhe
            if (is_string($value) && $this->isJson($value)) {
                $decoded = json_decode($value, true);
                if (isset($decoded['value'])) {
                    // Se il valore è ancora JSON, estrai il valore interno
                    if (is_string($decoded['value']) && $this->isJson($decoded['value'])) {
                        $innerDecoded = json_decode($decoded['value'], true);
                        if (isset($innerDecoded['value'])) {
                            $value = $innerDecoded['value'];
                        } else {
                            $value = $decoded['value'];
                        }
                    } else {
                        $value = $decoded['value'];
                    }
                }
            }

            // Se il valore è cambiato, aggiorna
            if ($value !== $originalValue) {
                $setting->value = $value;
                $setting->save();
                $cleaned++;
                $this->line("Pulito: {$setting->key} - {$originalValue} -> {$value}");
            }
        }

        $this->info("Pulizia completata! {$cleaned} impostazioni corrette.");
    }

    /**
     * Controlla se una stringa è JSON valido
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
