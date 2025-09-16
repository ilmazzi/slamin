<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Services\TranslationApiService;

class TranslateAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:translate-all-api
                            {--provider=libre : Translation provider (google, deepl, microsoft, libre)}
                            {--api-key= : API key for the translation service}
                            {--force : Force translation even if target file exists}
                            {--dry-run : Show what would be translated without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate all translation files from Italian to all supported languages';

    /**
     * Supported languages
     */
    protected $supportedLanguages = ['en', 'fr', 'es', 'de', 'pt'];

    /**
     * Translation files to process
     */
    protected $translationFiles = [
        'admin',
        'common',
        'auth',
        'dashboard',
        'events',
        'poems',
        'videos',
        'articles',
        'profile',
        'carousel'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $provider = $this->option('provider');
        $apiKey = $this->option('api-key');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        $this->info("🌍 Starting automatic translation of all files...");
        $this->info("📋 Provider: {$provider}");
        $this->info("🔑 API Key: " . ($apiKey ? 'Provided' : 'Not provided'));
        $this->info("⚡ Force: " . ($force ? 'Yes' : 'No'));
        $this->info("🔍 Dry Run: " . ($dryRun ? 'Yes' : 'No'));
        $this->newLine();

        // Initialize translation service
        $translationService = new TranslationApiService($provider, $apiKey);

        // Test API connection
        $this->info("🔌 Testing API connection...");
        try {
            $testResult = $translationService->translate('Test', 'en', 'it');
            if ($testResult === 'Test') {
                $this->warn("⚠️  API test returned original text - API might not be working");
            } else {
                $this->info("✅ API connection successful");
            }
        } catch (\Exception $e) {
            $this->error("❌ API connection failed: " . $e->getMessage());
            return 1;
        }

        $this->newLine();

        $totalFiles = count($this->translationFiles);
        $totalLanguages = count($this->supportedLanguages);
        $totalTranslations = $totalFiles * $totalLanguages;

        $this->info("📊 Translation Plan:");
        $this->info("   📁 Files: {$totalFiles}");
        $this->info("   🌍 Languages: {$totalLanguages}");
        $this->info("   🔄 Total translations: {$totalTranslations}");
        $this->newLine();

        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No actual translations will be performed");
            $this->newLine();
        }

        $progressBar = $this->output->createProgressBar($totalTranslations);
        $progressBar->start();

        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;

        foreach ($this->translationFiles as $file) {
            foreach ($this->supportedLanguages as $language) {
                try {
                    $result = $this->translateFile($file, $language, $translationService, $force, $dryRun);

                    if ($result['status'] === 'success') {
                        $successCount++;
                        $this->line(" ✅ {$file}.php → {$language}");
                    } elseif ($result['status'] === 'skipped') {
                        $skippedCount++;
                        $this->line(" ⏭️  {$file}.php → {$language} (skipped)");
                    } else {
                        $errorCount++;
                        $this->line(" ❌ {$file}.php → {$language} (error)");
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $this->line(" ❌ {$file}.php → {$language} (exception: " . $e->getMessage() . ")");
                }

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("📈 Translation Summary:");
        $this->info("   ✅ Successful: {$successCount}");
        $this->info("   ⏭️  Skipped: {$skippedCount}");
        $this->info("   ❌ Errors: {$errorCount}");
        $this->info("   📊 Total: {$totalTranslations}");

        if ($errorCount > 0) {
            $this->warn("⚠️  Some translations failed. Check the output above for details.");
            return 1;
        }

        $this->info("🎉 All translations completed successfully!");
        return 0;
    }

    /**
     * Translate a single file
     */
    private function translateFile($file, $language, $translationService, $force, $dryRun)
    {
        $sourceFile = "lang/it/{$file}.php";
        $targetFile = "lang/{$language}/{$file}.php";

        // Check if source file exists
        if (!File::exists($sourceFile)) {
            return ['status' => 'error', 'message' => 'Source file not found'];
        }

        // Check if target file exists and force is not enabled
        if (File::exists($targetFile) && !$force) {
            return ['status' => 'skipped', 'message' => 'Target file exists'];
        }

        if ($dryRun) {
            return ['status' => 'success', 'message' => 'Would translate'];
        }

        try {
            // Load source translations
            $sourceTranslations = include $sourceFile;
            if (!is_array($sourceTranslations)) {
                return ['status' => 'error', 'message' => 'Invalid source file format'];
            }

            // Translate each key
            $translatedTranslations = [];
            foreach ($sourceTranslations as $key => $value) {
                if (is_string($value)) {
                    $translatedValue = $translationService->translate($value, $language, 'it');
                    $translatedTranslations[$key] = $translatedValue;
                } else {
                    $translatedTranslations[$key] = $value;
                }
            }

            // Create target directory if it doesn't exist
            $targetDir = dirname($targetFile);
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            // Save translated file
            $this->saveTranslationFile($targetFile, $translatedTranslations);

            return ['status' => 'success', 'message' => 'Translated successfully'];

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Save translation file with proper formatting
     */
    private function saveTranslationFile($filePath, $translations)
    {
        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

        // Ensure content is valid UTF-8
        if (!mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        }

        file_put_contents($filePath, $content, LOCK_EX);
    }
}
