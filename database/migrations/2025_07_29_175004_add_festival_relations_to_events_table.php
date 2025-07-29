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
            // Campo per associare un evento a un festival esistente
            $table->foreignId('festival_id')->nullable()->constrained('events')->onDelete('set null');

            // Campo JSON per memorizzare gli ID degli eventi selezionati per un festival
            $table->json('festival_events')->nullable();

            // Indici per migliorare le performance
            $table->index('festival_id');
            $table->index(['category', 'festival_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['festival_id']);
            $table->dropIndex(['festival_id']);
            $table->dropIndex(['category', 'festival_id']);
            $table->dropColumn(['festival_id', 'festival_events']);
        });
    }
};
