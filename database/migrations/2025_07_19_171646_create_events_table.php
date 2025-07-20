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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Basic event information
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable(); // Requisiti per partecipanti

            // Date and time
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->dateTime('registration_deadline')->nullable();

            // Location information
            $table->string('venue_name');
            $table->text('venue_address');
            $table->string('city');
            $table->string('country', 2); // ISO country code
            $table->decimal('latitude', 10, 8)->nullable(); // Per mappa
            $table->decimal('longitude', 11, 8)->nullable(); // Per mappa

            // Event settings
            $table->boolean('is_public')->default(true); // Pubblico o privato
            $table->integer('max_participants')->nullable(); // Limite partecipanti
            $table->decimal('entry_fee', 8, 2)->default(0); // Costo partecipazione
            $table->string('status')->default('draft'); // draft, published, cancelled, completed

            // Organization
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('venue_owner_id')->nullable()->constrained('users')->onDelete('set null');

            // Additional settings
            $table->boolean('allow_requests')->default(true); // Consente auto-candidature
            $table->json('tags')->nullable(); // Tag dell'evento
            $table->string('image_url')->nullable(); // Immagine evento

            $table->timestamps();

            // Indexes for better performance
            $table->index(['organizer_id', 'status']);
            $table->index(['start_datetime', 'is_public']);
            $table->index(['latitude', 'longitude']); // Per ricerche geografiche
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
