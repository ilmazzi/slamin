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
            $table->decimal('commission_rate', 5, 4)->default(0.0000)->after('currency'); // Es: 0.1000 = 10%
            $table->decimal('commission_fixed', 8, 2)->default(0.00)->after('commission_rate'); // Commissione fissa in euro
            $table->decimal('commission_total', 8, 2)->default(0.00)->after('commission_fixed'); // Commissione totale
            $table->decimal('translator_amount', 8, 2)->default(0.00)->after('commission_total'); // Importo che va al traduttore
            $table->decimal('platform_amount', 8, 2)->default(0.00)->after('translator_amount'); // Importo che va alla piattaforma
            $table->string('payment_method', 20)->default('stripe')->after('platform_amount'); // Metodo di pagamento usato
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('translation_payments', function (Blueprint $table) {
            $table->dropColumn([
                'commission_rate',
                'commission_fixed',
                'commission_total',
                'translator_amount',
                'platform_amount',
                'payment_method'
            ]);
        });
    }
};
