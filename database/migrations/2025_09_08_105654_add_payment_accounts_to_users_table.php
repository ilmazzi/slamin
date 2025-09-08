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
            // Stripe Connect (aggiungi solo se non esiste)
            if (!Schema::hasColumn('users', 'stripe_connect_status')) {
                $table->string('stripe_connect_status')->default('not_connected')->after('stripe_connect_account_id');
            }
            if (!Schema::hasColumn('users', 'stripe_connect_details')) {
                $table->json('stripe_connect_details')->nullable()->after('stripe_connect_status');
            }
            if (!Schema::hasColumn('users', 'stripe_connected_at')) {
                $table->timestamp('stripe_connected_at')->nullable()->after('stripe_connect_details');
            }

            // PayPal
            if (!Schema::hasColumn('users', 'paypal_email')) {
                $table->string('paypal_email')->nullable()->after('stripe_connected_at');
            }
            if (!Schema::hasColumn('users', 'paypal_merchant_id')) {
                $table->string('paypal_merchant_id')->nullable()->after('paypal_email');
            }
            if (!Schema::hasColumn('users', 'paypal_verified')) {
                $table->boolean('paypal_verified')->default(false)->after('paypal_merchant_id');
            }
            if (!Schema::hasColumn('users', 'paypal_connected_at')) {
                $table->timestamp('paypal_connected_at')->nullable()->after('paypal_verified');
            }

            // Payout settings
            if (!Schema::hasColumn('users', 'preferred_payout_method')) {
                $table->string('preferred_payout_method')->default('stripe')->after('paypal_connected_at');
            }
            if (!Schema::hasColumn('users', 'payout_method_configured')) {
                $table->boolean('payout_method_configured')->default(false)->after('preferred_payout_method');
            }
            if (!Schema::hasColumn('users', 'payout_settings')) {
                $table->json('payout_settings')->nullable()->after('payout_method_configured');
            }

            // Bank details (for manual payouts)
            if (!Schema::hasColumn('users', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('payout_settings');
            }
            if (!Schema::hasColumn('users', 'bank_iban')) {
                $table->string('bank_iban')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('users', 'bank_swift')) {
                $table->string('bank_swift')->nullable()->after('bank_iban');
            }
            if (!Schema::hasColumn('users', 'bank_account_holder')) {
                $table->string('bank_account_holder')->nullable()->after('bank_swift');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_connect_account_id',
                'stripe_connect_status',
                'stripe_connect_details',
                'stripe_connected_at',
                'paypal_email',
                'paypal_merchant_id',
                'paypal_verified',
                'paypal_connected_at',
                'preferred_payout_method',
                'payout_method_configured',
                'payout_settings',
                'bank_name',
                'bank_iban',
                'bank_swift',
                'bank_account_holder'
            ]);
        });
    }
};
