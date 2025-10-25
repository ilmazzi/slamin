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
            // Campi core PeerTube mancanti
            if (!Schema::hasColumn('videos', 'peertube_video_id')) {
                $table->integer('peertube_video_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('videos', 'peertube_uuid')) {
                $table->string('peertube_uuid')->nullable()->after('peertube_video_id');
            }
            
            // Campo moderation_status se mancante
            if (!Schema::hasColumn('videos', 'moderation_status')) {
                $table->string('moderation_status')->default('pending')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $columns = ['peertube_video_id', 'peertube_uuid', 'moderation_status'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('videos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
