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
        Schema::table('translation_payments', function (Blueprint $table) {
            $table->string('payout_status')->default('pending')->after('status'); // pending, transferred, failed, manual_required
            $table->string('payout_transfer_id')->nullable()->after('payout_status');
            $table->timestamp('payout_date')->nullable()->after('payout_transfer_id');
            $table->text('payout_notes')->nullable()->after('payout_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('translation_payments', function (Blueprint $table) {
            $table->dropColumn(['payout_status', 'payout_transfer_id', 'payout_date', 'payout_notes']);
        });
    }
};
