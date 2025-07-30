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
        Schema::create('group_join_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id'); // Utente che richiede di entrare
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->text('message')->nullable(); // Messaggio opzionale della richiesta
            $table->unsignedBigInteger('processed_by')->nullable(); // Chi ha processato la richiesta
            $table->timestamp('processed_at')->nullable(); // Quando è stata processata
            $table->timestamps();
            
            // Indici
            $table->index('group_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('processed_by');
            $table->index(['group_id', 'user_id']);
            $table->index(['group_id', 'status']);
            
            // Foreign keys
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            
            // Unico: un utente può avere una sola richiesta pendente per gruppo
            $table->unique(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_join_requests');
    }
};
