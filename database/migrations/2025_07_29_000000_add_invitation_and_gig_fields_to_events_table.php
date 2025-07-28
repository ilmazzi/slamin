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
        Schema::table('events', function (Blueprint $table) {
            // Campo per la scadenza inviti
            $table->datetime('invitation_deadline')->nullable()->after('registration_deadline');

            // Campo per le posizioni d'ingaggio (JSON)
            $table->json('gig_positions')->nullable()->after('invitation_deadline');

            // Indici per migliorare le performance
            $table->index('invitation_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['invitation_deadline']);
            $table->dropColumn(['invitation_deadline', 'gig_positions']);
        });
    }
};
