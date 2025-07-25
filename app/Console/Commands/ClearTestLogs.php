<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;

class ClearTestLogs extends Command
{
    protected $signature = 'logs:clear-test {--confirm : Skip confirmation prompt}';
    protected $description = 'Clear all test logs from the database';

    public function handle()
    {
        $testLogs = ActivityLog::whereJsonContains('details', ['test' => true])->count();
        
        if ($testLogs === 0) {
            $this->info('No test logs found in the database.');
            return 0;
        }

        $this->warn("Found {$testLogs} test logs in the database.");

        if (!$this->option('confirm')) {
            if (!$this->confirm('Do you want to delete all test logs?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $deleted = ActivityLog::whereJsonContains('details', ['test' => true])->delete();
        
        $this->info("Successfully deleted {$deleted} test logs.");
        
        return 0;
    }
}
