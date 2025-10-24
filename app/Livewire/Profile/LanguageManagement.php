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
    
    // Autocomplete
    public $searchLanguage = '';
    public $showLanguageDropdown = false;
    
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
        $this->searchLanguage = '';
        $this->showLanguageDropdown = false;
    }

    public function showAddForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }
    
    public function updatedSearchLanguage()
    {
        $this->showLanguageDropdown = strlen($this->searchLanguage) >= 2;
    }
    
    public function selectLanguage($name, $code)
    {
        $this->language_name = $name;
        $this->language_code = $code;
        $this->searchLanguage = $name;
        $this->showLanguageDropdown = false;
    }
    
    public function getFilteredLanguagesProperty()
    {
        if (strlen($this->searchLanguage) < 2) {
            return [];
        }
        
        $search = strtolower($this->searchLanguage);
        $languages = $this->getAllWorldLanguages();
        
        return array_filter($languages, function($lang) use ($search) {
            return str_contains(strtolower($lang['name']), $search) || 
                   str_contains(strtolower($lang['code']), $search);
        });
    }
    
    private function getAllWorldLanguages()
    {
        return [
            ['name' => 'Italiano', 'code' => 'it'],
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Español', 'code' => 'es'],
            ['name' => 'Français', 'code' => 'fr'],
            ['name' => 'Deutsch', 'code' => 'de'],
            ['name' => 'Português', 'code' => 'pt'],
            ['name' => 'Русский', 'code' => 'ru'],
            ['name' => '中文', 'code' => 'zh'],
            ['name' => '日本語', 'code' => 'ja'],
            ['name' => '한국어', 'code' => 'ko'],
            ['name' => 'العربية', 'code' => 'ar'],
            ['name' => 'हिन्दी', 'code' => 'hi'],
            ['name' => 'Nederlands', 'code' => 'nl'],
            ['name' => 'Polski', 'code' => 'pl'],
            ['name' => 'Türkçe', 'code' => 'tr'],
            ['name' => 'Svenska', 'code' => 'sv'],
            ['name' => 'Norsk', 'code' => 'no'],
            ['name' => 'Dansk', 'code' => 'da'],
            ['name' => 'Suomi', 'code' => 'fi'],
            ['name' => 'Ελληνικά', 'code' => 'el'],
            ['name' => 'Čeština', 'code' => 'cs'],
            ['name' => 'Magyar', 'code' => 'hu'],
            ['name' => 'Română', 'code' => 'ro'],
            ['name' => 'Български', 'code' => 'bg'],
            ['name' => 'Українська', 'code' => 'uk'],
            ['name' => 'עברית', 'code' => 'he'],
            ['name' => 'ไทย', 'code' => 'th'],
            ['name' => 'Tiếng Việt', 'code' => 'vi'],
            ['name' => 'Bahasa Indonesia', 'code' => 'id'],
            ['name' => 'Bahasa Melayu', 'code' => 'ms'],
        ];
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


