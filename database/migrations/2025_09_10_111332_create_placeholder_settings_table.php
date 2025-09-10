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
        Schema::create('placeholder_settings', function (Blueprint $table) {
            $table->id();
            $table->string('poem_placeholder_color')->default('#6c757d'); // Colore placeholder poesie
            $table->string('article_placeholder_color')->default('#007bff'); // Colore placeholder articoli
            $table->timestamps();
        });

        // Inserisci il record di default
        DB::table('placeholder_settings')->insert([
            'poem_placeholder_color' => '#6c757d',
            'article_placeholder_color' => '#007bff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placeholder_settings');
    }
};
