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
        Schema::table('events', function (Blueprint $table) {
            // Allow NULL values for location fields to support online events
            $table->string('venue_name')->nullable()->change();
            $table->text('venue_address')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('country', 2)->nullable()->change(); // ISO country code
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Revert back to NOT NULL for location fields
            $table->string('venue_name')->nullable(false)->change();
            $table->text('venue_address')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('country', 2)->nullable(false)->change(); // ISO country code
        });
    }
};
