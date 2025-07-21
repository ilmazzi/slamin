<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use App\Models\Package;

class TestVideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Test upload video (simulato)
     */
    public function testUpload(Request $request)
    {
        $user = Auth::user();

        // Verifica se l'utente può caricare video
        if (!$user->canUploadMoreVideos()) {
            return redirect()->route('premium.index')
                ->with('error', 'Limite video raggiunto. Acquista un pacchetto premium per caricare più video.');
        }

        // Validazione
        $request->validate([
            'video' => 'required|file|mimes:mp4,avi,mov,mkv,webm,flv|max:10240', // 10MB per test
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'tags' => 'nullable|string|max:500',
        ]);

        try {
            // Simula upload su PeerTube
            $peerTubeResponse = [
                'id' => 'test_' . time(),
                'url' => 'https://video.slamin.it/videos/watch/' . 'test_' . time(),
                'embedUrl' => 'https://video.slamin.it/videos/embed/' . 'test_' . time(),
                'thumbnailPath' => '/static/thumbnails/test_thumbnail.jpg',
                'duration' => rand(60, 600), // 1-10 minuti
                'resolution' => '720p',
                'success' => true
            ];

            // Salva nel database
            $video = Video::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'is_public' => $request->boolean('is_public', true),
                'video_url' => $peerTubeResponse['url'],
                'thumbnail' => $peerTubeResponse['thumbnailPath'],
                // Campi PeerTube
                'peertube_id' => $peerTubeResponse['id'],
                'peertube_url' => $peerTubeResponse['url'],
                'peertube_embed_url' => $peerTubeResponse['embedUrl'],
                'peertube_thumbnail_url' => $peerTubeResponse['thumbnailPath'],
                'duration' => $peerTubeResponse['duration'],
                'resolution' => $peerTubeResponse['resolution'],
                'file_size' => $request->file('video')->getSize(),
                'moderation_status' => 'pending',
            ]);

            return redirect()->route('profile.videos')
                ->with('success', 'Video caricato con successo! (Test Mode - PeerTube non configurato)');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Errore durante l\'upload: ' . $e->getMessage());
        }
    }

    /**
     * Test acquisto pacchetto (simulato)
     */
    public function testPurchase(Request $request, Package $package)
    {
        $user = Auth::user();

        // Validazione
        $request->validate([
            'payment_method' => 'required|string',
            'terms_accepted' => 'required|accepted',
        ]);

        try {
            // Simula acquisto Stripe
            $subscription = \App\Models\UserSubscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'start_date' => now(),
                'end_date' => now()->addDays($package->duration_days),
                'status' => 'active',
                'stripe_subscription_id' => 'test_sub_' . time(),
                'stripe_customer_id' => 'test_cust_' . $user->id,
            ]);

            return redirect()->route('premium.success', $subscription)
                ->with('success', 'Abbonamento attivato con successo! (Test Mode - Stripe non configurato)');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Errore durante l\'acquisto: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard test
     */
    public function testDashboard()
    {
        $user = Auth::user();
        $videos = $user->videos()->latest()->get();
        $packages = Package::active()->ordered()->get();
        $currentSubscription = $user->activeSubscription;

        return view('test.dashboard', compact('user', 'videos', 'packages', 'currentSubscription'));
    }
}
