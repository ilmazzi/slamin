<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LoggingService;

class TestErrorLogging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:error-logging {type=exception}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the error logging system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        
        $this->info("Testing error logging with type: {$type}");
        
        switch ($type) {
            case 'exception':
                $this->testException();
                break;
            case 'validation':
                $this->testValidation();
                break;
            case 'database':
                $this->testDatabase();
                break;
            default:
                $this->error("Unknown test type: {$type}");
                return 1;
        }
        
        $this->info('Error logging test completed. Check the logs.');
        return 0;
    }
    
    private function testException()
    {
        try {
            throw new \Exception('This is a test exception for error logging');
        } catch (\Exception $e) {
            LoggingService::logError('test_exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
    
    private function testValidation()
    {
        LoggingService::logError('validation_failed', [
            'field' => 'email',
            'rule' => 'required',
            'message' => 'The email field is required',
        ]);
    }
    
    private function testDatabase()
    {
        LoggingService::logError('database_error', [
            'query' => 'SELECT * FROM non_existent_table',
            'message' => 'Table non_existent_table doesn\'t exist',
        ]);
    }
}
