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
        Schema::table('users', function (Blueprint $table) {
            $table->text('precise_address')->nullable()->after('location');
            $table->string('public_location')->nullable()->after('precise_address');
            $table->string('city')->nullable()->after('public_location');
            $table->string('region')->nullable()->after('city');
            $table->string('country')->nullable()->after('region');
            $table->enum('location_privacy', ['public', 'region', 'country', 'private'])->default('region')->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'precise_address',
                'public_location',
                'city',
                'region',
                'country',
                'location_privacy'
            ]);
        });
    }
};
