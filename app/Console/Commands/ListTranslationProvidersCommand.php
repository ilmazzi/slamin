<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TranslationApiService;

class ListTranslationProvidersCommand extends Command
{
    protected $signature = 'translations:list-providers';
    protected $description = 'List available translation providers and their status';

    public function handle()
    {
        $this->info("🌍 Available Translation Providers:");
        $this->newLine();

        $translationService = new TranslationApiService();
        $providers = $translationService->getProviders();
        $supportedLanguages = $translationService->getSupportedLanguages();

        foreach ($providers as $key => $provider) {
            $this->line("🔹 {$key}: {$provider['name']}");
            $this->line("   URL: {$provider['url']}");
            $this->line("   Requires Key: " . ($provider['requires_key'] ? 'Yes' : 'No'));
            $this->line("   Rate Limit: " . ($provider['rate_limit'] ?? 'N/A') . " requests");
            $this->line("   Cost: $" . ($provider['cost_per_1m_chars'] ?? 'N/A') . "/1M chars");

            // Test connection
            $this->line("   Testing connection...");
            $testResult = $translationService->testConnection();
            if ($testResult['success']) {
                $this->line("   ✅ Status: Connected");
            } else {
                $this->line("   ❌ Status: Failed - " . $testResult['message']);
            }
            $this->newLine();
        }

        $this->info("📝 Usage Examples:");
        $this->line("  php artisan translations:translate-page en admin --provider=libre");
        $this->line("  php artisan translations:translate-page es common --provider=google --api-key=YOUR_KEY");
        $this->line("  php artisan translations:translate-page fr auth --provider=deepl --api-key=YOUR_KEY --force");

        return self::SUCCESS;
    }
}
