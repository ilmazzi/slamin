<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutoTranslationService;
use Illuminate\Support\Facades\File;

class TestAutoTranslation extends Command
{
    protected $signature = 'translation:test {--provider=google} {--from=en} {--to=it} {--text=Hello world}';
    protected $description = 'Testa il sistema di traduzione automatica';

    public function handle()
    {
        $this->info('🤖 TEST SISTEMA TRADUZIONE AUTOMATICA');
        $this->info('=====================================');
        $this->newLine();

        $translationService = new AutoTranslationService();

        // 1. Test configurazione
        $this->info('1️⃣ Verifica configurazione...');
        if (!$translationService->isConfigured()) {
            $this->error('❌ Servizio di traduzione non configurato!');
            $this->info('Configura le seguenti variabili nel file .env:');
            $this->info('TRANSLATION_PROVIDER=google|deepl|openai');
            $this->info('TRANSLATION_API_KEY=your_api_key_here');
            return 1;
        }
        $this->info('✅ Servizio configurato');

        // 2. Test connessione
        $this->info('2️⃣ Test connessione...');
        if (!$translationService->testConnection()) {
            $this->error('❌ Connessione al servizio fallita!');
            return 1;
        }
        $this->info('✅ Connessione OK');

        // 3. Test traduzione singola
        $this->info('3️⃣ Test traduzione singola...');
        $from = $this->option('from');
        $to = $this->option('to');
        $text = $this->option('text');

        $this->info("   Traduci: '{$text}' da {$from} a {$to}");

        try {
            $translation = $translationService->translate($text, $from, $to);
            
            if ($translation && $translation !== $text) {
                $this->info("   ✅ Traduzione: '{$translation}'");
            } else {
                $this->error("   ❌ Traduzione fallita o identica al testo originale");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Errore traduzione: " . $e->getMessage());
            return 1;
        }

        // 4. Test traduzione array
        $this->info('4️⃣ Test traduzione array...');
        $testArray = [
            'welcome' => 'Welcome to our platform',
            'login' => 'Please login to continue',
            'register' => 'Create a new account',
            'dashboard' => 'Your dashboard'
        ];

        try {
            $translatedArray = $translationService->translateArray($testArray, $from, $to);
            
            $this->info('   ✅ Array tradotto:');
            foreach ($translatedArray as $key => $value) {
                $this->line("      {$key}: '{$value}'");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Errore traduzione array: " . $e->getMessage());
            return 1;
        }

        // 5. Test con file di traduzione reali
        $this->info('5️⃣ Test con file di traduzione reali...');
        $italianFile = lang_path('it/admin.php');
        
        if (File::exists($italianFile)) {
            $italianTranslations = include $italianFile;
            
            // Prendi solo le prime 5 chiavi per il test
            $testTranslations = array_slice($italianTranslations, 0, 5, true);
            
            $this->info("   Traduci {$from} -> {$to} per " . count($testTranslations) . " chiavi");
            
            try {
                $translatedKeys = $translationService->translateArray($testTranslations, 'it', $to);
                
                $this->info('   ✅ Traduzioni completate:');
                foreach ($translatedKeys as $key => $value) {
                    $this->line("      {$key}: '{$value}'");
                }
            } catch (\Exception $e) {
                $this->error("   ❌ Errore traduzione file: " . $e->getMessage());
                return 1;
            }
        } else {
            $this->warn("   ⚠️  File italiano non trovato, salto test file reali");
        }

        // 6. Statistiche
        $this->info('6️⃣ Statistiche...');
        $stats = $translationService->getUsageStats();
        
        $this->info("   Provider: {$stats['provider']}");
        $this->info("   Configurato: " . ($stats['configured'] ? 'Sì' : 'No'));
        $this->info("   Connessione: " . ($stats['connection_ok'] ? 'OK' : 'Fallita'));
        $this->info("   Traduzioni totali: {$stats['total_translations']}");

        $this->newLine();
        $this->info('🎉 Test completato con successo!');
        $this->info('Il sistema di traduzione automatica è pronto per l\'uso.');
        
        return 0;
    }
} 