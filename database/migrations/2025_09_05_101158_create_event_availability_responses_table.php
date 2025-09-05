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
        Schema::create('event_availability_responses', function (Blueprint $table) {
            $table->id();

            // Core data
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('availability_option_id')->constrained('event_availability_options')->onDelete('cascade');

            // Response data
            $table->enum('status', ['preferred', 'available', 'unavailable'])->default('unavailable');
            $table->text('notes')->nullable(); // Note opzionali del partecipante

            $table->timestamps();

            // Prevent duplicate responses
            $table->unique(['user_id', 'availability_option_id'], 'unique_user_availability_response');

            // Indexes
            $table->index(['event_id', 'user_id']);
            $table->index(['availability_option_id', 'status']);
            $table->index(['event_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_availability_responses');
    }
};
