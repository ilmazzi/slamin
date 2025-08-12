<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MoreArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::take(5)->get();
        $categories = ArticleCategory::all();
        $tags = ArticleTag::all();

        if ($users->isEmpty() || $categories->isEmpty() || $tags->isEmpty()) {
            $this->command->error('Utenti, categorie o tag mancanti. Esegui prima i seeder necessari.');
            return;
        }

        $this->command->info('Creazione di 20 articoli aggiuntivi...');

        $articles = [
            [
                'title' => 'Tecniche di Respirazione Avanzate per Performance',
                'content' => '<h2>Respirazione Diaframmatica</h2><p>La respirazione diaframmatica è fondamentale per mantenere il controllo della voce durante una performance.</p><h3>Esercizi Pratici</h3><ul><li>Respirazione 4-7-8</li><li>Respirazione quadrata</li><li>Respirazione alternata</li></ul>',
                'excerpt' => 'Scopri le tecniche avanzate di respirazione per migliorare le tue performance di Poetry Slam.',
                'featured' => true,
                'views' => 245,
                'likes' => 38,
                'comments' => 12,
                'days_ago' => 2
            ],
            [
                'title' => 'Come Scrivere Testi Emotivamente Coinvolgenti',
                'content' => '<h2>L\'Importanza dell\'Emozione</h2><p>I testi che toccano il cuore del pubblico sono quelli che nascono da esperienze personali autentiche.</p><h3>Strategie di Scrittura</h3><ul><li>Usa dettagli specifici</li><li>Racconta storie personali</li><li>Crea immagini vivide</li></ul>',
                'excerpt' => 'Impara a scrivere testi che emozionano e coinvolgono profondamente il pubblico.',
                'featured' => false,
                'views' => 189,
                'likes' => 25,
                'comments' => 8,
                'days_ago' => 4
            ],
            [
                'title' => 'Gestione dell\'Ansia Prima di una Performance',
                'content' => '<h2>L\'Ansia è Normale</h2><p>L\'ansia pre-performance è una reazione naturale del corpo. Imparare a gestirla è parte del processo.</p><h3>Tecniche di Gestione</h3><ul><li>Visualizzazione positiva</li><li>Respirazione profonda</li><li>Routine pre-performance</li></ul>',
                'excerpt' => 'Strategie pratiche per gestire l\'ansia e trasformarla in energia positiva.',
                'featured' => false,
                'views' => 156,
                'likes' => 19,
                'comments' => 6,
                'days_ago' => 6
            ],
            [
                'title' => 'Storia del Poetry Slam in Europa',
                'content' => '<h2>Le Origini Europee</h2><p>Il Poetry Slam si è diffuso in Europa negli anni \'90, creando una scena vibrante e diversificata.</p><h3>Paesi Pionieri</h3><ul><li>Germania</li><li>Francia</li><li>Regno Unito</li><li>Italia</li></ul>',
                'excerpt' => 'Un viaggio attraverso la storia del Poetry Slam nel continente europeo.',
                'featured' => true,
                'views' => 312,
                'likes' => 42,
                'comments' => 15,
                'days_ago' => 8
            ],
            [
                'title' => 'Come Costruire una Community di Poetry Slam',
                'content' => '<h2>L\'Importanza della Community</h2><p>Una community forte è la base per il successo del Poetry Slam in ogni città.</p><h3>Elementi Chiave</h3><ul><li>Eventi regolari</li><li>Workshop e formazione</li><li>Collaborazioni</li><li>Supporto reciproco</li></ul>',
                'excerpt' => 'Guida pratica per creare e mantenere una community di Poetry Slam attiva.',
                'featured' => false,
                'views' => 134,
                'likes' => 17,
                'comments' => 5,
                'days_ago' => 10
            ],
            [
                'title' => 'Tecniche di Memoria per Poeti Slam',
                'content' => '<h2>Memorizzazione Efficace</h2><p>Imparare a memoria i propri testi è essenziale per una performance fluida e coinvolgente.</p><h3>Metodi di Studio</h3><ul><li>Ripetizione spaziale</li><li>Associazioni mentali</li><li>Pratica costante</li></ul>',
                'excerpt' => 'Strategie per memorizzare efficacemente i testi e migliorare le performance.',
                'featured' => false,
                'views' => 178,
                'likes' => 23,
                'comments' => 7,
                'days_ago' => 12
            ],
            [
                'title' => 'Poetry Slam e Social Media: Una Guida Completa',
                'content' => '<h2>L\'Era Digitale</h2><p>I social media hanno rivoluzionato il modo in cui i poeti slam si connettono con il pubblico.</p><h3>Piattaforme Principali</h3><ul><li>Instagram per video brevi</li><li>YouTube per performance complete</li><li>TikTok per contenuti virali</li></ul>',
                'excerpt' => 'Come utilizzare i social media per promuovere il tuo Poetry Slam e raggiungere un pubblico più ampio.',
                'featured' => true,
                'views' => 423,
                'likes' => 56,
                'comments' => 18,
                'days_ago' => 15
            ],
            [
                'title' => 'Workshop di Scrittura Creativa per Principianti',
                'content' => '<h2>Iniziare a Scrivere</h2><p>La scrittura creativa è un\'arte che si può imparare e migliorare con la pratica.</p><h3>Esercizi Base</h3><ul><li>Scrittura libera quotidiana</li><li>Osservazione del mondo</li><li>Esperimenti con forme poetiche</li></ul>',
                'excerpt' => 'Un workshop completo per chi vuole iniziare il percorso della scrittura creativa.',
                'featured' => false,
                'views' => 98,
                'likes' => 12,
                'comments' => 4,
                'days_ago' => 18
            ],
            [
                'title' => 'Eventi Poetry Slam a Milano: Calendario 2024',
                'content' => '<h2>La Scena Milanese</h2><p>Milano ospita una delle scene di Poetry Slam più vivaci d\'Italia.</p><h3>Eventi Principali</h3><ul><li>Open Mic settimanali</li><li>Competizioni mensili</li><li>Festival annuali</li><li>Workshop specializzati</li></ul>',
                'excerpt' => 'Tutto quello che devi sapere sugli eventi di Poetry Slam a Milano nel 2024.',
                'featured' => false,
                'views' => 267,
                'likes' => 34,
                'comments' => 11,
                'days_ago' => 20
            ],
            [
                'title' => 'Come Organizzare un Evento Poetry Slam di Successo',
                'content' => '<h2>Pianificazione dell\'Evento</h2><p>Organizzare un evento Poetry Slam richiede attenzione ai dettagli e passione per l\'arte.</p><h3>Elementi Essenziali</h3><ul><li>Location adeguata</li><li>Promozione efficace</li><li>Logistica impeccabile</li><li>Atmosfera accogliente</li></ul>',
                'excerpt' => 'Guida completa per organizzare eventi Poetry Slam memorabili e di successo.',
                'featured' => false,
                'views' => 145,
                'likes' => 18,
                'comments' => 6,
                'days_ago' => 22
            ],
            [
                'title' => 'Tecniche di Voce per Poeti Slam',
                'content' => '<h2>La Voce come Strumento</h2><p>La voce è lo strumento principale del poeta slam. Prendersene cura è fondamentale.</p><h3>Esercizi Vocali</h3><ul><li>Riscaldamento vocale</li><li>Controllo del tono</li><li>Proiezione della voce</li><li>Articolazione</li></ul>',
                'excerpt' => 'Esercizi e tecniche per sviluppare una voce potente ed espressiva.',
                'featured' => false,
                'views' => 167,
                'likes' => 21,
                'comments' => 7,
                'days_ago' => 25
            ],
            [
                'title' => 'Poetry Slam e Inclusività: Creare Spazi Sicuri',
                'content' => '<h2>L\'Importanza dell\'Inclusività</h2><p>Il Poetry Slam deve essere uno spazio sicuro per tutte le voci e le identità.</p><h3>Pratiche Inclusive</h3><ul><li>Codici di condotta chiari</li><li>Diversità nella programmazione</li><li>Accessibilità fisica e culturale</li><li>Supporto per nuove voci</li></ul>',
                'excerpt' => 'Come creare eventi Poetry Slam inclusivi e accoglienti per tutti.',
                'featured' => false,
                'views' => 203,
                'likes' => 28,
                'comments' => 9,
                'days_ago' => 28
            ],
            [
                'title' => 'Collaborazioni tra Poeti Slam e Musicisti',
                'content' => '<h2>Poesia e Musica</h2><p>La collaborazione tra poeti slam e musicisti può creare esperienze artistiche uniche.</p><h3>Formati di Collaborazione</h3><ul><li>Poesia con accompagnamento musicale</li><li>Performance interdisciplinari</li><li>Progetti di registrazione</li><li>Eventi multimediali</li></ul>',
                'excerpt' => 'Esplora le possibilità creative della collaborazione tra poesia e musica.',
                'featured' => false,
                'views' => 134,
                'likes' => 16,
                'comments' => 5,
                'days_ago' => 30
            ],
            [
                'title' => 'Poetry Slam Digitale: Eventi Online e Ibridi',
                'content' => '<h2>L\'Era del Digitale</h2><p>La pandemia ha accelerato l\'adozione di format digitali per il Poetry Slam.</p><h3>Vantaggi del Digitale</h3><ul><li>Accessibilità globale</li><li>Riduzione dei costi</li><li>Possibilità di registrazione</li><li>Interazione innovativa</li></ul>',
                'excerpt' => 'Come organizzare e partecipare a eventi Poetry Slam online e ibridi.',
                'featured' => false,
                'views' => 189,
                'likes' => 24,
                'comments' => 8,
                'days_ago' => 32
            ],
            [
                'title' => 'Mentorship nel Poetry Slam: Crescere Insieme',
                'content' => '<h2>Il Valore della Mentorship</h2><p>I programmi di mentorship sono fondamentali per la crescita della community.</p><h3>Benefici della Mentorship</h3><ul><li>Apprendimento accelerato</li><li>Supporto emotivo</li><li>Connessioni professionali</li><li>Feedback costruttivo</li></ul>',
                'excerpt' => 'Come i programmi di mentorship stanno trasformando la scena del Poetry Slam.',
                'featured' => false,
                'views' => 112,
                'likes' => 14,
                'comments' => 4,
                'days_ago' => 35
            ],
            [
                'title' => 'Poetry Slam e Attivismo: La Voce del Cambiamento',
                'content' => '<h2>Poesia come Attivismo</h2><p>Il Poetry Slam è spesso utilizzato come strumento di attivismo e cambiamento sociale.</p><h3>Temi Sociali</h3><ul><li>Giustizia sociale</li><li>Diritti umani</li><li>Ambiente</li><li>Uguaglianza</li></ul>',
                'excerpt' => 'Come il Poetry Slam può essere una potente voce per il cambiamento sociale.',
                'featured' => false,
                'views' => 278,
                'likes' => 37,
                'comments' => 12,
                'days_ago' => 38
            ],
            [
                'title' => 'Tecniche di Editing per Testi di Poetry Slam',
                'content' => '<h2>L\'Arte dell\'Editing</h2><p>L\'editing è una parte cruciale del processo creativo per ogni poeta slam.</p><h3>Strategie di Editing</h3><ul><li>Revisione per ritmo</li><li>Ottimizzazione per performance</li><li>Feedback da altri poeti</li><li>Test con il pubblico</li></ul>',
                'excerpt' => 'Guida pratica per migliorare i tuoi testi attraverso l\'editing efficace.',
                'featured' => false,
                'views' => 156,
                'likes' => 19,
                'comments' => 6,
                'days_ago' => 40
            ],
            [
                'title' => 'Poetry Slam e Educazione: Portare la Poesia nelle Scuole',
                'content' => '<h2>Poesia nell\'Educazione</h2><p>Il Poetry Slam può essere un potente strumento educativo nelle scuole.</p><h3>Benefici Educativi</h3><ul><li>Sviluppo della creatività</li><li>Miglioramento delle competenze linguistiche</li><li>Fiducia in se stessi</li><li>Espressione emotiva</li></ul>',
                'excerpt' => 'Come il Poetry Slam sta trasformando l\'educazione nelle scuole italiane.',
                'featured' => false,
                'views' => 198,
                'likes' => 26,
                'comments' => 8,
                'days_ago' => 42
            ],
            [
                'title' => 'Festival di Poetry Slam in Italia: Guida Completa 2024',
                'content' => '<h2>I Festival Principali</h2><p>L\'Italia ospita numerosi festival di Poetry Slam durante tutto l\'anno.</p><h3>Festival da Non Perdere</h3><ul><li>Festival di Roma</li><li>Slam Poetry Milano</li><li>Poeti in Piazza Firenze</li><li>Festival di Torino</li></ul>',
                'excerpt' => 'Tutti i festival di Poetry Slam da non perdere in Italia nel 2024.',
                'featured' => false,
                'views' => 345,
                'likes' => 45,
                'comments' => 14,
                'days_ago' => 45
            ]
        ];

        foreach ($articles as $articleData) {
            $user = $users->random();
            $category = $categories->random();
            $selectedTags = $tags->random(rand(2, 4));

            $article = Article::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => [
                    'it' => $articleData['title'],
                    'en' => $articleData['title'],
                    'es' => $articleData['title'],
                    'fr' => $articleData['title'],
                    'de' => $articleData['title'],
                    'pt' => $articleData['title']
                ],
                'content' => [
                    'it' => $articleData['content'],
                    'en' => $articleData['content'],
                    'es' => $articleData['content'],
                    'fr' => $articleData['content'],
                    'de' => $articleData['content'],
                    'pt' => $articleData['content']
                ],
                'excerpt' => [
                    'it' => $articleData['excerpt'],
                    'en' => $articleData['excerpt'],
                    'es' => $articleData['excerpt'],
                    'fr' => $articleData['excerpt'],
                    'de' => $articleData['excerpt'],
                    'pt' => $articleData['excerpt']
                ],
                'status' => 'published',
                'featured' => $articleData['featured'],
                'views_count' => $articleData['views'],
                'likes_count' => $articleData['likes'],
                'comments_count' => $articleData['comments'],
                'slug' => Str::slug($articleData['title']),
                'meta_title' => [
                    'it' => $articleData['title'],
                    'en' => $articleData['title'],
                    'es' => $articleData['title'],
                    'fr' => $articleData['title'],
                    'de' => $articleData['title'],
                    'pt' => $articleData['title']
                ],
                'meta_description' => [
                    'it' => $articleData['excerpt'],
                    'en' => $articleData['excerpt'],
                    'es' => $articleData['excerpt'],
                    'fr' => $articleData['excerpt'],
                    'de' => $articleData['excerpt'],
                    'pt' => $articleData['excerpt']
                ],
                'published_at' => Carbon::now()->subDays($articleData['days_ago']),
                'moderation_status' => 'approved',
                'is_public' => true
            ]);

            // Associa i tag
            $article->tags()->attach($selectedTags->pluck('id'));

            $this->command->info("Articolo creato: {$articleData['title']}");
        }

        $this->command->info('Seeder completato! Creati ' . count($articles) . ' articoli aggiuntivi.');
    }
}
