<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GigPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GigPositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = GigPosition::orderBy('sort_order', 'asc')->get();
        return view('admin.gig-positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gig-positions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:gig_positions,key',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        GigPosition::create($validated);

        return redirect()->route('admin.gig-positions.index')
            ->with('success', 'Posizione di ingaggio creata con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $position = GigPosition::findOrFail($id);
        return view('admin.gig-positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $position = GigPosition::findOrFail($id);
        return view('admin.gig-positions.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $position = GigPosition::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:gig_positions,key,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $position->update($validated);

        return redirect()->route('admin.gig-positions.index')
            ->with('success', 'Posizione di ingaggio aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = GigPosition::findOrFail($id);

        // Controlla se ci sono gigs che usano questa posizione
        if ($position->gigs()->count() > 0) {
            return redirect()->route('admin.gig-positions.index')
                ->with('error', 'Non è possibile eliminare questa posizione perché è utilizzata da alcuni ingaggi.');
        }

        $position->delete();

        return redirect()->route('admin.gig-positions.index')
            ->with('success', 'Posizione di ingaggio eliminata con successo!');
    }

    /**
     * Toggle lo stato attivo/inattivo
     */
    public function toggleStatus(string $id)
    {
        $position = GigPosition::findOrFail($id);
        $position->update(['is_active' => !$position->is_active]);

        return redirect()->route('admin.gig-positions.index')
            ->with('success', 'Stato della posizione aggiornato con successo!');
    }
}
