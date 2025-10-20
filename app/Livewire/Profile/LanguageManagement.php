<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserLanguage;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class LanguageManagement extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editingLanguage = null;
    
    // Form fields
    public $language_name = '';
    public $language_code = '';
    public $type = 'native';
    public $level = null;
    
    protected $rules = [
        'language_name' => 'required|string|max:255',
        'language_code' => 'required|string|max:5',
        'type' => 'required|in:native,spoken,written',
        'level' => 'nullable|in:excellent,good,poor',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->language_name = '';
        $this->language_code = '';
        $this->type = 'native';
        $this->level = null;
        $this->editingLanguage = null;
        $this->showForm = false;
    }

    public function showAddForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editLanguage(UserLanguage $language)
    {
        if ($language->user_id !== Auth::id()) {
            abort(403);
        }

        $this->editingLanguage = $language;
        $this->language_name = $language->language_name;
        $this->language_code = $language->language_code;
        $this->type = $language->type;
        $this->level = $language->level;
        $this->showForm = true;
    }

    public function save()
    {
        // Se è madrelingua, il livello deve essere null
        if ($this->type === 'native') {
            $this->level = null;
        } else {
            // Se è parlato o scritto, il livello è obbligatorio
            $this->rules['level'] = 'required|in:excellent,good,poor';
        }

        $this->validate();

        // Verifica che non esista già questa combinazione
        $query = UserLanguage::where('user_id', Auth::id())
            ->where('language_code', $this->language_code)
            ->where('type', $this->type);

        if ($this->editingLanguage) {
            $query->where('id', '!=', $this->editingLanguage->id);
        }

        $existing = $query->first();

        if ($existing) {
            $this->addError('language', __('languages.already_exists'));
            return;
        }

        $data = [
            'user_id' => Auth::id(),
            'language_name' => $this->language_name,
            'language_code' => $this->language_code,
            'type' => $this->type,
            'level' => $this->level,
        ];

        if ($this->editingLanguage) {
            $this->editingLanguage->update($data);
            session()->flash('success', __('languages.updated_successfully'));
        } else {
            UserLanguage::create($data);
            session()->flash('success', __('languages.added_successfully'));
        }

        $this->resetForm();
    }

    public function deleteLanguage(UserLanguage $language)
    {
        if ($language->user_id !== Auth::id()) {
            abort(403);
        }

        $language->delete();
        session()->flash('success', __('languages.deleted_successfully'));
    }

    public function getLanguagesProperty()
    {
        return Auth::user()->languages()->orderBy('language_name')->get();
    }

    public function getWorldLanguagesProperty()
    {
        return \App\Providers\LanguageServiceProvider::getAllWorldLanguages();
    }

    public function render()
    {
        return view('livewire.profile.language-management', [
            'languages' => $this->languages,
            'worldLanguages' => $this->worldLanguages,
        ]);
    }
}

