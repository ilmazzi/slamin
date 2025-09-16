<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\TranslationManagementController;

class TestHardcodedCommand extends Command
{
    protected $signature = 'translations:test-hardcoded';
    protected $description = 'Test the hardcoded text detection system';

    public function handle()
    {
        $this->info('🔍 Testing Hardcoded Text Detection...');

        try {
            $controller = new TranslationManagementController();

            // Test reflection to access private method
            $reflection = new \ReflectionClass($controller);
            $method = $reflection->getMethod('findHardcodedTexts');
            $method->setAccessible(true);

            $hardcodedTexts = $method->invoke($controller);

            $this->info('✅ Hardcoded texts found: ' . count($hardcodedTexts));

            if (count($hardcodedTexts) > 0) {
                $this->info('📋 Sample texts found:');
                $sampleCount = min(10, count($hardcodedTexts));

                for ($i = 0; $i < $sampleCount; $i++) {
                    $text = $hardcodedTexts[$i];
                    $this->line("  " . ($i + 1) . ". \"{$text['text']}\" (from {$text['file']}:{$text['line']})");
                }

                if (count($hardcodedTexts) > 10) {
                    $this->line("  ... and " . (count($hardcodedTexts) - 10) . " more");
                }
            } else {
                $this->info('🎉 No hardcoded texts found! All texts are properly translated.');
            }

            $this->info('✅ Hardcoded text detection working correctly!');

        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
