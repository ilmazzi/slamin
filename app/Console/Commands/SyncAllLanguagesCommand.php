<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\TranslationManagementController;

class SyncAllLanguagesCommand extends Command
{
    protected $signature = 'translations:sync-all';
    protected $description = 'Sincronizza tutte le lingue partendo dall\'italiano';

    public function handle()
    {
        $this->info('🌍 Avvio sincronizzazione di tutte le lingue...');

        try {
            $controller = new TranslationManagementController();

            // Simula una richiesta per il metodo syncAllLanguages
            $request = new \Illuminate\Http\Request();
            $response = $controller->syncAllLanguages($request);
            $data = json_decode($response->getContent(), true);

            if ($data['success']) {
                $this->info('✅ Sincronizzazione completata con successo!');

                if (isset($data['results'])) {
                    $this->info('📊 Risultati per lingua:');
                    foreach ($data['results'] as $lang => $result) {
                        $this->line("  • {$lang}: {$result['files_processed']} file, {$result['keys_added']} chiavi aggiunte, {$result['keys_updated']} chiavi aggiornate");

                        if (!empty($result['errors'])) {
                            $this->warn("    Errori: " . implode(', ', $result['errors']));
                        }
                    }
                }
            } else {
                $this->error('❌ Errore durante la sincronizzazione: ' . $data['message']);
                return self::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
