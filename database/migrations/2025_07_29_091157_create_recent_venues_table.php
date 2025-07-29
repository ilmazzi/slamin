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
        Schema::create('recent_venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('venue_name');
            $table->string('venue_address');
            $table->string('city');
            $table->string('postcode');
            $table->string('country')->default('Italia');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('usage_count')->default(1);
            $table->timestamp('last_used_at');
            $table->timestamps();
            
            // Indici per performance
            $table->index(['user_id', 'last_used_at']);
            $table->index(['venue_name', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recent_venues');
    }
};
