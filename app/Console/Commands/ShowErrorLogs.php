<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class ShowErrorLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:errors {--limit=10 : Number of recent errors to show} {--user= : Filter by user ID} {--category= : Filter by category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show recent error logs from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $userId = $this->option('user');
        $category = $this->option('category');

        $query = ActivityLog::where('level', ActivityLog::LEVEL_ERROR)
                           ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $errors = $query->limit($limit)->get();

        if ($errors->isEmpty()) {
            $this->info('No error logs found.');
            return;
        }

        $this->info("Showing last {$errors->count()} error logs:\n");

        foreach ($errors as $error) {
            $this->line("=== Error #{$error->id} ===");
            $this->line("Time: {$error->created_at}");
            $this->line("Category: {$error->category}");
            $this->line("Action: {$error->action}");
            $this->line("Description: {$error->description}");
            $this->line("User ID: " . ($error->user_id ?? 'Guest'));
            $this->line("URL: " . ($error->url ?? 'N/A'));
            $this->line("Method: " . ($error->method ?? 'N/A'));
            
            if (!empty($error->details)) {
                $this->line("Details:");
                foreach ($error->details as $key => $value) {
                    if (is_string($value) && strlen($value) > 100) {
                        $value = substr($value, 0, 100) . '...';
                    }
                    $this->line("  {$key}: " . (is_array($value) ? json_encode($value) : $value));
                }
            }
            
            $this->line("");
        }
    }
} 