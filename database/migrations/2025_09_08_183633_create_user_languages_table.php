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
        Schema::create('user_languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('language_name'); // Nome della lingua (es. "Italiano", "English", "Français")
            $table->string('language_code', 5); // Codice ISO (es. "it", "en", "fr")
            $table->enum('type', ['native', 'spoken', 'written']); // Tipo di competenza
            $table->enum('level', ['excellent', 'good', 'poor'])->nullable(); // Livello (solo per spoken/written)
            $table->timestamps();

            // Indice unico per evitare duplicati
            $table->unique(['user_id', 'language_code', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_languages');
    }
};
