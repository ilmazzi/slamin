<?php

namespace App\Livewire\Poems;

use App\Models\Poem;
use App\Models\PoemCategory;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class PoemCreate extends Component
{
    use WithFileUploads;

    public $title = '';
    public $content = '';
    public $category_id = '';
    public $language_id = '';
    public $is_draft = false;
    public $featured_image;
    public $tags = '';

    public $categories = [];
    public $languages = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string|min:10',
        'category_id' => 'required|exists:poem_categories,id',
        'language_id' => 'required|exists:languages,id',
        'featured_image' => 'nullable|image|max:2048',
        'tags' => 'nullable|string|max:500',
        'is_draft' => 'boolean'
    ];

    protected $messages = [
        'title.required' => 'Il titolo è obbligatorio.',
        'title.max' => 'Il titolo non può superare i 255 caratteri.',
        'content.required' => 'Il contenuto è obbligatorio.',
        'content.min' => 'Il contenuto deve essere di almeno 10 caratteri.',
        'category_id.required' => 'La categoria è obbligatoria.',
        'category_id.exists' => 'La categoria selezionata non è valida.',
        'language_id.required' => 'La lingua è obbligatoria.',
        'language_id.exists' => 'La lingua selezionata non è valida.',
        'featured_image.image' => 'L\'immagine deve essere un file immagine valido.',
        'featured_image.max' => 'L\'immagine non può superare i 2MB.',
        'tags.max' => 'I tag non possono superare i 500 caratteri.'
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->categories = PoemCategory::orderBy('name')->get();
        $this->languages = Language::orderBy('name')->get();
        
        // Set default values
        if ($this->languages->count() > 0) {
            $this->language_id = $this->languages->first()->id;
        }
    }

    public function saveDraft()
    {
        $this->is_draft = true;
        $this->save();
    }

    public function save()
    {
        $this->validate();

        try {
            $poem = Poem::create([
                'title' => $this->title,
                'content' => $this->content,
                'category_id' => $this->category_id,
                'language_id' => $this->language_id,
                'user_id' => Auth::id(),
                'is_draft' => $this->is_draft,
                'tags' => $this->tags
            ]);

            // Handle featured image upload
            if ($this->featured_image) {
                $path = $this->featured_image->store('poems/featured', 'public');
                $poem->update(['featured_image' => $path]);
            }

            // Reset form
            $this->reset(['title', 'content', 'category_id', 'tags', 'featured_image']);
            $this->is_draft = false;

            // Show success message
            session()->flash('success', $this->is_draft ? 
                'Bozza salvata con successo!' : 
                'Poesia pubblicata con successo!'
            );

            // Redirect to poem view
            return redirect()->route('poems.show', $poem);

        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante il salvataggio: ' . $e->getMessage());
        }
    }

    public function updatedContent($value)
    {
        // Auto-save draft every 30 seconds (optional)
        // This could be implemented with a debounced save
    }

    public function render()
    {
        return view('livewire.poems.poem-create')
            ->layout('components.layouts.master');
    }
}
