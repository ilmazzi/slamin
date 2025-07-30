<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Admin\TranslationController;

class CreateTestLanguage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-language {code=pt} {name=Português}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test creating a new language';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $code = $this->argument('code');
        $name = $this->argument('name');
        
        $this->info("Creating language: {$code} ({$name})");
        
        try {
            // Simulate the createLanguage method
            $languageCode = strtolower($code);
            $languagePath = lang_path($languageCode);

            if (File::exists($languagePath)) {
                $this->error("Language already exists.");
                return 1;
            }

            // Create directory
            File::makeDirectory($languagePath, 0755, true);

            // Copy only keys from Italian (without translations)
            $italianPath = lang_path('it');
            if (File::exists($italianPath)) {
                $files = File::files($italianPath);
                foreach ($files as $file) {
                    $fileName = $file->getFilename();
                    $newPath = $languagePath . '/' . $fileName;
                    
                    // Load Italian translations
                    $italianTranslations = include $file->getPathname();
                    if (is_array($italianTranslations)) {
                        // Create array with only keys (empty values)
                        $emptyTranslations = [];
                        foreach ($italianTranslations as $key => $value) {
                            $emptyTranslations[$key] = '';
                        }
                        
                        // Generate PHP content with empty keys
                        $controller = new TranslationController();
                        $phpContent = $controller->generatePhpContent($emptyTranslations, pathinfo($fileName, PATHINFO_FILENAME));
                        File::put($newPath, $phpContent);
                        
                        $this->info("Created file: {$fileName}");
                    }
                }
            }

            $this->info("Language {$name} created successfully!");
            $this->info("Files created in: {$languagePath}");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error creating language: " . $e->getMessage());
            return 1;
        }
    }
}
