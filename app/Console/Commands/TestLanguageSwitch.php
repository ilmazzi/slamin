<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class TestLanguageSwitch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:language-switch {lang=pt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test language switching';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lang = $this->argument('lang');
        
        $this->info("Testing language switch to: {$lang}");
        
        // Test current locale
        $this->info("Current locale: " . App::getLocale());
        
        // Test setting locale
        App::setLocale($lang);
        $this->info("New locale: " . App::getLocale());
        
        // Test some translations
        $this->info("Testing translations:");
        $this->line("  Dashboard: " . __('dashboard.dashboard'));
        $this->line("  Home: " . __('home.home'));
        $this->line("  Events: " . __('events.events'));
        
        return 0;
    }
}
