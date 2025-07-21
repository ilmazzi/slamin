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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome del pacchetto
            $table->string('slug')->unique(); // Slug per URL
            $table->text('description')->nullable(); // Descrizione
            $table->decimal('price', 8, 2); // Prezzo
            $table->string('currency', 3)->default('EUR'); // Valuta
            $table->integer('video_limit'); // Limite video
            $table->integer('duration_days'); // Durata in giorni
            $table->string('stripe_price_id')->nullable(); // ID prezzo Stripe
            $table->json('features')->nullable(); // Features extra (JSON)
            $table->boolean('is_active')->default(true); // Se attivo
            $table->integer('sort_order')->default(0); // Ordine visualizzazione
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
