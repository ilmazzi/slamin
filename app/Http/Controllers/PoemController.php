<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use App\Models\PoemComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PoemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'search']);
        $this->middleware('can:create,App\Models\Poem')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Poem::with(['user', 'likes', 'comments'])
                    ->published();

        // Filtri
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('language')) {
            $query->byLanguage($request->language);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Ordinamento
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'popular':
                $query->popular();
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'alphabetical':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->recent();
        }

        $poems = $query->paginate(12);

        return view('poems.index', compact('poems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = config('poems.categories', []);
        $poemTypes = config('poems.poem_types', []);
        $languages = config('app.languages', ['it' => 'Italiano', 'en' => 'English']);

        return view('poems.create', compact('categories', 'poemTypes', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|in:' . implode(',', array_keys(config('poems.categories', []))),
            'poem_type' => 'required|string|in:' . implode(',', array_keys(config('poems.poem_types', []))),
            'language' => 'required|string|max:10',
            'tags' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_public' => 'boolean',
            'is_draft' => 'boolean',
            'translation_available' => 'boolean',
            'translation_price' => 'nullable|numeric|min:0|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        
        // Gestione thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('poems/thumbnails', 'public');
            $data['thumbnail_path'] = $thumbnailPath;
        }

        // Gestione tags
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
            $data['tags'] = array_filter($tags);
        }

        // Impostazioni di pubblicazione
        if ($data['is_draft'] ?? false) {
            $data['is_draft'] = true;
            $data['is_public'] = false;
            $data['draft_saved_at'] = now();
        } else {
            $data['is_draft'] = false;
            $data['published_at'] = now();
        }

        // Slug unico
        $data['slug'] = $this->generateUniqueSlug($data['title']);

        // Conteggio parole
        $data['word_count'] = str_word_count(strip_tags($data['content']));

        $poem = Auth::user()->poems()->create($data);

        return redirect()->route('poems.show', $poem)
            ->with('success', __('poems.messages.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Poem $poem)
    {
        // Incrementa le visualizzazioni
        $poem->incrementViewCount();

        // Carica relazioni
        $poem->load(['user', 'comments.approved', 'likes', 'bookmarks', 'translations']);

        // Poesie correlate
        $relatedPoems = Poem::published()
            ->where('id', '!=', $poem->id)
            ->where(function($query) use ($poem) {
                $query->where('category', $poem->category)
                      ->orWhere('language', $poem->language)
                      ->orWhereJsonContains('tags', $poem->tags);
            })
            ->limit(4)
            ->get();

        return view('poems.show', compact('poem', 'relatedPoems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poem $poem)
    {
        if (!$poem->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        $categories = config('poems.categories', []);
        $poemTypes = config('poems.poem_types', []);
        $languages = config('app.languages', ['it' => 'Italiano', 'en' => 'English']);

        return view('poems.edit', compact('poem', 'categories', 'poemTypes', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poem $poem)
    {
        if (!$poem->canBeEditedBy(Auth::user())) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|in:' . implode(',', array_keys(config('poems.categories', []))),
            'poem_type' => 'required|string|in:' . implode(',', array_keys(config('poems.poem_types', []))),
            'language' => 'required|string|max:10',
            'tags' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_public' => 'boolean',
            'is_draft' => 'boolean',
            'translation_available' => 'boolean',
            'translation_price' => 'nullable|numeric|min:0|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Gestione thumbnail
        if ($request->hasFile('thumbnail')) {
            // Elimina il vecchio thumbnail
            if ($poem->thumbnail_path) {
                Storage::disk('public')->delete($poem->thumbnail_path);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('poems/thumbnails', 'public');
            $data['thumbnail_path'] = $thumbnailPath;
        }

        // Gestione tags
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
            $data['tags'] = array_filter($tags);
        }

        // Impostazioni di pubblicazione
        if ($data['is_draft'] ?? false) {
            $data['is_draft'] = true;
            $data['is_public'] = false;
            $data['draft_saved_at'] = now();
        } else {
            $data['is_draft'] = false;
            if (!$poem->published_at) {
                $data['published_at'] = now();
            }
        }

        // Slug unico se il titolo è cambiato
        if ($poem->title !== $data['title']) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $poem->id);
        }

        // Conteggio parole
        $data['word_count'] = str_word_count(strip_tags($data['content']));

        $poem->update($data);

        return redirect()->route('poems.show', $poem)
            ->with('success', __('poems.messages.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poem $poem)
    {
        if (!$poem->canBeDeletedBy(Auth::user())) {
            abort(403);
        }

        // Elimina il thumbnail
        if ($poem->thumbnail_path) {
            Storage::disk('public')->delete($poem->thumbnail_path);
        }

        $poem->delete();

        return redirect()->route('poems.index')
            ->with('success', __('poems.messages.deleted'));
    }

    /**
     * Mostra le poesie dell'utente
     */
    public function myPoems()
    {
        $poems = Auth::user()->poems()
            ->with(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('poems.my-poems', compact('poems'));
    }

    /**
     * Mostra le bozze dell'utente
     */
    public function drafts()
    {
        $drafts = Auth::user()->poems()
            ->drafts()
            ->orderBy('draft_saved_at', 'desc')
            ->paginate(12);

        return view('poems.drafts', compact('drafts'));
    }

    /**
     * Cerca poesie
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category');
        $language = $request->get('language');
        $sort = $request->get('sort', 'recent');

        $poems = Poem::with(['user', 'likes', 'comments'])
            ->published()
            ->when($query, function($q) use ($query) {
                $q->where(function($subQ) use ($query) {
                    $subQ->where('title', 'like', "%{$query}%")
                         ->orWhere('content', 'like', "%{$query}%")
                         ->orWhere('description', 'like', "%{$query}%")
                         ->orWhereJsonContains('tags', $query)
                         ->orWhereHas('user', function($userQ) use ($query) {
                             $userQ->where('name', 'like', "%{$query}%");
                         });
                });
            })
            ->when($category, function($q) use ($category) {
                $q->byCategory($category);
            })
            ->when($language, function($q) use ($language) {
                $q->byLanguage($language);
            })
            ->when($sort === 'popular', function($q) {
                $q->popular();
            })
            ->when($sort === 'oldest', function($q) {
                $q->orderBy('published_at', 'asc');
            })
            ->when($sort === 'alphabetical', function($q) {
                $q->orderBy('title', 'asc');
            })
            ->when($sort === 'recent', function($q) {
                $q->recent();
            })
            ->paginate(12);

        return view('poems.search', compact('poems', 'query', 'category', 'language', 'sort'));
    }

    /**
     * Genera uno slug unico
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (Poem::where('slug', $slug)
                  ->when($excludeId, function($query) use ($excludeId) {
                      $query->where('id', '!=', $excludeId);
                  })
                  ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
