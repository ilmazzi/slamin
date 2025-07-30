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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // URL dell'immagine del gruppo
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->unsignedBigInteger('created_by'); // Chi ha creato il gruppo
            $table->timestamps();
            
            // Indici
            $table->index('created_by');
            $table->index('visibility');
            $table->index(['created_by', 'visibility']);
            
            // Foreign key
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
