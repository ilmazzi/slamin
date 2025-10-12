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
        Schema::table('user_badges', function (Blueprint $table) {
            $table->boolean('is_featured')->default(true)->after('progress'); // Show in sidebar/profile
            $table->integer('display_order')->default(0)->after('is_featured'); // Order of display
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'display_order']);
        });
    }
};
