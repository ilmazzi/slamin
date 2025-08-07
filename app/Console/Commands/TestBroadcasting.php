<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\UserLoggedIn;

class TestBroadcasting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:broadcasting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test broadcasting by sending a UserLoggedIn event';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing broadcasting...');

        try {
            broadcast(new UserLoggedIn('Test User', 'test@example.com'));
            $this->info('✅ Event sent successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Error sending event: ' . $e->getMessage());
        }
    }
}
