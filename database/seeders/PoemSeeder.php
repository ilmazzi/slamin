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
                'thumbnail' => 'https://images.unsplash.com/photo-1532798442725-41036acc7489?w=400&h=300&fit=crop',
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
                'thumbnail' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=300&fit=crop',
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
                'thumbnail' => 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?w=400&h=300&fit=crop',
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
                'thumbnail' => 'https://images.unsplash.com/photo-1519501025264-65ba15a82390?w=400&h=300&fit=crop',
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
                'thumbnail' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&h=300&fit=crop',
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
                'thumbnail' => 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?w=400&h=300&fit=crop',
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
                'thumbnail' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'La Danza delle Foglie',
                'content' => "Autunno arriva piano,\nle foglie danzano nel vento,\ncolori di oro e rame,\nun balletto silenzioso e lento.\n\nOgni foglia ha la sua storia,\nogni caduta un nuovo inizio,\nla natura ci insegna,\nche ogni fine è un principio.",
                'description' => 'Una poesia sull\'autunno e la bellezza del cambiamento stagionale.',
                'category' => 'nature',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['autunno', 'foglie', 'cambiamento', 'natura'],
                'is_featured' => true,
                'translation_available' => true,
                'translation_price' => 22.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'Il Sussurro del Tempo',
                'content' => "Il tempo scorre silenzioso,\ncome un fiume che non si ferma mai,\nogni secondo è prezioso,\nogni momento un dono che non tornerà.\n\nImparo a vivere nel presente,\na godere di ogni respiro,\nperché il futuro è incerto,\ne il passato è già trascorso.",
                'description' => 'Una riflessione filosofica sul tempo e la sua preziosità.',
                'category' => 'philosophy',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['tempo', 'filosofia', 'presente', 'vita'],
                'is_featured' => false,
                'translation_available' => true,
                'translation_price' => 28.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'La Musica del Cuore',
                'content' => "Il mio cuore batte,\ncome un tamburo nella notte,\nogni pulsazione una nota,\nogni battito una melodia.\n\nLa musica del mio essere,\nrisuona nell\'universo,\ne io danzo al suo ritmo,\nliberamente, senza paura.",
                'description' => 'Una poesia sulla musica interiore e la libertà dell\'anima.',
                'category' => 'personal',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['musica', 'cuore', 'libertà', 'anima'],
                'is_featured' => true,
                'translation_available' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'Il Volo della Libertà',
                'content' => "Come un uccello nel cielo,\nspiego le ali della libertà,\nvolo alto sopra le nuvole,\nverso orizzonti infiniti.\n\nNessuna gabbia mi trattiene,\nnessuna catena mi lega,\nsono libero di essere me stesso,\nliberamente, senza limiti.",
                'description' => 'Una poesia sulla libertà e la ricerca della propria identità.',
                'category' => 'personal',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['libertà', 'volo', 'identità', 'orizzonti'],
                'is_featured' => false,
                'translation_available' => true,
                'translation_price' => 25.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'Il Sonetto della Speranza',
                'content' => "Quando tutto sembra perduto,\ne il buio avvolge il mio cammino,\nuna luce brilla nel mio cuore,\nportando speranza e destino.\n\nLa speranza è come una stella,\nche guida i marinai nel mare,\nè la forza che mi sostiene,\nquando tutto sembra crollare.\n\nAnche nelle ore più buie,\nquando la tempesta infuria,\nla speranza mi dice di resistere,\ne che la luce tornerà a brillare.",
                'description' => 'Un sonetto sulla speranza e la resilienza dell\'animo umano.',
                'category' => 'philosophy',
                'poem_type' => 'sonnet',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['speranza', 'resilienza', 'luce', 'tempesta'],
                'is_featured' => true,
                'translation_available' => true,
                'translation_price' => 35.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'Il Canto della Montagna',
                'content' => "Le montagne cantano,\ncon voci antiche e profonde,\nstorie di ere passate,\nche il vento ancora racconta.\n\nRocce che hanno visto secoli,\nneve che non si scioglie mai,\nla montagna è maestosa,\ncome la saggezza che non muore.",
                'description' => 'Una poesia sulla maestosità delle montagne e la loro saggezza millenaria.',
                'category' => 'nature',
                'poem_type' => 'free_verse',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['montagne', 'saggezza', 'antichità', 'maestosità'],
                'is_featured' => false,
                'translation_available' => true,
                'translation_price' => 20.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'La Ballata del Viaggiatore',
                'content' => "Ho viaggiato per strade infinite,\nattraverso terre sconosciute,\nogni passo una scoperta,\nogni incontro una fortuna.\n\nIl viaggio mi ha insegnato,\nche il mondo è vasto e bello,\nche ogni cultura ha la sua storia,\ne ogni persona ha la sua stella.",
                'description' => 'Una ballata sui viaggi e le scoperte che arricchiscono l\'anima.',
                'category' => 'life',
                'poem_type' => 'ballad',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['viaggio', 'scoperta', 'cultura', 'mondo'],
                'is_featured' => true,
                'translation_available' => true,
                'translation_price' => 30.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'L\'Elegia del Tramonto',
                'content' => "Il sole si nasconde,\ndietro l\'orizzonte dorato,\nlasciando il cielo dipinto,\ncon colori incantati.\n\nÈ l\'ora del tramonto,\nquando tutto si addolcisce,\ne il mondo si prepara,\nper la notte che avanza.",
                'description' => 'Un\'elegia sulla bellezza del tramonto e la transizione verso la notte.',
                'category' => 'nature',
                'poem_type' => 'elegy',
                'language' => 'it',
                'original_language' => 'it',
                'tags' => ['tramonto', 'bellezza', 'transizione', 'notte'],
                'is_featured' => false,
                'translation_available' => false,
                'thumbnail' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop',
            ],
        ];

        foreach ($poems as $poemData) {
            $user = $users->random();
            
            // Genera uno slug unico aggiungendo un timestamp
            $baseSlug = \Illuminate\Support\Str::slug($poemData['title']);
            $uniqueSlug = $baseSlug . '-' . time() . '-' . rand(1000, 9999);
            
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
                'thumbnail' => $poemData['thumbnail'],
                'thumbnail_path' => $poemData['thumbnail'],
                'slug' => $uniqueSlug,
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
