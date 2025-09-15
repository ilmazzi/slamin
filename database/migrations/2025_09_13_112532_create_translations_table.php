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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 5)->index();
            $table->string('group_name', 50)->index();
            $table->string('key_name', 100)->index();
            $table->text('value')->nullable();
            $table->timestamps();

            // Indice unico per evitare duplicati
            $table->unique(['locale', 'group_name', 'key_name'], 'unique_translation');

            // Indici per performance
            $table->index(['locale', 'group_name']);
            $table->index(['group_name', 'key_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
