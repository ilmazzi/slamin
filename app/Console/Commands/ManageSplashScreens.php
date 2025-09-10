<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\SplashScreenHelper;

class ManageSplashScreens extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'splash:manage {action} {--slogan=}';

    /**
     * The console command description.
     */
    protected $description = 'Manage splash screen slogans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listSlogans();
                break;
            case 'count':
                $this->showCount();
                break;
            case 'random':
                $this->showRandom();
                break;
            default:
                $this->error('Invalid action. Available actions: list, count, random');
                return 1;
        }

        return 0;
    }

    /**
     * List all available slogans
     */
    private function listSlogans()
    {
        $slogans = SplashScreenHelper::getAllSlogans();

        $this->info('Available Splash Screen Slogans:');
        $this->line('');

        foreach ($slogans as $index => $slogan) {
            $this->line(sprintf('%2d. %s', $index + 1, $slogan));
        }

        $this->line('');
        $this->info('Total: ' . count($slogans) . ' slogans');
    }

    /**
     * Show count of slogans
     */
    private function showCount()
    {
        $count = SplashScreenHelper::getSlogansCount();
        $this->info("Total splash screen slogans: {$count}");
    }

    /**
     * Show a random slogan
     */
    private function showRandom()
    {
        $slogan = SplashScreenHelper::getRandomSlogan();
        $this->info("Random slogan: {$slogan}");
    }
}
