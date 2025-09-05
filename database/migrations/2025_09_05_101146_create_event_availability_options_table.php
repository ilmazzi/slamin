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
        Schema::create('event_availability_options', function (Blueprint $table) {
            $table->id();

            // Core data
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->dateTime('datetime');
            $table->text('description')->nullable(); // Descrizione opzionale per l'orario

            // Metadata
            $table->integer('sort_order')->default(0); // Per ordinare le opzioni
            $table->boolean('is_active')->default(true); // Per disabilitare opzioni

            $table->timestamps();

            // Indexes
            $table->index(['event_id', 'datetime']);
            $table->index(['event_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_availability_options');
    }
};
