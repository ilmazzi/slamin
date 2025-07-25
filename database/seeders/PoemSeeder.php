<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Poem;
use App\Models\User;

class PoemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ottieni alcuni utenti per associare le poesie
        $users = User::take(5)->get();
        
        if ($users->isEmpty()) {
            return;
        }

        $poems = [
            [
                'title' => 'Il Silenzio della Notte',
                'content' => "Nel buio della notte,\nquando tutto tace,\nla luna illumina il mio cuore\ncon la sua luce di pace.\n\nLe stelle brillano lassù,\ncome diamanti nel cielo,\ne io mi sento piccolo,\nma parte di questo anelo.",
                'description' => 'Una poesia sulla quiete della notte e la bellezza del cielo stellato.',
                'category' => 'nature',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['natura', 'notte', 'luna', 'stelle'],
                'is_featured' => true,
                'translation_available' => true,
                'translation_price' => 25.00,
            ],
            [
                'title' => 'Il Vento del Cambiamento',
                'content' => "Il vento soffia forte,\nportando con sé il cambiamento,\nle foglie danzano nell'aria,\ncome pensieri nel momento.\n\nNulla rimane fermo,\ntutto si trasforma,\ne io mi adatto,\nalla vita che si riforma.",
                'description' => 'Una riflessione sul cambiamento e la trasformazione della vita.',
                'category' => 'philosophy',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['cambiamento', 'vita', 'trasformazione', 'vento'],
                'is_featured' => false,
                'translation_available' => false,
            ],
            [
                'title' => 'L\'Amore che Non Muore',
                'content' => "Il tuo sorriso illumina i miei giorni,\ncome il sole illumina la terra,\nla tua voce è musica per le mie orecchie,\ne il tuo amore mi libera dalla guerra.\n\nInsieme siamo più forti,\ninsieme possiamo tutto,\nil nostro amore è eterno,\ncome il cielo infinito e profondo.",
                'description' => 'Una poesia d\'amore che celebra la forza dell\'unione.',
                'category' => 'love',
                'poem_type' => 'sonnet',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['amore', 'unione', 'eternità', 'forza'],
                'is_featured' => true,
                'translation_available' => true,
                'translation_price' => 30.00,
            ],
            [
                'title' => 'La Città che Dorme',
                'content' => "Le strade sono vuote,\nle luci sono spente,\nla città dorme profondamente,\nmentre io veglio nella mente.\n\nI pensieri si rincorrono,\ncome ombre nella notte,\ne io li osservo,\ncon occhi pieni di luce.",
                'description' => 'Una poesia sulla solitudine notturna e la riflessione interiore.',
                'category' => 'personal',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['città', 'notte', 'solitudine', 'riflessione'],
                'is_featured' => false,
                'translation_available' => false,
            ],
            [
                'title' => 'Il Mare della Vita',
                'content' => "La vita è come il mare,\ncon onde che vanno e vengono,\nmomenti di calma e tempesta,\nche ci insegnano a vivere.\n\nOgni onda porta un messaggio,\nogni goccia una lezione,\ne noi navighiamo,\nverso la nostra destinazione.",
                'description' => 'Una metafora della vita attraverso l\'immagine del mare.',
                'category' => 'life',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['vita', 'mare', 'onde', 'destinazione'],
                'is_featured' => true,
                'translation_available' => true,
                'translation_price' => 20.00,
            ],
            [
                'title' => 'Haiku della Primavera',
                'content' => "Fiori di ciliegio\ncadono delicatamente\nsul sentiero vuoto",
                'description' => 'Un haiku tradizionale sulla bellezza della primavera.',
                'category' => 'nature',
                'poem_type' => 'haiku',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['haiku', 'primavera', 'ciliegio', 'natura'],
                'is_featured' => true,
                'translation_available' => true,
                'translation_price' => 15.00,
            ],
            [
                'title' => 'Il Limerick del Poeta',
                'content' => "C'era un poeta di Roma\nche scriveva solo in aroma,\nma un giorno per caso\nincontrò un abbraccio\ne ora scrive d'amore con coma.",
                'description' => 'Un limerick divertente sulla vita del poeta.',
                'category' => 'personal',
                'poem_type' => 'limerick',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['limerick', 'poeta', 'divertimento', 'amore'],
                'is_featured' => false,
                'translation_available' => false,
            ],
        ];

        foreach ($poems as $poemData) {
            $user = $users->random();
            
            Poem::create([
                'title' => $poemData['title'],
                'content' => $poemData['content'],
                'description' => $poemData['description'],
                'user_id' => $user->id,
                'category' => $poemData['category'],
                'poem_type' => $poemData['poem_type'],
                'language' => $poemData['language'],
                'original_language' => $poemData['original_language'],
                'tags' => $poemData['tags'],
                'is_public' => true,
                'moderation_status' => 'approved',
                'is_featured' => $poemData['is_featured'],
                'translation_available' => $poemData['translation_available'],
                'translation_price' => $poemData['translation_price'] ?? null,
                'view_count' => rand(10, 500),
                'like_count' => rand(5, 100),
                'comment_count' => rand(0, 20),
                'share_count' => rand(0, 50),
                'bookmark_count' => rand(0, 30),
                'word_count' => str_word_count(strip_tags($poemData['content'])),
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
