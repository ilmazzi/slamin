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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['portal', 'event'])->default('portal');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon_path')->nullable(); // Path to badge icon
            $table->string('category'); // videos, articles, poems, photos, likes, comments, posts, forum, event_participation, event_wins
            $table->enum('criteria_type', ['count', 'milestone', 'first_time', 'streak', 'special'])->default('count');
            $table->integer('criteria_value')->default(1); // Threshold (1, 5, 10, 50, 100, etc.)
            $table->integer('points')->default(10); // Points awarded for this badge
            $table->integer('order')->default(0); // Display order
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index(['type', 'category']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};

