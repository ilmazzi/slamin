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

class ArticleFakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assicurati che ci siano utenti, categorie e tag
        $users = User::take(5)->get();
        if ($users->isEmpty()) {
            $this->command->error('Nessun utente trovato. Crea prima alcuni utenti.');
            return;
        }

        $categories = ArticleCategory::all();
        if ($categories->isEmpty()) {
            $this->command->error('Nessuna categoria trovata. Esegui prima ArticleDataSeeder.');
            return;
        }

        $tags = ArticleTag::all();
        if ($tags->isEmpty()) {
            $this->command->error('Nessun tag trovato. Esegui prima ArticleDataSeeder.');
            return;
        }

        $this->command->info('Creazione articoli finti...');

        $articles = [
            [
                'title' => [
                    'it' => 'La Magia del Poetry Slam: Come Incantare il Pubblico',
                    'en' => 'The Magic of Poetry Slam: How to Enchant the Audience',
                    'es' => 'La Magia del Poetry Slam: Cómo Encantar a la Audiencia',
                    'fr' => 'La Magie du Poetry Slam: Comment Enchanter le Public',
                    'de' => 'Die Magie des Poetry Slam: Wie man das Publikum verzaubert',
                    'pt' => 'A Magia do Poetry Slam: Como Encantar o Público'
                ],
                'content' => [
                    'it' => '<h2>Introduzione al Poetry Slam</h2><p>Il Poetry Slam è più di una semplice competizione poetica. È un\'arte che combina parole, performance e connessione umana in un\'esperienza unica e coinvolgente.</p><h3>Elementi Chiave della Performance</h3><ul><li><strong>Presenza scenica:</strong> La capacità di riempire lo spazio e catturare l\'attenzione</li><li><strong>Timing perfetto:</strong> Il ritmo e la cadenza delle parole</li><li><strong>Connessione emotiva:</strong> Creare un legame con il pubblico</li><li><strong>Autenticità:</strong> Essere veri e sinceri nella propria espressione</li></ul><h3>Consigli per Principianti</h3><p>Inizia con testi che conosci bene e che ti emozionano. La passione è contagiosa e il pubblico la percepisce immediatamente. Pratica davanti allo specchio, registrati e ascoltati. Ogni performance è un\'opportunità per crescere e migliorare.</p><h3>Conclusione</h3><p>Il Poetry Slam è un viaggio di scoperta personale e artistica. Ogni poeta ha una voce unica da condividere con il mondo.</p>',
                    'en' => '<h2>Introduction to Poetry Slam</h2><p>Poetry Slam is more than just a poetry competition. It\'s an art that combines words, performance, and human connection in a unique and engaging experience.</p><h3>Key Elements of Performance</h3><ul><li><strong>Stage presence:</strong> The ability to fill the space and capture attention</li><li><strong>Perfect timing:</strong> The rhythm and cadence of words</li><li><strong>Emotional connection:</strong> Creating a bond with the audience</li><li><strong>Authenticity:</strong> Being true and sincere in your expression</li></ul><h3>Tips for Beginners</h3><p>Start with texts you know well and that move you. Passion is contagious and the audience perceives it immediately. Practice in front of a mirror, record yourself and listen. Every performance is an opportunity to grow and improve.</p><h3>Conclusion</h3><p>Poetry Slam is a journey of personal and artistic discovery. Every poet has a unique voice to share with the world.</p>',
                    'es' => '<h2>Introducción al Poetry Slam</h2><p>El Poetry Slam es más que una simple competencia poética. Es un arte que combina palabras, performance y conexión humana en una experiencia única y envolvente.</p><h3>Elementos Clave de la Performance</h3><ul><li><strong>Presencia escénica:</strong> La capacidad de llenar el espacio y capturar la atención</li><li><strong>Timing perfecto:</strong> El ritmo y la cadencia de las palabras</li><li><strong>Conexión emocional:</strong> Crear un vínculo con la audiencia</li><li><strong>Autenticidad:</strong> Ser verdadero y sincero en tu expresión</li></ul><h3>Consejos para Principiantes</h3><p>Comienza con textos que conozcas bien y que te muevan. La pasión es contagiosa y la audiencia la percibe inmediatamente. Practica frente al espejo, grábate y escucha. Cada performance es una oportunidad para crecer y mejorar.</p><h3>Conclusión</h3><p>El Poetry Slam es un viaje de descubrimiento personal y artístico. Cada poeta tiene una voz única para compartir con el mundo.</p>',
                    'fr' => '<h2>Introduction au Poetry Slam</h2><p>Le Poetry Slam est plus qu\'une simple compétition poétique. C\'est un art qui combine mots, performance et connexion humaine dans une expérience unique et engageante.</p><h3>Éléments Clés de la Performance</h3><ul><li><strong>Présence scénique:</strong> La capacité de remplir l\'espace et de capturer l\'attention</li><li><strong>Timing parfait:</strong> Le rythme et la cadence des mots</li><li><strong>Connexion émotionnelle:</strong> Créer un lien avec le public</li><li><strong>Authenticité:</strong> Être vrai et sincère dans son expression</li></ul><h3>Conseils pour les Débutants</h3><p>Commencez par des textes que vous connaissez bien et qui vous touchent. La passion est contagieuse et le public la perçoit immédiatement. Pratiquez devant un miroir, enregistrez-vous et écoutez. Chaque performance est une opportunité de grandir et de s\'améliorer.</p><h3>Conclusion</h3><p>Le Poetry Slam est un voyage de découverte personnelle et artistique. Chaque poète a une voix unique à partager avec le monde.</p>',
                    'de' => '<h2>Einführung in Poetry Slam</h2><p>Poetry Slam ist mehr als nur ein Dichtwettbewerb. Es ist eine Kunst, die Worte, Performance und menschliche Verbindung in einer einzigartigen und fesselnden Erfahrung kombiniert.</p><h3>Schlüsselelemente der Performance</h3><ul><li><strong>Bühn Präsenz:</strong> Die Fähigkeit, den Raum zu füllen und Aufmerksamkeit zu erregen</li><li><strong>Perfektes Timing:</strong> Der Rhythmus und die Kadenz der Worte</li><li><strong>Emotionale Verbindung:</strong> Eine Bindung zum Publikum schaffen</li><li><strong>Authentizität:</strong> Wahr und aufrichtig in deinem Ausdruck sein</li></ul><h3>Tipps für Anfänger</h3><p>Beginne mit Texten, die du gut kennst und die dich bewegen. Leidenschaft ist ansteckend und das Publikum nimmt sie sofort wahr. Übe vor dem Spiegel, nimm dich auf und höre zu. Jede Performance ist eine Gelegenheit zu wachsen und sich zu verbessern.</p><h3>Fazit</h3><p>Poetry Slam ist eine Reise der persönlichen und künstlerischen Entdeckung. Jeder Dichter hat eine einzigartige Stimme, die er mit der Welt teilen kann.</p>',
                    'pt' => '<h2>Introdução ao Poetry Slam</h2><p>O Poetry Slam é mais do que uma simples competição poética. É uma arte que combina palavras, performance e conexão humana em uma experiência única e envolvente.</p><h3>Elementos Chave da Performance</h3><ul><li><strong>Presença cênica:</strong> A capacidade de preencher o espaço e capturar a atenção</li><li><strong>Timing perfeito:</strong> O ritmo e a cadência das palavras</li><li><strong>Conexão emocional:</strong> Criar um vínculo com a audiência</li><li><strong>Autenticidade:</strong> Ser verdadeiro e sincero em sua expressão</li></ul><h3>Dicas para Iniciantes</h3><p>Comece com textos que você conhece bem e que te movem. A paixão é contagiosa e a audiência a percebe imediatamente. Pratique na frente do espelho, grave-se e escute. Cada performance é uma oportunidade para crescer e melhorar.</p><h3>Conclusão</h3><p>O Poetry Slam é uma jornada de descoberta pessoal e artística. Cada poeta tem uma voz única para compartilhar com o mundo.</p>'
                ],
                'excerpt' => [
                    'it' => 'Scopri i segreti per creare performance di Poetry Slam coinvolgenti e memorabili. Dalla presenza scenica alla connessione emotiva, tutto quello che devi sapere per incantare il pubblico.',
                    'en' => 'Discover the secrets to creating engaging and memorable Poetry Slam performances. From stage presence to emotional connection, everything you need to know to enchant the audience.',
                    'es' => 'Descubre los secretos para crear performances de Poetry Slam envolventes y memorables. Desde la presencia escénica hasta la conexión emocional, todo lo que necesitas saber para encantar a la audiencia.',
                    'fr' => 'Découvrez les secrets pour créer des performances de Poetry Slam engageantes et mémorables. De la présence scénique à la connexion émotionnelle, tout ce que vous devez savoir pour enchanter le public.',
                    'de' => 'Entdecke die Geheimnisse, um fesselnde und unvergessliche Poetry Slam Performances zu schaffen. Von der Bühn Präsenz bis zur emotionalen Verbindung, alles was du wissen musst, um das Publikum zu verzaubern.',
                    'pt' => 'Descubra os segredos para criar performances de Poetry Slam envolventes e memoráveis. Da presença cênica à conexão emocional, tudo o que você precisa saber para encantar a audiência.'
                ],
                'status' => 'published',
                'featured' => true,
                'views_count' => 156,
                'likes_count' => 23,
                'comments_count' => 8,
                'published_at' => Carbon::now()->subDays(5)
            ],
            [
                'title' => [
                    'it' => 'Tecniche di Respirazione per Poeti Slam',
                    'en' => 'Breathing Techniques for Slam Poets',
                    'es' => 'Técnicas de Respiración para Poetas Slam',
                    'fr' => 'Techniques de Respiration pour Poètes Slam',
                    'de' => 'Atemtechniken für Slam Poeten',
                    'pt' => 'Técnicas de Respiração para Poetas Slam'
                ],
                'content' => [
                    'it' => '<h2>L\'Importanza della Respirazione</h2><p>La respirazione è fondamentale per ogni poeta slam. Una respirazione corretta ti permette di mantenere il controllo della voce, gestire l\'emozione e creare pause drammatiche efficaci.</p><h3>Esercizi di Base</h3><ul><li><strong>Respirazione diaframmatica:</strong> Respira profondamente dal diaframma per una voce più potente</li><li><strong>Controllo del ritmo:</strong> Impara a sincronizzare la respirazione con il ritmo del testo</li><li><strong>Pause strategiche:</strong> Usa la respirazione per creare suspense e enfasi</li></ul><h3>Pratica Quotidiana</h3><p>Dedica almeno 10 minuti al giorno agli esercizi di respirazione. La costanza è la chiave per vedere risultati significativi.</p>',
                    'en' => '<h2>The Importance of Breathing</h2><p>Breathing is fundamental for every slam poet. Proper breathing allows you to maintain voice control, manage emotion, and create effective dramatic pauses.</p><h3>Basic Exercises</h3><ul><li><strong>Diaphragmatic breathing:</strong> Breathe deeply from the diaphragm for a more powerful voice</li><li><strong>Rhythm control:</strong> Learn to synchronize breathing with the rhythm of the text</li><li><strong>Strategic pauses:</strong> Use breathing to create suspense and emphasis</li></ul><h3>Daily Practice</h3><p>Dedicate at least 10 minutes a day to breathing exercises. Consistency is the key to seeing significant results.</p>',
                    'es' => '<h2>La Importancia de la Respiración</h2><p>La respiración es fundamental para cada poeta slam. Una respiración correcta te permite mantener el control de la voz, gestionar la emoción y crear pausas dramáticas efectivas.</p><h3>Ejercicios Básicos</h3><ul><li><strong>Respiración diafragmática:</strong> Respira profundamente desde el diafragma para una voz más potente</li><li><strong>Control del ritmo:</strong> Aprende a sincronizar la respiración con el ritmo del texto</li><li><strong>Pausas estratégicas:</strong> Usa la respiración para crear suspense y énfasis</li></ul><h3>Práctica Diaria</h3><p>Dedica al menos 10 minutos al día a los ejercicios de respiración. La constancia es la clave para ver resultados significativos.</p>',
                    'fr' => '<h2>L\'Importance de la Respiration</h2><p>La respiration est fondamentale pour chaque poète slam. Une respiration correcte vous permet de maintenir le contrôle de la voix, gérer l\'émotion et créer des pauses dramatiques efficaces.</p><h3>Exercices de Base</h3><ul><li><strong>Respiration diaphragmatique:</strong> Respirez profondément depuis le diaphragme pour une voix plus puissante</li><li><strong>Contrôle du rythme:</strong> Apprenez à synchroniser la respiration avec le rythme du texte</li><li><strong>Pauses stratégiques:</strong> Utilisez la respiration pour créer du suspense et de l\'emphase</li></ul><h3>Pratique Quotidienne</h3><p>Consacrez au moins 10 minutes par jour aux exercices de respiration. La constance est la clé pour voir des résultats significatifs.</p>',
                    'de' => '<h2>Die Bedeutung der Atmung</h2><p>Die Atmung ist fundamental für jeden Slam Poeten. Richtiges Atmen ermöglicht es dir, die Stimme zu kontrollieren, Emotionen zu managen und effektive dramatische Pausen zu schaffen.</p><h3>Grundübungen</h3><ul><li><strong>Zwerchfellatmung:</strong> Atme tief aus dem Zwerchfell für eine kraftvollere Stimme</li><li><strong>Rhythmuskontrolle:</strong> Lerne, die Atmung mit dem Rhythmus des Textes zu synchronisieren</li><li><strong>Strategische Pausen:</strong> Nutze die Atmung, um Spannung und Betonung zu schaffen</li></ul><h3>Tägliche Übung</h3><p>Widme mindestens 10 Minuten pro Tag den Atemübungen. Beständigkeit ist der Schlüssel, um signifikante Ergebnisse zu sehen.</p>',
                    'pt' => '<h2>A Importância da Respiração</h2><p>A respiração é fundamental para cada poeta slam. Uma respiração correta permite que você mantenha o controle da voz, gerencie a emoção e crie pausas dramáticas efetivas.</p><h3>Exercícios Básicos</h3><ul><li><strong>Respiração diafragmática:</strong> Respire profundamente do diafragma para uma voz mais potente</li><li><strong>Controle do ritmo:</strong> Aprenda a sincronizar a respiração com o ritmo do texto</li><li><strong>Pausas estratégicas:</strong> Use a respirazione para criar suspense e ênfase</li></ul><h3>Prática Diária</h3><p>Dedique pelo menos 10 minutos por dia aos exercícios de respiração. A constância é a chave para ver resultados significativos.</p>'
                ],
                'excerpt' => [
                    'it' => 'Impara le tecniche di respirazione essenziali per migliorare la tua performance di Poetry Slam. Dalla respirazione diaframmatica al controllo del ritmo.',
                    'en' => 'Learn the essential breathing techniques to improve your Poetry Slam performance. From diaphragmatic breathing to rhythm control.',
                    'es' => 'Aprende las técnicas de respiración esenciales para mejorar tu performance de Poetry Slam. Desde la respiración diafragmática hasta el control del ritmo.',
                    'fr' => 'Apprenez les techniques de respiration essentielles pour améliorer votre performance de Poetry Slam. De la respiration diaphragmatique au contrôle du rythme.',
                    'de' => 'Lerne die wesentlichen Atemtechniken, um deine Poetry Slam Performance zu verbessern. Von der Zwerchfellatmung bis zur Rhythmuskontrolle.',
                    'pt' => 'Aprenda as técnicas de respiração essenciais para melhorar sua performance de Poetry Slam. Da respiração diafragmática ao controle do ritmo.'
                ],
                'status' => 'published',
                'featured' => false,
                'views_count' => 89,
                'likes_count' => 15,
                'comments_count' => 4,
                'published_at' => Carbon::now()->subDays(3)
            ],
            [
                'title' => [
                    'it' => 'Storia del Poetry Slam: Dalle Origini ai Giorni Nostri',
                    'en' => 'History of Poetry Slam: From Origins to Present Day',
                    'es' => 'Historia del Poetry Slam: Desde los Orígenes hasta Hoy',
                    'fr' => 'Histoire du Poetry Slam: Des Origines à Nos Jours',
                    'de' => 'Geschichte des Poetry Slam: Von den Ursprüngen bis Heute',
                    'pt' => 'História do Poetry Slam: Das Origens aos Dias de Hoje'
                ],
                'content' => [
                    'it' => '<h2>Le Origini</h2><p>Il Poetry Slam nacque a Chicago nel 1984, ideato da Marc Smith. Era un modo per rendere la poesia più accessibile e coinvolgente per il pubblico.</p><h3>L\'Evoluzione</h3><p>Negli anni \'90, il movimento si diffuse rapidamente in tutto il mondo, creando una comunità globale di poeti slam.</p><h3>Oggi</h3><p>Il Poetry Slam è riconosciuto come una forma d\'arte legittima, con competizioni internazionali e una crescente popolarità.</p>',
                    'en' => '<h2>The Origins</h2><p>Poetry Slam was born in Chicago in 1984, conceived by Marc Smith. It was a way to make poetry more accessible and engaging for the public.</p><h3>The Evolution</h3><p>In the 1990s, the movement spread rapidly around the world, creating a global community of slam poets.</p><h3>Today</h3><p>Poetry Slam is recognized as a legitimate art form, with international competitions and growing popularity.</p>',
                    'es' => '<h2>Los Orígenes</h2><p>El Poetry Slam nació en Chicago en 1984, ideado por Marc Smith. Era una forma de hacer la poesía más accesible y envolvente para el público.</p><h3>La Evolución</h3><p>En los años 90, el movimiento se extendió rápidamente por todo el mundo, creando una comunidad global de poetas slam.</p><h3>Hoy</h3><p>El Poetry Slam es reconocido como una forma de arte legítima, con competencias internacionales y creciente popularidad.</p>',
                    'fr' => '<h2>Les Origines</h2><p>Le Poetry Slam est né à Chicago en 1984, conçu par Marc Smith. C\'était un moyen de rendre la poésie plus accessible et engageante pour le public.</p><h3>L\'Évolution</h3><p>Dans les années 1990, le mouvement s\'est rapidement répandu dans le monde entier, créant une communauté mondiale de poètes slam.</p><h3>Aujourd\'hui</h3><p>Le Poetry Slam est reconnu comme une forme d\'art légitime, avec des compétitions internationales et une popularité croissante.</p>',
                    'de' => '<h2>Die Ursprünge</h2><p>Poetry Slam wurde 1984 in Chicago geboren, konzipiert von Marc Smith. Es war ein Weg, Poesie für die Öffentlichkeit zugänglicher und fesselnder zu machen.</p><h3>Die Entwicklung</h3><p>In den 1990er Jahren verbreitete sich die Bewegung rasch auf der ganzen Welt und schuf eine globale Gemeinschaft von Slam Poeten.</p><h3>Heute</h3><p>Poetry Slam wird als legitime Kunstform anerkannt, mit internationalen Wettbewerben und wachsender Popularität.</p>',
                    'pt' => '<h2>As Origens</h2><p>O Poetry Slam nasceu em Chicago em 1984, concebido por Marc Smith. Era uma forma de tornar a poesia mais acessível e envolvente para o público.</p><h3>A Evolução</h3><p>Nos anos 90, o movimento se espalhou rapidamente pelo mundo, criando uma comunidade global de poetas slam.</p><h3>Hoje</h3><p>O Poetry Slam é reconhecido como uma forma de arte legítima, com competições internacionais e crescente popularidade.</p>'
                ],
                'excerpt' => [
                    'it' => 'Scopri la storia affascinante del Poetry Slam, dalle sue origini a Chicago nel 1984 fino alla sua diffusione globale.',
                    'en' => 'Discover the fascinating history of Poetry Slam, from its origins in Chicago in 1984 to its global spread.',
                    'es' => 'Descubre la fascinante historia del Poetry Slam, desde sus orígenes en Chicago en 1984 hasta su difusión global.',
                    'fr' => 'Découvrez l\'histoire fascinante du Poetry Slam, de ses origines à Chicago en 1984 à sa diffusion mondiale.',
                    'de' => 'Entdecke die faszinierende Geschichte des Poetry Slam, von seinen Ursprüngen in Chicago 1984 bis zu seiner globalen Verbreitung.',
                    'pt' => 'Descubra a história fascinante do Poetry Slam, desde suas origens em Chicago em 1984 até sua difusão global.'
                ],
                'status' => 'published',
                'featured' => false,
                'views_count' => 234,
                'likes_count' => 31,
                'comments_count' => 12,
                'published_at' => Carbon::now()->subDays(7)
            ],
            [
                'title' => [
                    'it' => 'Come Scrivere Testi Potenti per il Poetry Slam',
                    'en' => 'How to Write Powerful Texts for Poetry Slam',
                    'es' => 'Cómo Escribir Textos Poderosos para Poetry Slam',
                    'fr' => 'Comment Écrire des Textes Puissants pour le Poetry Slam',
                    'de' => 'Wie man kraftvolle Texte für Poetry Slam schreibt',
                    'pt' => 'Como Escrever Textos Poderosos para Poetry Slam'
                ],
                'content' => [
                    'it' => '<h2>La Scrittura Creativa</h2><p>Scrivere per il Poetry Slam richiede una combinazione di tecnica poetica e capacità performativa.</p><h3>Elementi Chiave</h3><ul><li><strong>Immagini vivide:</strong> Crea scene che il pubblico possa visualizzare</li><li><strong>Emozioni autentiche:</strong> Scrivi di ciò che conosci e senti profondamente</li><li><strong>Ritmo e musicalità:</strong> Usa l\'allitterazione e l\'assonanza</li></ul>',
                    'en' => '<h2>Creative Writing</h2><p>Writing for Poetry Slam requires a combination of poetic technique and performance ability.</p><h3>Key Elements</h3><ul><li><strong>Vivid imagery:</strong> Create scenes that the audience can visualize</li><li><strong>Authentic emotions:</strong> Write about what you know and feel deeply</li><li><strong>Rhythm and musicality:</strong> Use alliteration and assonance</li></ul>',
                    'es' => '<h2>Escritura Creativa</h2><p>Escribir para Poetry Slam requiere una combinación de técnica poética y capacidad performativa.</p><h3>Elementos Clave</h3><ul><li><strong>Imágenes vívidas:</strong> Crea escenas que la audiencia pueda visualizar</li><li><strong>Emociones auténticas:</strong> Escribe sobre lo que conoces y sientes profundamente</li><li><strong>Ritmo y musicalidad:</strong> Usa la aliteración y la asonancia</li></ul>',
                    'fr' => '<h2>L\'Écriture Créative</h2><p>Écrire pour le Poetry Slam nécessite une combinaison de technique poétique et de capacité performative.</p><h3>Éléments Clés</h3><ul><li><strong>Imagery vivante:</strong> Créez des scènes que le public puisse visualiser</li><li><strong>Émotions authentiques:</strong> Écrivez sur ce que vous connaissez et ressentez profondément</li><li><strong>Rythme et musicalité:</strong> Utilisez l\'allitération et l\'assonance</li></ul>',
                    'de' => '<h2>Kreatives Schreiben</h2><p>Für Poetry Slam zu schreiben erfordert eine Kombination aus poetischer Technik und Performance-Fähigkeit.</p><h3>Schlüsselelemente</h3><ul><li><strong>Lebendige Bilder:</strong> Erstelle Szenen, die das Publikum visualisieren kann</li><li><strong>Authentische Emotionen:</strong> Schreibe über das, was du kennst und tief fühlst</li><li><strong>Rhythmus und Musikalität:</strong> Verwende Alliteration und Assonanz</li></ul>',
                    'pt' => '<h2>Escrita Criativa</h2><p>Escrever para Poetry Slam requer uma combinação de técnica poética e capacidade performativa.</p><h3>Elementos Chave</h3><ul><li><strong>Imagens vívidas:</strong> Crie cenas que a audiência possa visualizar</li><li><strong>Emoções autênticas:</strong> Escreva sobre o que você conhece e sente profundamente</li><li><strong>Ritmo e musicalidade:</strong> Use aliteração e assonância</li></ul>'
                ],
                'excerpt' => [
                    'it' => 'Impara le tecniche per scrivere testi potenti e coinvolgenti per il Poetry Slam. Dalla scrittura creativa alla performance.',
                    'en' => 'Learn techniques for writing powerful and engaging texts for Poetry Slam. From creative writing to performance.',
                    'es' => 'Aprende técnicas para escribir textos poderosos y envolventes para Poetry Slam. Desde la escritura creativa hasta la performance.',
                    'fr' => 'Apprenez les techniques pour écrire des textes puissants et engageants pour le Poetry Slam. De l\'écriture créative à la performance.',
                    'de' => 'Lerne Techniken, um kraftvolle und fesselnde Texte für Poetry Slam zu schreiben. Vom kreativen Schreiben bis zur Performance.',
                    'pt' => 'Aprenda técnicas para escrever textos poderosos e envolventes para Poetry Slam. Da escrita criativa à performance.'
                ],
                'status' => 'draft',
                'featured' => false,
                'views_count' => 0,
                'likes_count' => 0,
                'comments_count' => 0,
                'published_at' => null
            ],
            [
                'title' => [
                    'it' => 'Eventi Poetry Slam in Italia: Guida Completa',
                    'en' => 'Poetry Slam Events in Italy: Complete Guide',
                    'es' => 'Eventos Poetry Slam en Italia: Guía Completa',
                    'fr' => 'Événements Poetry Slam en Italie: Guide Complet',
                    'de' => 'Poetry Slam Events in Italien: Vollständiger Leitfaden',
                    'pt' => 'Eventos Poetry Slam na Itália: Guia Completo'
                ],
                'content' => [
                    'it' => '<h2>Eventi Principali</h2><p>L\'Italia ospita numerosi eventi di Poetry Slam durante tutto l\'anno.</p><h3>Festival e Competizioni</h3><ul><li><strong>Roma:</strong> Festival Internazionale di Poesia</li><li><strong>Milano:</strong> Slam Poetry Contest</li><li><strong>Firenze:</strong> Poeti in Piazza</li></ul><h3>Come Partecipare</h3><p>La maggior parte degli eventi è aperta a tutti. Controlla i siti web per le iscrizioni.</p>',
                    'en' => '<h2>Main Events</h2><p>Italy hosts numerous Poetry Slam events throughout the year.</p><h3>Festivals and Competitions</h3><ul><li><strong>Rome:</strong> International Poetry Festival</li><li><strong>Milan:</strong> Slam Poetry Contest</li><li><strong>Florence:</strong> Poets in the Square</li></ul><h3>How to Participate</h3><p>Most events are open to everyone. Check websites for registrations.</p>',
                    'es' => '<h2>Eventos Principales</h2><p>Italia alberga numerosos eventos de Poetry Slam durante todo el año.</p><h3>Festivales y Competencias</h3><ul><li><strong>Roma:</strong> Festival Internacional de Poesía</li><li><strong>Milán:</strong> Concurso de Slam Poetry</li><li><strong>Florencia:</strong> Poetas en la Plaza</li></ul><h3>Cómo Participar</h3><p>La mayoría de eventos están abiertos a todos. Revisa los sitios web para las inscripciones.</p>',
                    'fr' => '<h2>Événements Principaux</h2><p>L\'Italie accueille de nombreux événements de Poetry Slam tout au long de l\'année.</p><h3>Festivals et Compétitions</h3><ul><li><strong>Rome:</strong> Festival International de Poésie</li><li><strong>Milan:</strong> Concours de Slam Poetry</li><li><strong>Florence:</strong> Poètes sur la Place</li></ul><h3>Comment Participer</h3><p>La plupart des événements sont ouverts à tous. Vérifiez les sites web pour les inscriptions.</p>',
                    'de' => '<h2>Hauptveranstaltungen</h2><p>Italien veranstaltet das ganze Jahr über zahlreiche Poetry Slam Events.</p><h3>Festivals und Wettbewerbe</h3><ul><li><strong>Rom:</strong> Internationales Poesiefestival</li><li><strong>Mailand:</strong> Slam Poetry Wettbewerb</li><li><strong>Florenz:</strong> Dichter auf dem Platz</li></ul><h3>Wie man Teilnimmt</h3><p>Die meisten Veranstaltungen sind für alle offen. Überprüfe die Websites für Anmeldungen.</p>',
                    'pt' => '<h2>Eventos Principais</h2><p>A Itália sedia numerosos eventos de Poetry Slam durante todo o ano.</p><h3>Festivais e Competições</h3><ul><li><strong>Roma:</strong> Festival Internacional de Poesia</li><li><strong>Milão:</strong> Concurso de Slam Poetry</li><li><strong>Florença:</strong> Poetas na Praça</li></ul><h3>Como Participar</h3><p>A maioria dos eventos está aberta a todos. Verifique os sites para inscrições.</p>'
                ],
                'excerpt' => [
                    'it' => 'Scopri tutti gli eventi di Poetry Slam in Italia. Festival, competizioni e opportunità per esibirsi e competere.',
                    'en' => 'Discover all Poetry Slam events in Italy. Festivals, competitions and opportunities to perform and compete.',
                    'es' => 'Descubre todos los eventos de Poetry Slam en Italia. Festivales, competencias y oportunidades para actuar y competir.',
                    'fr' => 'Découvrez tous les événements de Poetry Slam en Italie. Festivals, compétitions et opportunités pour performer et concourir.',
                    'de' => 'Entdecke alle Poetry Slam Events in Italien. Festivals, Wettbewerbe und Möglichkeiten zum Auftreten und Mitmachen.',
                    'pt' => 'Descubra todos os eventos de Poetry Slam na Itália. Festivais, competições e oportunidades para se apresentar e competir.'
                ],
                'status' => 'published',
                'featured' => true,
                'views_count' => 312,
                'likes_count' => 45,
                'comments_count' => 18,
                'published_at' => Carbon::now()->subDays(10)
            ]
        ];

        foreach ($articles as $articleData) {
            $user = $users->random();
            $category = $categories->random();
            $selectedTags = $tags->random(rand(2, 4));

            $article = Article::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => $articleData['title'],
                'content' => $articleData['content'],
                'excerpt' => $articleData['excerpt'],
                'status' => $articleData['status'],
                'featured' => $articleData['featured'],
                'views_count' => $articleData['views_count'],
                'likes_count' => $articleData['likes_count'],
                'comments_count' => $articleData['comments_count'],
                'slug' => Str::slug($articleData['title']['it']),
                'meta_title' => $articleData['title'],
                'meta_description' => $articleData['excerpt'],
                'published_at' => $articleData['published_at'],
                'moderation_status' => 'approved',
                'is_public' => true
            ]);

            // Associa i tag
            $article->tags()->attach($selectedTags->pluck('id'));

            $this->command->info("Articolo creato: {$articleData['title']['it']}");
        }

        $this->command->info('Seeder completato! Creati ' . count($articles) . ' articoli finti.');
    }
}
