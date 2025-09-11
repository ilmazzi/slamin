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
        Schema::table('carousels', function (Blueprint $table) {
            // Cambia content_description da VARCHAR(255) a TEXT per supportare descrizioni più lunghe
            $table->text('content_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            // Ripristina a VARCHAR(255) - ATTENZIONE: potrebbero esserci perdite di dati
            $table->string('content_description', 255)->nullable()->change();
        });
    }
};