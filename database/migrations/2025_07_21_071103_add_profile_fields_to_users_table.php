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
            $table->string('phone')->nullable()->after('location');
            $table->string('website')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('website');
            $table->string('social_facebook')->nullable()->after('profile_photo');
            $table->string('social_instagram')->nullable()->after('social_facebook');
            $table->string('social_youtube')->nullable()->after('social_instagram');
            $table->string('social_twitter')->nullable()->after('social_youtube');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'website',
                'profile_photo',
                'social_facebook',
                'social_instagram',
                'social_youtube',
                'social_twitter'
            ]);
        });
    }
};
