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
        // Aggiungi campi social alla tabella events
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'like_count')) {
                $table->integer('like_count')->default(0)->after('festival_events');
            }
            if (!Schema::hasColumn('events', 'comment_count')) {
                $table->integer('comment_count')->default(0)->after('like_count');
            }
        });

        // Aggiungi campi social alla tabella carousels
        Schema::table('carousels', function (Blueprint $table) {
            if (!Schema::hasColumn('carousels', 'like_count')) {
                $table->integer('like_count')->default(0);
            }
            if (!Schema::hasColumn('carousels', 'comment_count')) {
                $table->integer('comment_count')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rimuovi campi social dalla tabella events
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['like_count', 'comment_count']);
        });

        // Rimuovi campi social dalla tabella carousels
        Schema::table('carousels', function (Blueprint $table) {
            $table->dropColumn(['like_count', 'comment_count']);
        });
    }
};
