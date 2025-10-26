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
        Schema::table('placeholder_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('placeholder_settings', 'event_placeholder_color')) {
                $table->string('event_placeholder_color')->default('#17a2b8')->after('article_placeholder_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('placeholder_settings', function (Blueprint $table) {
            if (Schema::hasColumn('placeholder_settings', 'event_placeholder_color')) {
                $table->dropColumn('event_placeholder_color');
            }
        });
    }
};
