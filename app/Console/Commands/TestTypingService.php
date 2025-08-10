<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TypingService;

class TestTypingService extends Command
{
    protected $signature = 'typing:test {room} {user} {name}';
    protected $description = 'Test the TypingService with Redis';

    public function handle()
    {
        $roomId = $this->argument('room');
        $userId = $this->argument('user');
        $userName = $this->argument('name');

        $typingService = app(TypingService::class);

        $this->info("Testing TypingService for Room: {$roomId}, User: {$userId}, Name: {$userName}");

        // Test start typing
        $this->info("Starting typing...");
        $typingUsers = $typingService->startTyping($roomId, $userId, $userName);
        $this->info("Users typing: " . json_encode($typingUsers));

        // Test get typing users
        $this->info("Getting typing users...");
        $currentTyping = $typingService->getTypingUsers($roomId);
        $this->info("Current typing users: " . json_encode($currentTyping));

        // Test is user typing
        $this->info("Checking if user is typing...");
        $isTyping = $typingService->isUserTyping($roomId, $userId);
        $this->info("User is typing: " . ($isTyping ? 'Yes' : 'No'));

        // Test stats
        $this->info("Getting typing stats...");
        $stats = $typingService->getTypingStats($roomId);
        $this->info("Stats: " . json_encode($stats));

        // Wait 2 seconds
        $this->info("Waiting 2 seconds...");
        sleep(2);

        // Test stop typing
        $this->info("Stopping typing...");
        $typingUsers = $typingService->stopTyping($roomId, $userId, $userName);
        $this->info("Users typing after stop: " . json_encode($typingUsers));

        $this->info("Test completed!");

        return 0;
    }
}
