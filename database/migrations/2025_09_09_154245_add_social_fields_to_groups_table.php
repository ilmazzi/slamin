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
        Schema::table('groups', function (Blueprint $table) {
            $table->string('website')->nullable()->after('description');
            $table->string('social_facebook')->nullable()->after('website');
            $table->string('social_instagram')->nullable()->after('social_facebook');
            $table->string('social_youtube')->nullable()->after('social_instagram');
            $table->string('social_twitter')->nullable()->after('social_youtube');
            $table->string('social_tiktok')->nullable()->after('social_twitter');
            $table->string('social_linkedin')->nullable()->after('social_tiktok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn([
                'website',
                'social_facebook',
                'social_instagram',
                'social_youtube',
                'social_twitter',
                'social_tiktok',
                'social_linkedin'
            ]);
        });
    }
};
