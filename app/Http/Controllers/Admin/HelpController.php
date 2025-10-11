<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'help');

        $helps = Help::ofType($type)
            ->ordered()
            ->paginate(15);

        return view('admin.help.index', compact('helps', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'help');
        return view('admin.help.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Help/FAQ Store - Request data:', $request->all());

        $validated = $request->validate([
            'type' => 'required|in:help,faq',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:0',
        ]);

        \Log::info('Help/FAQ Store - Validated data:', $validated);

        $help = Help::create([
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active') && $request->is_active === 'on'
        ]);

        \Log::info('Help/FAQ Store - Created:', ['id' => $help->id, 'title' => $help->title]);

        return redirect()
            ->route('admin.help.index', ['type' => $help->type])
            ->with('success', 'FAQ/Help creato con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Help $help)
    {
        return view('admin.help.show', compact('help'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Help $help)
    {
        return view('admin.help.edit', compact('help'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Help $help)
    {
        $request->validate([
            'type' => 'required|in:help,faq',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $help->update([
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()
            ->route('admin.help.index', ['type' => $help->type])
            ->with('success', __('admin.help.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Help $help)
    {
        $type = $help->type;
        $help->delete();

        return redirect()
            ->route('admin.help.index', ['type' => $type])
            ->with('success', __('admin.help.deleted_successfully'));
    }

    /**
     * Toggle active status
     */
    public function toggle(Help $help)
    {
        $help->update(['is_active' => !$help->is_active]);

        $status = $help->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.help.index', ['type' => $help->type])
            ->with('success', __('admin.help.' . $status . '_successfully'));
    }
}
