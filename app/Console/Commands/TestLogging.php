<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LoggingService;
use App\Models\User;
use App\Models\Event;

class TestLogging extends Command
{
    protected $signature = 'test:logging {--count=10 : Number of test logs to generate}';
    protected $description = 'Generate test logs to verify the logging system';

    public function handle()
    {
        $count = $this->option('count');
        $this->info("Generating {$count} test logs...");

        // Get a user for testing
        $user = User::first();
        if (!$user) {
            $this->error('No users found in database. Please create a user first.');
            return 1;
        }

        $this->info("Using user: {$user->name} (ID: {$user->id})");

        // Generate different types of logs
        $this->generateAuthLogs($user, $count);
        $this->generateUserLogs($user, $count);
        $this->generateEventLogs($user, $count);
        $this->generateSystemLogs($count);
        $this->generateErrorLogs($count);

        $this->info('Test logs generated successfully!');
        $this->info('You can now check the logs at: /admin/logs');
        
        return 0;
    }

    private function generateAuthLogs($user, $count)
    {
        $this->info('Generating authentication logs...');
        
        for ($i = 0; $i < $count; $i++) {
            $actions = ['login', 'logout', 'login_failed', 'register'];
            $action = $actions[array_rand($actions)];
            
            LoggingService::logAuth($action, [
                'test' => true,
                'iteration' => $i + 1,
                'user_email' => $user->email
            ]);
        }
    }

    private function generateUserLogs($user, $count)
    {
        $this->info('Generating user management logs...');
        
        for ($i = 0; $i < $count; $i++) {
            $actions = ['create', 'update', 'profile_photo_updated', 'banner_updated'];
            $action = $actions[array_rand($actions)];
            
            LoggingService::logUser($action, [
                'test' => true,
                'iteration' => $i + 1,
                'changes' => ['name' => 'Test User', 'email' => 'test@example.com']
            ], 'App\Models\User', $user->id);
        }
    }

    private function generateEventLogs($user, $count)
    {
        $this->info('Generating event logs...');
        
        for ($i = 0; $i < $count; $i++) {
            $actions = ['create', 'update', 'delete', 'publish', 'invite_sent'];
            $action = $actions[array_rand($actions)];
            
            LoggingService::logEvent($action, [
                'test' => true,
                'iteration' => $i + 1,
                'event_title' => "Test Event " . ($i + 1),
                'venue' => 'Test Venue'
            ], 'App\Models\Event', rand(1, 100));
        }
    }

    private function generateSystemLogs($count)
    {
        $this->info('Generating system logs...');
        
        for ($i = 0; $i < $count; $i++) {
            $actions = ['maintenance_start', 'backup_complete', 'cache_cleared', 'queue_failed'];
            $action = $actions[array_rand($actions)];
            
            LoggingService::logSystem($action, [
                'test' => true,
                'iteration' => $i + 1,
                'memory_usage' => rand(100, 1000) . 'MB',
                'disk_usage' => rand(50, 90) . '%'
            ]);
        }
    }

    private function generateErrorLogs($count)
    {
        $this->info('Generating error logs...');
        
        for ($i = 0; $i < $count; $i++) {
            $actions = ['validation_failed', 'database_error', 'file_upload_failed', 'external_api_error'];
            $action = $actions[array_rand($actions)];
            
            LoggingService::logError($action, [
                'test' => true,
                'iteration' => $i + 1,
                'error_message' => 'This is a test error message',
                'stack_trace' => 'Test stack trace...'
            ]);
        }
    }
}
