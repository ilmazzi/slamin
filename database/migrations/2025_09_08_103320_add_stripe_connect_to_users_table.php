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
            $table->string('stripe_connect_account_id')->nullable()->after('email_verified_at');
            $table->string('paypal_email')->nullable()->after('stripe_connect_account_id');
            $table->boolean('payout_method_configured')->default(false)->after('paypal_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_connect_account_id', 'paypal_email', 'payout_method_configured']);
        });
    }
};
