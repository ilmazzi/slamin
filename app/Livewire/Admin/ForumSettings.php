<?php

namespace App\Livewire\Admin;

use App\Models\ForumSetting;
use Livewire\Component;

class ForumSettings extends Component
{
    public $settings = [];
    public $editMode = [];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $dbSettings = ForumSetting::all();
        
        foreach ($dbSettings as $setting) {
            $this->settings[$setting->key] = [
                'value' => $setting->value,
                'description' => $setting->description,
                'type' => $setting->type,
            ];
            $this->editMode[$setting->key] = false;
        }
    }

    public function toggleEdit($key)
    {
        $this->editMode[$key] = !$this->editMode[$key];
    }

    public function saveSetting($key)
    {
        $value = $this->settings[$key]['value'];
        $type = $this->settings[$key]['type'];

        ForumSetting::set($key, $value, $type);

        $this->editMode[$key] = false;

        $this->dispatch('notify', [
            'message' => 'Configurazione aggiornata con successo!',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.forum-settings');
    }
}
