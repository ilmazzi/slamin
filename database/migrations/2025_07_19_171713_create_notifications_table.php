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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Core notification data
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Destinatario
            $table->string('type'); // event_invitation, event_request, event_update, etc.
            $table->string('title'); // Titolo notifica
            $table->text('message'); // Messaggio della notifica

            // Related data
            $table->json('data')->nullable(); // Dati aggiuntivi (ID evento, invito, ecc.)
            $table->string('action_url')->nullable(); // URL per azione
            $table->string('action_text')->nullable(); // Testo del pulsante azione

            // Status
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->boolean('is_email_sent')->default(false); // Se email inviata
            $table->string('priority')->default('normal'); // low, normal, high, urgent

            // Timestamps
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
