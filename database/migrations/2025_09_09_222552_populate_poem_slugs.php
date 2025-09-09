<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Poem;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Popola gli slug mancanti per tutte le poesie
        $poems = Poem::where(function($q) {
            $q->whereNull('slug')->orWhere('slug', '');
        })->get();

        foreach ($poems as $poem) {
            $title = $poem->title ?: 'poesia-senza-titolo';
            $baseSlug = Str::slug($title);
            $slug = $baseSlug;
            $counter = 1;

            // Assicurati che lo slug sia unico
            while (Poem::where('slug', $slug)->where('id', '!=', $poem->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $poem->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non facciamo nulla nel down perché non vogliamo perdere gli slug
    }
};
