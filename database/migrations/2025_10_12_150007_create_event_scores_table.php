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
        Schema::create('event_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('event_participants')->onDelete('cascade');
            $table->foreignId('judge_id')->nullable()->constrained('users')->onDelete('set null'); // Who gave this score
            $table->integer('round')->default(1); // Round number
            $table->decimal('score', 4, 1); // Score 0.0 - 10.0 with 1 decimal
            $table->text('notes')->nullable();
            $table->timestamp('scored_at');
            $table->timestamps();

            // Indexes
            $table->index(['event_id', 'participant_id', 'round']);
            $table->index('judge_id');
            $table->index('scored_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_scores');
    }
};

