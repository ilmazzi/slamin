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
        Schema::create('user_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('total_points')->default(0);
            $table->integer('portal_points')->default(0); // Points from portal activities
            $table->integer('event_points')->default(0); // Points from poetry slam events
            $table->integer('level')->default(1); // User level (calculated based on points + badges)
            $table->integer('badges_count')->default(0); // Cache for badge count
            $table->timestamps();

            // Indexes
            $table->index('total_points');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_points');
    }
};

