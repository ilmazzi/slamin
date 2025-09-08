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
        Schema::create('poem_translation_negotiations', function (Blueprint $table) {
            $table->id();

            // Riferimento alla candidatura (gig_application)
            $table->unsignedBigInteger('gig_application_id');
            $table->foreign('gig_application_id')->references('id')->on('gig_applications')->onDelete('cascade');

            // Utente che ha scritto il messaggio
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Tipo di messaggio
            $table->enum('message_type', ['proposal', 'accept', 'reject', 'counter', 'info'])->default('info');

            // Contenuto del messaggio
            $table->text('message');

            // Proposta di compenso (opzionale)
            $table->decimal('proposed_compensation', 10, 2)->nullable();

            // Proposta di scadenza (opzionale)
            $table->date('proposed_deadline')->nullable();

            // Se il messaggio è stato letto
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            // Indici per performance
            $table->index(['gig_application_id', 'created_at'], 'ptn_gig_app_created_idx');
            $table->index(['user_id', 'is_read'], 'ptn_user_read_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poem_translation_negotiations');
    }
};
