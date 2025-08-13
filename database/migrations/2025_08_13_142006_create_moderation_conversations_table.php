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
        Schema::create('moderation_conversations', function (Blueprint $table) {
            $table->id();
            
            // Relazione con la segnalazione
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            
            // Autore del contenuto segnalato
            $table->foreignId('content_author_id')->constrained('users')->onDelete('cascade');
            
            // Moderatore assegnato (può essere null se non ancora assegnato)
            $table->foreignId('assigned_moderator_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Status della conversazione
            $table->enum('status', [
                'open',           // Conversazione aperta
                'waiting_author', // In attesa di risposta dall'autore
                'waiting_moderator', // In attesa di risposta dal moderatore
                'closed'          // Conversazione chiusa
            ])->default('open');
            
            // Timestamps
            $table->timestamps();
            
            // Indici
            $table->index(['report_id']);
            $table->index(['content_author_id']);
            $table->index(['assigned_moderator_id']);
            $table->index(['status']);
            
            // Una conversazione per segnalazione
            $table->unique('report_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_conversations');
    }
};
