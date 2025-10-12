<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('event_participants')->onDelete('cascade');
            $table->integer('position'); // 1, 2, 3, etc.
            $table->decimal('total_score', 6, 2); // Total score across all rounds
            $table->json('round_scores')->nullable(); // Array of scores per round
            $table->foreignId('badge_id')->nullable()->constrained('badges')->onDelete('set null'); // Badge won (1st, 2nd, 3rd place)
            $table->boolean('badge_awarded')->default(false); // Track if badge was awarded
            $table->timestamps();

            // Indexes
            $table->index(['event_id', 'position']);
            $table->unique(['event_id', 'participant_id']); // One ranking per participant per event
            $table->index('badge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rankings');
    }
};

