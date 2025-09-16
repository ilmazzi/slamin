<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\TranslationManagementController;

class TestTranslationsCommand extends Command
{
    protected $signature = 'translations:test';
    protected $description = 'Test the translation management system';

    public function handle()
    {
        $this->info('🧪 Testing Translation Management System...');

        try {
            // Test controller instantiation
            $controller = new TranslationManagementController();
            $this->info('✅ Controller instantiated successfully');

            // Test available languages
            $reflection = new \ReflectionClass($controller);
            $method = $reflection->getMethod('getAvailableLanguages');
            $method->setAccessible(true);
            $languages = $method->invoke($controller);

            $this->info('✅ Available languages: ' . implode(', ', $languages));

            // Test translation files
            $method = $reflection->getMethod('getTranslationFiles');
            $method->setAccessible(true);
            $files = $method->invoke($controller);

            $this->info('✅ Translation files: ' . implode(', ', array_keys($files)));

            $this->info('🎉 All tests passed! The system is working correctly.');

        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
