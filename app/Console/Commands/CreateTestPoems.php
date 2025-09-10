<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poem;
use App\Models\User;

class CreateTestPoems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-poems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea poesie di test senza immagini per testare i placeholder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📝 Creazione poesie di test...');

        // Ottieni un utente admin o il primo utente disponibile
        $user = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->first() ?? User::first();

        if (!$user) {
            $this->error('❌ Nessun utente trovato per creare le poesie');
            return;
        }

        $testPoems = [
            [
                'title' => 'La Voce del Silenzio',
                'content' => 'Nel silenzio della notte\nascolto il battito del cuore\nche racconta storie antiche\ndi amori perduti e ritrovati.\n\nLa luna danza nel cielo\nmentre le stelle sussurrano\nsegreto di mondi lontani\ndove tutto è possibile.\n\nE io, qui nel buio,\ntrovo la pace che cercavo\nnella voce del silenzio\nche parla senza parole.',
                'description' => 'Una poesia che esplora la bellezza del silenzio e la pace interiore.',
                'category' => 'amore',
                'poem_type' => 'sonetto',
                'language' => 'it',
                'tags' => 'silenzio, pace, notte, luna',
                'is_public' => true,
                'is_draft' => false,
            ],
            [
                'title' => 'Versi di Libertà',
                'content' => 'Libertà è volare alto\noltre le nuvole del dubbio\noltre i muri della paura\nverso l\'infinito del sogno.\n\nLibertà è essere se stessi\nsenza maschere né finzioni\ncon la verità nel cuore\ne la passione negli occhi.\n\nLibertà è amare senza limiti\ncreare senza confini\nvivere senza rimpianti\nsognare senza paura.',
                'description' => 'Un inno alla libertà e all\'autenticità.',
                'category' => 'libertà',
                'poem_type' => 'libera',
                'language' => 'it',
                'tags' => 'libertà, sogno, autenticità, passione',
                'is_public' => true,
                'is_draft' => false,
            ],
            [
                'title' => 'Il Tempo che Passa',
                'content' => 'Il tempo scorre come un fiume\nportando via i ricordi\nma lasciando tracce profonde\nnel cuore di chi sa ascoltare.\n\nOgni momento è un tesoro\nogni istante una possibilità\ndi creare qualcosa di bello\nche duri per l\'eternità.\n\nIl tempo che passa\nnon è perduto se vissuto\ncon amore e passione\nnel presente che ci appartiene.',
                'description' => 'Una riflessione sul tempo e sul valore del presente.',
                'category' => 'riflessione',
                'poem_type' => 'libera',
                'language' => 'it',
                'tags' => 'tempo, presente, riflessione, vita',
                'is_public' => true,
                'is_draft' => false,
            ],
            [
                'title' => 'Sogni di Carta',
                'content' => 'I sogni sono fatti di carta\nfragili come foglie d\'autunno\nma forti come la speranza\nche non muore mai.\n\nSu ogni foglio scrivo\nle mie emozioni più profonde\ni miei desideri più segreti\nle mie paure più nascoste.\n\nE quando il vento li porta via\nso che torneranno\nsotto forma di poesia\nper illuminare il mondo.',
                'description' => 'Una poesia sui sogni e sulla scrittura come forma di espressione.',
                'category' => 'sogni',
                'poem_type' => 'libera',
                'language' => 'it',
                'tags' => 'sogni, scrittura, speranza, poesia',
                'is_public' => true,
                'is_draft' => false,
            ],
            [
                'title' => 'La Danza delle Emozioni',
                'content' => 'Le emozioni danzano nel cuore\ncome farfalle colorate\nche si librano nell\'aria\ncreando magia e bellezza.\n\nLa gioia salta e ride\nla tristezza piange piano\nla rabbia urla e tuona\nl\'amore canta e sussurra.\n\nE io, spettatore di questa danza\nmi lascio trasportare\ndalla musica delle emozioni\nche rende la vita un\'opera d\'arte.',
                'description' => 'Una poesia che descrive le emozioni come una danza colorata.',
                'category' => 'emozioni',
                'poem_type' => 'libera',
                'language' => 'it',
                'tags' => 'emozioni, danza, vita, arte',
                'is_public' => true,
                'is_draft' => false,
            ],
        ];

        foreach ($testPoems as $index => $poemData) {
            $poem = Poem::create([
                'user_id' => $user->id,
                'title' => $poemData['title'],
                'content' => $poemData['content'],
                'description' => $poemData['description'],
                'category' => $poemData['category'],
                'poem_type' => $poemData['poem_type'],
                'language' => $poemData['language'],
                'tags' => $poemData['tags'],
                'is_public' => $poemData['is_public'],
                'is_draft' => $poemData['is_draft'],
                'moderation_status' => 'approved', // Approvato per essere visibile
                'thumbnail_path' => null, // Nessuna immagine per testare i placeholder
                'published_at' => now()->subDays(rand(1, 30)),
                'view_count' => rand(1, 100),
                'like_count' => rand(0, 50),
                'comment_count' => rand(0, 20),
            ]);

            $this->info("✅ Creato: {$poem->title}");
        }

        $this->info("🎯 Create " . count($testPoems) . " poesie di test senza immagini");
        $this->info('🎉 Operazione completata!');
    }
}
