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
        Schema::create('moderation_messages', function (Blueprint $table) {
            $table->id();
            
            // Relazione con la conversazione
            $table->foreignId('conversation_id')->constrained('moderation_conversations')->onDelete('cascade');
            
            // Autore del messaggio
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Tipo di messaggio
            $table->enum('type', [
                'system',      // Messaggio di sistema (es. "Conversazione aperta")
                'author',      // Messaggio dell'autore del contenuto
                'moderator',   // Messaggio del moderatore
                'admin'        // Messaggio dell'amministratore
            ]);
            
            // Contenuto del messaggio
            $table->text('message');
            
            // Dati aggiuntivi (JSON)
            $table->json('data')->nullable();
            
            // Se il messaggio è interno (solo per moderatori)
            $table->boolean('is_internal')->default(false);
            
            // Se il messaggio è stato letto
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indici
            $table->index(['conversation_id']);
            $table->index(['user_id']);
            $table->index(['type']);
            $table->index(['is_internal']);
            $table->index(['is_read']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_messages');
    }
};
