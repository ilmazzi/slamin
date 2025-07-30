<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\LanguageServiceProvider;

class TestAvailableLanguages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:available-languages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test available languages loading';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Available Languages...');
        
        // Test the service provider methods
        $provider = new LanguageServiceProvider(app());
        
        // Use reflection to access private methods
        $reflection = new \ReflectionClass($provider);
        $getAvailableLanguagesMethod = $reflection->getMethod('getAvailableLanguages');
        $getAvailableLanguagesMethod->setAccessible(true);
        
        $languages = $getAvailableLanguagesMethod->invoke($provider);
        
        $this->info('Available Languages:');
        foreach ($languages as $code => $name) {
            $flagCode = LanguageServiceProvider::getFlagCode($code);
            $this->line("  {$code} => {$name} (flag: {$flagCode})");
        }
        
        $this->info('Total languages: ' . count($languages));
        
        return 0;
    }
}
