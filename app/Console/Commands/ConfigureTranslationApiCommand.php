<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TranslationApiService;

class ConfigureTranslationApiCommand extends Command
{
    protected $signature = 'translations:configure-api
                            {--provider= : Translation provider to configure}
                            {--api-key= : API key for the provider}
                            {--test : Test the configuration}';

    protected $description = 'Configure translation API services';

    public function handle()
    {
        $provider = $this->option('provider');
        $apiKey = $this->option('api-key');
        $test = $this->option('test');

        if (!$provider) {
            $this->showProviders();
            return self::SUCCESS;
        }

        if ($test) {
            $this->testProvider($provider, $apiKey);
            return self::SUCCESS;
        }

        if (!$apiKey) {
            $apiKey = $this->ask("Enter API key for {$provider}");
        }

        if (empty($apiKey)) {
            $this->error("API key is required");
            return self::FAILURE;
        }

        $this->updateEnvFile($provider, $apiKey);
        $this->info("✅ Configuration updated successfully!");
        $this->info("Provider: {$provider}");
        $this->info("API Key: " . substr($apiKey, 0, 8) . "...");

        return self::SUCCESS;
    }

    private function showProviders()
    {
        $this->info("🌍 Available Translation Providers:");
        $this->newLine();

        $providers = config('translation.providers');

        foreach ($providers as $key => $provider) {
            $this->line("🔹 {$key}: {$provider['name']}");
            $this->line("   URL: {$provider['url']}");
            $this->line("   Requires Key: " . ($provider['requires_key'] ? 'Yes' : 'No'));
            $this->line("   Rate Limit: {$provider['rate_limit']} requests");
            $this->line("   Cost: \${$provider['cost_per_1m_chars']}/1M chars");
            $this->newLine();
        }

        $this->info("Usage:");
        $this->line("  php artisan translations:configure-api --provider=google --api-key=YOUR_KEY");
        $this->line("  php artisan translations:configure-api --provider=google --test");
    }

    private function testProvider($provider, $apiKey)
    {
        $this->info("🧪 Testing {$provider} API...");

        $translationService = new TranslationApiService($provider, $apiKey);
        $result = $translationService->testConnection();

        if ($result['success']) {
            $this->info("✅ {$result['message']}");
        } else {
            $this->error("❌ {$result['message']}");
        }
    }

    private function updateEnvFile($provider, $apiKey)
    {
        $envFile = base_path('.env');

        if (!file_exists($envFile)) {
            $this->error(".env file not found");
            return;
        }

        $envContent = file_get_contents($envFile);

        // Update or add TRANSLATION_PROVIDER
        if (preg_match('/^TRANSLATION_PROVIDER=.*/m', $envContent)) {
            $envContent = preg_replace('/^TRANSLATION_PROVIDER=.*/m', "TRANSLATION_PROVIDER={$provider}", $envContent);
        } else {
            $envContent .= "\nTRANSLATION_PROVIDER={$provider}\n";
        }

        // Update or add TRANSLATION_API_KEY
        if (preg_match('/^TRANSLATION_API_KEY=.*/m', $envContent)) {
            $envContent = preg_replace('/^TRANSLATION_API_KEY=.*/m', "TRANSLATION_API_KEY={$apiKey}", $envContent);
        } else {
            $envContent .= "TRANSLATION_API_KEY={$apiKey}\n";
        }

        file_put_contents($envFile, $envContent);
    }
}
