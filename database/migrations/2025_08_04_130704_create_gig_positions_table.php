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
        Schema::create('gig_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome della posizione (es: "Artista/Poeta")
            $table->string('key'); // Chiave unica (es: "artist_poet")
            $table->text('description')->nullable(); // Descrizione della posizione
            $table->boolean('is_active')->default(true); // Se la posizione è attiva
            $table->integer('sort_order')->default(0); // Ordine di visualizzazione
            $table->timestamps();

            // Indici
            $table->unique('key');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_positions');
    }
};
