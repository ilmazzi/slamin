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
        Schema::create('poem_translations', function (Blueprint $table) {
            $table->id();

            // Riferimento al gig di traduzione
            $table->unsignedBigInteger('gig_id');
            $table->foreign('gig_id')->references('id')->on('gigs')->onDelete('cascade');

            // Riferimento alla poesia originale
            $table->unsignedBigInteger('poem_id');
            $table->foreign('poem_id')->references('id')->on('poems')->onDelete('cascade');

            // Traduttore
            $table->unsignedBigInteger('translator_id');
            $table->foreign('translator_id')->references('id')->on('users')->onDelete('cascade');

            // Lingua della traduzione
            $table->string('language', 10);

            // Titolo tradotto
            $table->string('title');

            // Contenuto tradotto
            $table->longText('content');

            // Note del traduttore (opzionale)
            $table->text('translator_notes')->nullable();

            // Stato della traduzione
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');

            // Compenso finale concordato
            $table->decimal('final_compensation', 10, 2)->nullable();

            // Data di completamento
            $table->datetime('completed_at')->nullable();

            $table->timestamps();

            // Indici per performance
            $table->index(['poem_id', 'language'], 'pt_poem_lang_idx');
            $table->index(['translator_id', 'status'], 'pt_translator_status_idx');
            $table->index(['gig_id', 'status'], 'pt_gig_status_idx');

            // Vincolo unico: una traduzione per poesia+lingua+traduttore
            $table->unique(['poem_id', 'language', 'translator_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poem_translations');
    }
};
