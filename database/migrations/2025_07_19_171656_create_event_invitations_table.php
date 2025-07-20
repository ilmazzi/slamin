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
        Schema::create('event_invitations', function (Blueprint $table) {
            $table->id();

            // Core invitation data
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('invited_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade'); // Chi ha inviato l'invito

            // Invitation details
            $table->text('message')->nullable(); // Messaggio personalizzato
            $table->string('role')->default('performer'); // performer, judge, technician, etc.
            $table->decimal('compensation', 8, 2)->nullable(); // Compenso proposto

            // Status and response
            $table->enum('status', ['pending', 'accepted', 'declined', 'expired'])->default('pending');
            $table->text('response_message')->nullable(); // Risposta dell'invitato
            $table->dateTime('responded_at')->nullable();
            $table->dateTime('expires_at')->nullable(); // Scadenza invito

            // Timestamps
            $table->timestamps();

            // Prevent duplicate invitations
            $table->unique(['event_id', 'invited_user_id'], 'unique_event_invitation');

            // Indexes for better performance
            $table->index(['invited_user_id', 'status']);
            $table->index(['event_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_invitations');
    }
};
