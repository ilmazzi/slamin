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
        Schema::create('group_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id'); // Utente invitato
            $table->unsignedBigInteger('invited_by'); // Chi ha inviato l'invito
            $table->enum('status', ['pending', 'accepted', 'declined', 'expired'])->default('pending');
            $table->text('message')->nullable(); // Messaggio opzionale dell'invito
            $table->timestamp('expires_at')->nullable(); // Scadenza invito
            $table->timestamps();
            
            // Indici
            $table->index('group_id');
            $table->index('user_id');
            $table->index('invited_by');
            $table->index('status');
            $table->index(['group_id', 'user_id']);
            $table->index(['user_id', 'status']);
            
            // Foreign keys
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('cascade');
            
            // Unico: un utente può avere un solo invito pendente per gruppo
            $table->unique(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_invitations');
    }
};
