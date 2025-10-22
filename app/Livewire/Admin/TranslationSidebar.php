<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class TranslationSidebar extends Component
{
    public $isOpen = false;
    public $translations = [];
    public $currentLocale;
    public $editingKey = null;
    public $editingValue = '';
    public $searchTerm = '';
    
    protected $listeners = ['toggleSidebar', 'refreshTranslations'];
    
    public function mount()
    {
        $this->currentLocale = app()->getLocale();
        $this->loadPageTranslations();
    }
    
    public function toggleSidebar()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->loadPageTranslations();
        }
    }
    
    public function loadPageTranslations()
    {
        // Carica tutte le traduzioni della lingua corrente
        $langPath = lang_path($this->currentLocale);
        $translations = [];
        
        if (File::exists($langPath)) {
            $files = File::allFiles($langPath);
            
            foreach ($files as $file) {
                $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $fileTranslations = include $file->getPathname();
                
                if (is_array($fileTranslations)) {
                    foreach ($fileTranslations as $key => $value) {
                        if (is_string($value)) {
                            $fullKey = $fileName . '.' . $key;
                            $translations[$fullKey] = [
                                'key' => $fullKey,
                                'value' => $value,
                                'file' => $fileName,
                                'subkey' => $key
                            ];
                        }
                    }
                }
            }
        }
        
        // Ordina per chiave
        ksort($translations);
        $this->translations = $translations;
    }
    
    public function edit($key)
    {
        $this->editingKey = $key;
        $this->editingValue = $this->translations[$key]['value'] ?? '';
    }
    
    public function cancelEdit()
    {
        $this->editingKey = null;
        $this->editingValue = '';
    }
    
    public function save()
    {
        if (!$this->editingKey) {
            return;
        }
        
        $translation = $this->translations[$this->editingKey] ?? null;
        if (!$translation) {
            return;
        }
        
        try {
            // Leggi il file corrente
            $langPath = lang_path($this->currentLocale . '/' . $translation['file'] . '.php');
            
            if (File::exists($langPath)) {
                $content = include $langPath;
                
                // Aggiorna il valore
                $content[$translation['subkey']] = $this->editingValue;
                
                // Scrivi il file
                $export = var_export($content, true);
                $export = str_replace(['array (', ')'], ['[', ']'], $export);
                $fileContent = "<?php\n\nreturn " . $export . ";\n";
                
                File::put($langPath, $fileContent);
                
                // Pulisci cache
                Cache::forget('translations');
                Cache::forget('translations.' . $this->currentLocale);
                
                // Aggiorna locale
                $this->translations[$this->editingKey]['value'] = $this->editingValue;
                
                $this->dispatch('translationUpdated', [
                    'key' => $this->editingKey,
                    'value' => $this->editingValue
                ]);
                
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Traduzione salvata!'
                ]);
                
                Log::info('Translation updated', [
                    'key' => $this->editingKey,
                    'locale' => $this->currentLocale,
                    'old_value' => $translation['value'],
                    'new_value' => $this->editingValue
                ]);
            }
            
            $this->cancelEdit();
            
        } catch (\Exception $e) {
            Log::error('Error saving translation', [
                'key' => $this->editingKey,
                'error' => $e->getMessage()
            ]);
            
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Errore nel salvataggio: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getFilteredTranslationsProperty()
    {
        if (empty($this->searchTerm)) {
            return $this->translations;
        }
        
        return array_filter($this->translations, function($translation) {
            return stripos($translation['key'], $this->searchTerm) !== false ||
                   stripos($translation['value'], $this->searchTerm) !== false;
        });
    }
    
    public function render()
    {
        return view('livewire.admin.translation-sidebar', [
            'filteredTranslations' => $this->filteredTranslations
        ]);
    }
}
