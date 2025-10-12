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
            // Remove old is_featured field
            $table->dropColumn('is_featured');
            
            // Add separate fields for sidebar and profile
            $table->boolean('show_in_sidebar')->default(true)->after('progress');
            $table->boolean('show_in_profile')->default(true)->after('show_in_sidebar');
            $table->integer('sidebar_order')->default(0)->after('show_in_profile');
            $table->integer('profile_order')->default(0)->after('sidebar_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropColumn(['show_in_sidebar', 'show_in_profile', 'sidebar_order', 'profile_order']);
            $table->boolean('is_featured')->default(true)->after('progress');
        });
    }
};
