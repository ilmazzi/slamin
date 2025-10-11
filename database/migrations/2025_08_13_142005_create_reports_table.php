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
        // Check if table already exists
        if (Schema::hasTable('reports')) {
            return;
        }

        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Utente che ha fatto la segnalazione
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Contenuto segnalato (relazione polimorfa)
            $table->string('reportable_type');
            $table->unsignedBigInteger('reportable_id');

            // Dettagli della segnalazione
            $table->enum('reason', [
                'spam',
                'inappropriate',
                'violence',
                'harassment',
                'copyright',
                'misinformation',
                'other'
            ]);
            $table->text('description')->nullable();

            // Status della segnalazione
            $table->enum('status', [
                'pending',
                'investigating',
                'resolved',
                'dismissed'
            ])->default('pending');

            // Risoluzione
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();

            $table->timestamps();

            // Indici
            $table->index(['reportable_type', 'reportable_id']);
            $table->index(['user_id']);
            $table->index(['status']);
            $table->index(['resolved_by']);

            // Indice composito per evitare segnalazioni duplicate
            $table->unique(['user_id', 'reportable_type', 'reportable_id'], 'unique_user_report');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
