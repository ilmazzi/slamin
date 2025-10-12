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
        Schema::create('event_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->integer('round_number'); // 1, 2, 3, finale, etc.
            $table->string('name'); // "Primo turno", "Semifinale", "Finale", etc.
            $table->integer('max_participants')->nullable(); // Max participants for this round
            $table->enum('scoring_type', ['average', 'sum', 'best_of', 'elimination'])->default('average');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['event_id', 'round_number']);
            $table->unique(['event_id', 'round_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rounds');
    }
};

