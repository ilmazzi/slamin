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
        Schema::table('videos', function (Blueprint $table) {
            // Campi PeerTube mancanti
            if (!Schema::hasColumn('videos', 'peertube_short_uuid')) {
                $table->string('peertube_short_uuid')->nullable();
            }
            if (!Schema::hasColumn('videos', 'peertube_status')) {
                $table->string('peertube_status')->default('processing');
            }
            if (!Schema::hasColumn('videos', 'peertube_uploaded_at')) {
                $table->timestamp('peertube_uploaded_at')->nullable();
            }
            if (!Schema::hasColumn('videos', 'peertube_processed_at')) {
                $table->timestamp('peertube_processed_at')->nullable();
            }

            // Indici per performance (solo se le colonne e indici non esistono)
            if (Schema::hasColumn('videos', 'peertube_video_id') && !Schema::hasIndex('videos', 'videos_peertube_video_id_index')) {
                $table->index(['peertube_video_id']);
            }
            if (Schema::hasColumn('videos', 'peertube_uuid') && !Schema::hasIndex('videos', 'videos_peertube_uuid_index')) {
                $table->index(['peertube_uuid']);
            }
            if (Schema::hasColumn('videos', 'peertube_status') && !Schema::hasIndex('videos', 'videos_peertube_status_index')) {
                $table->index(['peertube_status']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
        public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $columns = ['peertube_short_uuid', 'peertube_status', 'peertube_uploaded_at', 'peertube_processed_at'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('videos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
