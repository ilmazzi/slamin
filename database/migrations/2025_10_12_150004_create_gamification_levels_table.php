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
        Schema::create('gamification_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('level')->unique(); // 1, 2, 3, etc.
            $table->string('name'); // Novizio, Apprendista, Poeta, Maestro, etc.
            $table->text('description')->nullable();
            $table->integer('required_points'); // Minimum points needed
            $table->integer('required_badges')->default(0); // Minimum badges needed
            $table->string('icon_path')->nullable(); // Level icon/badge
            $table->json('perks')->nullable(); // Special perks for this level (JSON)
            $table->integer('order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('level');
            $table->index('required_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamification_levels');
    }
};

