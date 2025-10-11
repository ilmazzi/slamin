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
        // Check if table already exists (renamed migration, table may exist in production)
        if (Schema::hasTable('message_reactions')) {
            return;
        }

        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('chat_messages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('emoji', 10); // Supporta emoji Unicode
            $table->timestamp('created_at');

            // Indici per performance
            $table->index(['message_id', 'emoji']);
            $table->index(['user_id', 'message_id']);

            // Un utente può avere una sola reazione per messaggio
            $table->unique(['message_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_reactions');
    }
};
