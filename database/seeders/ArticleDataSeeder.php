<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;

class ArticleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea categorie di articoli
        $categories = [
            [
                'name' => [
                    'it' => 'Poetry Slam',
                    'en' => 'Poetry Slam',
                    'es' => 'Poetry Slam',
                    'fr' => 'Poetry Slam',
                    'de' => 'Poetry Slam',
                    'pt' => 'Poetry Slam'
                ],
                'description' => [
                    'it' => 'Articoli su eventi, tecniche e storie di Poetry Slam',
                    'en' => 'Articles about Poetry Slam events, techniques and stories',
                    'es' => 'Artículos sobre eventos, técnicas e historias de Poetry Slam',
                    'fr' => 'Articles sur les événements, techniques et histoires de Poetry Slam',
                    'de' => 'Artikel über Poetry Slam Events, Techniken und Geschichten',
                    'pt' => 'Artigos sobre eventos, técnicas e histórias de Poetry Slam'
                ],
                'slug' => 'poetry-slam',
                'color' => '#007bff',
                'icon' => 'ph-microphone-stage',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => [
                    'it' => 'Tecniche di Scrittura',
                    'en' => 'Writing Techniques',
                    'es' => 'Técnicas de Escritura',
                    'fr' => 'Techniques d\'Écriture',
                    'de' => 'Schreibtechniken',
                    'pt' => 'Técnicas de Escrita'
                ],
                'description' => [
                    'it' => 'Consigli e tecniche per migliorare la scrittura poetica',
                    'en' => 'Tips and techniques to improve poetic writing',
                    'es' => 'Consejos y técnicas para mejorar la escritura poética',
                    'fr' => 'Conseils et techniques pour améliorer l\'écriture poétique',
                    'de' => 'Tipps und Techniken zur Verbesserung des poetischen Schreibens',
                    'pt' => 'Dicas e técnicas para melhorar a escrita poética'
                ],
                'slug' => 'writing-techniques',
                'color' => '#28a745',
                'icon' => 'ph-pen-nib',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => [
                    'it' => 'Eventi e Festival',
                    'en' => 'Events and Festivals',
                    'es' => 'Eventos y Festivales',
                    'fr' => 'Événements et Festivals',
                    'de' => 'Events und Festivals',
                    'pt' => 'Eventos e Festivais'
                ],
                'description' => [
                    'it' => 'Copertura di eventi, festival e manifestazioni culturali',
                    'en' => 'Coverage of events, festivals and cultural manifestations',
                    'es' => 'Cobertura de eventos, festivales y manifestaciones culturales',
                    'fr' => 'Couverture d\'événements, festivals et manifestations culturelles',
                    'de' => 'Berichterstattung über Events, Festivals und kulturelle Manifestationen',
                    'pt' => 'Cobertura de eventos, festivais e manifestações culturais'
                ],
                'slug' => 'events-festivals',
                'color' => '#ffc107',
                'icon' => 'ph-calendar',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => [
                    'it' => 'Interviste',
                    'en' => 'Interviews',
                    'es' => 'Entrevistas',
                    'fr' => 'Interviews',
                    'de' => 'Interviews',
                    'pt' => 'Entrevistas'
                ],
                'description' => [
                    'it' => 'Interviste a poeti, artisti e personalità del mondo slam',
                    'en' => 'Interviews with poets, artists and slam world personalities',
                    'es' => 'Entrevistas con poetas, artistas y personalidades del mundo slam',
                    'fr' => 'Interviews avec des poètes, artistes et personnalités du monde slam',
                    'de' => 'Interviews mit Dichtern, Künstlern und Persönlichkeiten der Slam-Szene',
                    'pt' => 'Entrevistas com poetas, artistas e personalidades do mundo slam'
                ],
                'slug' => 'interviews',
                'color' => '#dc3545',
                'icon' => 'ph-microphone',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => [
                    'it' => 'Recensioni',
                    'en' => 'Reviews',
                    'es' => 'Reseñas',
                    'fr' => 'Critiques',
                    'de' => 'Rezensionen',
                    'pt' => 'Resenhas'
                ],
                'description' => [
                    'it' => 'Recensioni di libri, performance e opere artistiche',
                    'en' => 'Reviews of books, performances and artistic works',
                    'es' => 'Reseñas de libros, performances y obras artísticas',
                    'fr' => 'Critiques de livres, performances et œuvres artistiques',
                    'de' => 'Rezensionen von Büchern, Performances und künstlerischen Werken',
                    'pt' => 'Resenhas de livros, performances e obras artísticas'
                ],
                'slug' => 'reviews',
                'color' => '#6f42c1',
                'icon' => 'ph-star',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($categories as $categoryData) {
            ArticleCategory::create($categoryData);
        }

        echo "✓ Categorie articoli create\n";

        // Crea tag di articoli
        $tags = [
            [
                'name' => [
                    'it' => 'Poesia',
                    'en' => 'Poetry',
                    'es' => 'Poesía',
                    'fr' => 'Poésie',
                    'de' => 'Poesie',
                    'pt' => 'Poesia'
                ],
                'slug' => 'poetry',
                'color' => '#007bff',
                'is_active' => true,
                'usage_count' => 0
            ],
            [
                'name' => [
                    'it' => 'Slam',
                    'en' => 'Slam',
                    'es' => 'Slam',
                    'fr' => 'Slam',
                    'de' => 'Slam',
                    'pt' => 'Slam'
                ],
                'slug' => 'slam',
                'color' => '#28a745',
                'is_active' => true,
                'usage_count' => 0
            ],
            [
                'name' => [
                    'it' => 'Performance',
                    'en' => 'Performance',
                    'es' => 'Performance',
                    'fr' => 'Performance',
                    'de' => 'Performance',
                    'pt' => 'Performance'
                ],
                'slug' => 'performance',
                'color' => '#ffc107',
                'is_active' => true,
                'usage_count' => 0
            ],
            [
                'name' => [
                    'it' => 'Creatività',
                    'en' => 'Creativity',
                    'es' => 'Creatividad',
                    'fr' => 'Créativité',
                    'de' => 'Kreativität',
                    'pt' => 'Criatividade'
                ],
                'slug' => 'creativity',
                'color' => '#dc3545',
                'is_active' => true,
                'usage_count' => 0
            ],
            [
                'name' => [
                    'it' => 'Arte',
                    'en' => 'Art',
                    'es' => 'Arte',
                    'fr' => 'Art',
                    'de' => 'Kunst',
                    'pt' => 'Arte'
                ],
                'slug' => 'art',
                'color' => '#6f42c1',
                'is_active' => true,
                'usage_count' => 0
            ],
            [
                'name' => [
                    'it' => 'Cultura',
                    'en' => 'Culture',
                    'es' => 'Cultura',
                    'fr' => 'Culture',
                    'de' => 'Kultur',
                    'pt' => 'Cultura'
                ],
                'slug' => 'culture',
                'color' => '#fd7e14',
                'is_active' => true,
                'usage_count' => 0
            ],
            [
                'name' => [
                    'it' => 'Letteratura',
                    'en' => 'Literature',
                    'es' => 'Literatura',
                    'fr' => 'Littérature',
                    'de' => 'Literatur',
                    'pt' => 'Literatura'
                ],
                'slug' => 'literature',
                'color' => '#20c997',
                'is_active' => true,
                'usage_count' => 0
            ],
            [
                'name' => [
                    'it' => 'Workshop',
                    'en' => 'Workshop',
                    'es' => 'Taller',
                    'fr' => 'Atelier',
                    'de' => 'Workshop',
                    'pt' => 'Oficina'
                ],
                'slug' => 'workshop',
                'color' => '#17a2b8',
                'is_active' => true,
                'usage_count' => 0
            ]
        ];

        foreach ($tags as $tagData) {
            ArticleTag::create($tagData);
        }

        echo "✓ Tag articoli creati\n";
        echo "\n=== SEEDING COMPLETATO ===\n";
        echo "Categorie create: " . count($categories) . "\n";
        echo "Tag creati: " . count($tags) . "\n";
    }
}
