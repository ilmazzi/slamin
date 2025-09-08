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
        Schema::create('translation_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gig_application_id')->constrained('gig_applications')->onDelete('cascade');
            $table->foreignId('poem_id')->constrained('poems')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade'); // Chi paga (autore poesia)
            $table->foreignId('translator_id')->constrained('users')->onDelete('cascade'); // Chi riceve (traduttore)
            $table->decimal('amount', 10, 2); // Importo pagamento
            $table->string('currency', 3)->default('EUR'); // Valuta
            $table->string('stripe_payment_intent_id')->nullable(); // ID Stripe PaymentIntent
            $table->string('stripe_charge_id')->nullable(); // ID Stripe Charge
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->text('failure_reason')->nullable(); // Motivo fallimento
            $table->json('stripe_metadata')->nullable(); // Metadati Stripe
            $table->timestamp('paid_at')->nullable(); // Data pagamento
            $table->timestamps();

            // Indici per performance
            $table->index(['gig_application_id']);
            $table->index(['poem_id']);
            $table->index(['client_id']);
            $table->index(['translator_id']);
            $table->index(['status']);
            $table->index(['stripe_payment_intent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_payments');
    }
};
