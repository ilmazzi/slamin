<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifica l'ENUM per includere tutti i valori della configurazione
        DB::statement("ALTER TABLE poems MODIFY COLUMN poem_type ENUM('free_verse', 'sonnet', 'haiku', 'limerick', 'ballad', 'ode', 'elegy', 'epic', 'other') NOT NULL DEFAULT 'free_verse'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ripristina l'ENUM originale
        DB::statement("ALTER TABLE poems MODIFY COLUMN poem_type ENUM('free_verse', 'sonnet', 'haiku', 'limerick', 'other') NOT NULL DEFAULT 'free_verse'");
    }
};
