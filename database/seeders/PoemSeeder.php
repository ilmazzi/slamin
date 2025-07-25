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
                'category' => 'Natura',
                'language' => 'it',
                'tags' => ['natura', 'notte', 'luna', 'stelle'],
                'is_featured' => true,
            ],
            [
                'title' => 'Il Vento del Cambiamento',
                'content' => "Il vento soffia forte,\nportando con sé il cambiamento,\nle foglie danzano nell'aria,\ncome pensieri nel momento.\n\nNulla rimane fermo,\ntutto si trasforma,\ne io mi adatto,\nalla vita che si riforma.",
                'description' => 'Una riflessione sul cambiamento e la trasformazione della vita.',
                'category' => 'Filosofia',
                'language' => 'it',
                'tags' => ['cambiamento', 'vita', 'trasformazione', 'vento'],
                'is_featured' => false,
            ],
            [
                'title' => 'L\'Amore che Non Muore',
                'content' => "Il tuo sorriso illumina i miei giorni,\ncome il sole illumina la terra,\nla tua voce è musica per le mie orecchie,\ne il tuo amore mi libera dalla guerra.\n\nInsieme siamo più forti,\ninsieme possiamo tutto,\nil nostro amore è eterno,\ncome il cielo infinito e profondo.",
                'description' => 'Una poesia d\'amore che celebra la forza dell\'unione.',
                'category' => 'Amore',
                'language' => 'it',
                'tags' => ['amore', 'unione', 'eternità', 'forza'],
                'is_featured' => true,
            ],
            [
                'title' => 'La Città che Dorme',
                'content' => "Le strade sono vuote,\nle luci sono spente,\nla città dorme profondamente,\nmentre io veglio nella mente.\n\nI pensieri si rincorrono,\ncome ombre nella notte,\ne io li osservo,\ncon occhi pieni di luce.",
                'description' => 'Una poesia sulla solitudine notturna e la riflessione interiore.',
                'category' => 'Riflessione',
                'language' => 'it',
                'tags' => ['città', 'notte', 'solitudine', 'riflessione'],
                'is_featured' => false,
            ],
            [
                'title' => 'Il Mare della Vita',
                'content' => "La vita è come il mare,\ncon onde che vanno e vengono,\nmomenti di calma e tempesta,\nche ci insegnano a vivere.\n\nOgni onda porta un messaggio,\nogni goccia una lezione,\ne noi navighiamo,\nverso la nostra destinazione.",
                'description' => 'Una metafora della vita attraverso l\'immagine del mare.',
                'category' => 'Vita',
                'language' => 'it',
                'tags' => ['vita', 'mare', 'onde', 'destinazione'],
                'is_featured' => true,
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
                'language' => $poemData['language'],
                'tags' => $poemData['tags'],
                'is_public' => true,
                'moderation_status' => 'approved',
                'is_featured' => $poemData['is_featured'],
                'view_count' => rand(10, 500),
                'like_count' => rand(5, 100),
                'comment_count' => rand(0, 20),
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
