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
        Schema::create('event_requests', function (Blueprint $table) {
            $table->id();

            // Core request data
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Chi richiede

            // Request details
            $table->text('message'); // Messaggio di presentazione
            $table->string('requested_role')->default('performer'); // Ruolo richiesto
            $table->json('portfolio_links')->nullable(); // Link portfolio/video
            $table->text('experience')->nullable(); // Esperienza descritta

            // Status and response from organizer
            $table->enum('status', ['pending', 'accepted', 'declined', 'cancelled'])->default('pending');
            $table->text('organizer_response')->nullable(); // Risposta organizzatore
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('reviewed_at')->nullable();

            // Timestamps
            $table->timestamps();

            // Prevent duplicate requests
            $table->unique(['event_id', 'user_id'], 'unique_event_request');

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['event_id', 'status']);
            $table->index('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_requests');
    }
};
