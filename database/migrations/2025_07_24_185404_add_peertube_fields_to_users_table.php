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
            // PeerTube integration fields
            $table->integer('peertube_user_id')->nullable()->unique();
            $table->string('peertube_username')->nullable()->unique();
            $table->string('peertube_display_name')->nullable();
            $table->text('peertube_token')->nullable();
            $table->text('peertube_refresh_token')->nullable();
            $table->timestamp('peertube_token_expires_at')->nullable();
            $table->integer('peertube_account_id')->nullable();
            $table->integer('peertube_channel_id')->nullable();

            // Additional PeerTube fields
            $table->string('peertube_email')->nullable();
            $table->integer('peertube_role')->default(2); // Default: User role
            $table->bigInteger('peertube_video_quota')->default(-1); // -1 = unlimited
            $table->bigInteger('peertube_video_quota_daily')->default(-1); // -1 = unlimited
            $table->timestamp('peertube_created_at')->nullable();
            $table->string('peertube_password')->nullable(); // Encrypted password for API calls

            // Indexes
            $table->index(['peertube_user_id']);
            $table->index(['peertube_username']);
            $table->index(['peertube_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['peertube_user_id']);
            $table->dropIndex(['peertube_username']);
            $table->dropIndex(['peertube_account_id']);

            $table->dropColumn([
                'peertube_user_id',
                'peertube_username',
                'peertube_display_name',
                'peertube_token',
                'peertube_refresh_token',
                'peertube_token_expires_at',
                'peertube_account_id',
                'peertube_channel_id',
                'peertube_email',
                'peertube_role',
                'peertube_video_quota',
                'peertube_video_quota_daily',
                'peertube_created_at',
                'peertube_password'
            ]);
        });
    }
};
