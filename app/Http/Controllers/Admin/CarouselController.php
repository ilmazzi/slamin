<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function __construct()
    {
        // Middleware già applicato nelle rotte
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carousels = Carousel::orderBy('order', 'asc')->get();
        return view('admin.carousels.index', compact('carousels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.carousels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,avi,mov,mkv,webm,flv|max:10240',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        try {
            // Debug: salva informazioni sul file
            file_put_contents(storage_path('carousel_debug.txt'), '=== CAROUSEL UPLOAD ===' . "\n", FILE_APPEND);
            file_put_contents(storage_path('carousel_debug.txt'), 'File received: ' . ($request->hasFile('image') ? 'YES' : 'NO') . "\n", FILE_APPEND);

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                file_put_contents(storage_path('carousel_debug.txt'), 'Image name: ' . $imageFile->getClientOriginalName() . "\n", FILE_APPEND);
                file_put_contents(storage_path('carousel_debug.txt'), 'Image size: ' . $imageFile->getSize() . "\n", FILE_APPEND);
                file_put_contents(storage_path('carousel_debug.txt'), 'Image mime: ' . $imageFile->getMimeType() . "\n", FILE_APPEND);
            }

            // Upload image
            $imagePath = $request->file('image')->store('carousel', 'public');
            file_put_contents(storage_path('carousel_debug.txt'), 'Image stored at: ' . $imagePath . "\n", FILE_APPEND);

            // Upload video if provided
            $videoPath = null;
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('carousel', 'public');
                file_put_contents(storage_path('carousel_debug.txt'), 'Video stored at: ' . $videoPath . "\n", FILE_APPEND);
            }

            $carouselData = [
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $imagePath,
                'video_path' => $videoPath,
                'link_url' => $request->link_url,
                'link_text' => $request->link_text,
                'order' => $request->order ?? 0,
                'is_active' => $request->boolean('is_active', true),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            file_put_contents(storage_path('carousel_debug.txt'), 'Carousel data: ' . json_encode($carouselData, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

            $carousel = Carousel::create($carouselData);

            file_put_contents(storage_path('carousel_debug.txt'), 'Carousel created with ID: ' . $carousel->id . "\n", FILE_APPEND);

            return redirect()->route('admin.carousels.index')
                ->with('success', 'Slide del carosello creata con successo!');

        } catch (\Exception $e) {
            file_put_contents(storage_path('carousel_debug.txt'), 'Error: ' . $e->getMessage() . "\n", FILE_APPEND);
            file_put_contents(storage_path('carousel_debug.txt'), 'Stack trace: ' . $e->getTraceAsString() . "\n", FILE_APPEND);

            return back()->withInput()
                ->with('error', 'Errore durante la creazione: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Carousel $carousel)
    {
        return view('admin.carousels.show', compact('carousel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carousel $carousel)
    {
        return view('admin.carousels.edit', compact('carousel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Carousel $carousel)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,avi,mov,mkv,webm,flv|max:10240',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'link_url' => $request->link_url,
                'link_text' => $request->link_text,
                'order' => $request->order ?? 0,
                'is_active' => $request->boolean('is_active', true),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            // Update image if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($carousel->image_path) {
                    Storage::disk('public')->delete($carousel->image_path);
                }
                $data['image_path'] = $request->file('image')->store('carousel', 'public');
            }

            // Update video if provided
            if ($request->hasFile('video')) {
                // Delete old video
                if ($carousel->video_path) {
                    Storage::disk('public')->delete($carousel->video_path);
                }
                $data['video_path'] = $request->file('video')->store('carousel', 'public');
            }

            $carousel->update($data);

            return redirect()->route('admin.carousels.index')
                ->with('success', 'Slide del carosello aggiornata con successo!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Errore durante l\'aggiornamento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Carousel $carousel)
    {
        try {
            // Delete files
            if ($carousel->image_path) {
                Storage::disk('public')->delete($carousel->image_path);
            }
            if ($carousel->video_path) {
                Storage::disk('public')->delete($carousel->video_path);
            }

            $carousel->delete();

            return redirect()->route('admin.carousels.index')
                ->with('success', 'Slide del carosello eliminata con successo!');

        } catch (\Exception $e) {
            return back()->with('error', 'Errore durante l\'eliminazione: ' . $e->getMessage());
        }
    }

    /**
     * Update order of carousel items
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:carousels,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            Carousel::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
