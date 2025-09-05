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
            // Availability-based event fields
            $table->boolean('is_availability_based')->default(false)->after('is_online');
            $table->dateTime('availability_deadline')->nullable()->after('invitation_deadline');
            $table->text('availability_instructions')->nullable()->after('availability_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'is_availability_based',
                'availability_deadline',
                'availability_instructions'
            ]);
        });
    }
};
