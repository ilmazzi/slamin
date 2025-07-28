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
            // Campi per eventi ricorrenti
            $table->boolean('is_recurring')->default(false)->after('status');
            $table->enum('recurrence_type', [
                'daily',          // Giornaliera
                'weekly',         // Settimanale
                'monthly',        // Mensile
                'yearly'          // Annuale
            ])->nullable()->after('is_recurring');

            $table->integer('recurrence_interval')->default(1)->after('recurrence_type'); // Intervallo (es. ogni 3 giorni)
            $table->integer('recurrence_count')->nullable()->after('recurrence_interval'); // Numero di occorrenze
            $table->json('recurrence_weekdays')->nullable()->after('recurrence_count'); // Giorni della settimana per ricorrenza settimanale
            $table->integer('recurrence_monthday')->nullable()->after('recurrence_weekdays'); // Giorno del mese per ricorrenza mensile
            $table->foreignId('parent_event_id')->nullable()->after('recurrence_monthday')->constrained('events')->onDelete('cascade'); // Evento padre per la serie
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['parent_event_id']);
            $table->dropColumn([
                'is_recurring',
                'recurrence_type',
                'recurrence_interval',
                'recurrence_count',
                'recurrence_weekdays',
                'recurrence_monthday',
                'parent_event_id'
            ]);
        });
    }
};
