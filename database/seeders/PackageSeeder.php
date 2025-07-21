<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Gratuito',
                'slug' => 'free',
                'description' => 'Pacchetto gratuito con limiti base',
                'price' => 0.00,
                'currency' => 'EUR',
                'video_limit' => 3,
                'duration_days' => 0, // Illimitato
                'stripe_price_id' => null,
                'features' => [
                    'upload_videos' => true,
                    'basic_analytics' => true,
                    'community_support' => true,
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Poeta Base',
                'slug' => 'poet-basic',
                'description' => 'Perfetto per poeti che iniziano',
                'price' => 9.99,
                'currency' => 'EUR',
                'video_limit' => 10,
                'duration_days' => 30,
                'stripe_price_id' => null, // Da configurare con Stripe
                'features' => [
                    'upload_videos' => true,
                    'advanced_analytics' => true,
                    'priority_support' => true,
                    'custom_thumbnails' => true,
                    'video_quality_hd' => true,
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Poeta Pro',
                'slug' => 'poet-pro',
                'description' => 'Per poeti professionisti',
                'price' => 19.99,
                'currency' => 'EUR',
                'video_limit' => 25,
                'duration_days' => 30,
                'stripe_price_id' => null, // Da configurare con Stripe
                'features' => [
                    'upload_videos' => true,
                    'premium_analytics' => true,
                    'priority_support' => true,
                    'custom_thumbnails' => true,
                    'video_quality_4k' => true,
                    'advanced_editing' => true,
                    'collaboration_tools' => true,
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Poeta Elite',
                'slug' => 'poet-elite',
                'description' => 'Per poeti di livello avanzato',
                'price' => 39.99,
                'currency' => 'EUR',
                'video_limit' => 50,
                'duration_days' => 30,
                'stripe_price_id' => null, // Da configurare con Stripe
                'features' => [
                    'upload_videos' => true,
                    'enterprise_analytics' => true,
                    'dedicated_support' => true,
                    'custom_thumbnails' => true,
                    'video_quality_4k' => true,
                    'advanced_editing' => true,
                    'collaboration_tools' => true,
                    'live_streaming' => true,
                    'exclusive_events' => true,
                    'mentorship_program' => true,
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Organizzatore',
                'slug' => 'organizer',
                'description' => 'Per organizzatori di eventi',
                'price' => 29.99,
                'currency' => 'EUR',
                'video_limit' => 100,
                'duration_days' => 30,
                'stripe_price_id' => null, // Da configurare con Stripe
                'features' => [
                    'upload_videos' => true,
                    'event_management' => true,
                    'participant_management' => true,
                    'advanced_analytics' => true,
                    'priority_support' => true,
                    'custom_branding' => true,
                    'bulk_upload' => true,
                    'team_collaboration' => true,
                ],
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::updateOrCreate(
                ['slug' => $packageData['slug']],
                $packageData
            );
        }

        $this->command->info('✅ Pacchetti premium creati con successo!');
    }
}
