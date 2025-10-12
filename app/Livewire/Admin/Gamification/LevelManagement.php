<?php

namespace App\Livewire\Admin\Gamification;

use Livewire\Component;
use App\Models\GamificationLevel;

class LevelManagement extends Component
{
    public $levels;
    public $level;
    public $isEditing = false;
    public $showModal = false;
    
    // Form fields
    public $levelNumber;
    public $name;
    public $description;
    public $required_points;
    public $required_badges;
    public $order;

    protected $rules = [
        'levelNumber' => 'required|integer|min:1',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'required_points' => 'required|integer|min:0',
        'required_badges' => 'required|integer|min:0',
        'order' => 'required|integer|min:0',
    ];

    public function mount()
    {
        $this->loadLevels();
    }

    public function loadLevels()
    {
        $this->levels = GamificationLevel::ordered()->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($levelId)
    {
        $level = GamificationLevel::findOrFail($levelId);
        
        $this->level = $level;
        $this->levelNumber = $level->level;
        $this->name = $level->name;
        $this->description = $level->description;
        $this->required_points = $level->required_points;
        $this->required_badges = $level->required_badges;
        $this->order = $level->order;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'level' => $this->levelNumber,
            'name' => $this->name,
            'description' => $this->description,
            'required_points' => $this->required_points,
            'required_badges' => $this->required_badges,
            'order' => $this->order,
        ];

        if ($this->isEditing) {
            $this->level->update($data);
            $this->dispatch('swal:success', ['title' => 'Successo!', 'text' => 'Livello aggiornato con successo!']);
        } else {
            GamificationLevel::create($data);
            $this->dispatch('swal:success', ['title' => 'Successo!', 'text' => 'Livello creato con successo!']);
        }

        $this->loadLevels();
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($levelId)
    {
        $level = GamificationLevel::findOrFail($levelId);
        $level->delete();
        
        $this->dispatch('swal:success', ['title' => 'Successo!', 'text' => 'Livello eliminato con successo!']);
        $this->loadLevels();
    }

    private function resetForm()
    {
        $this->reset(['levelNumber', 'name', 'description', 'required_points', 'required_badges', 'order']);
        $this->levelNumber = ($this->levels->max('level') ?? 0) + 1;
        $this->order = ($this->levels->max('order') ?? 0) + 1;
    }

    public function render()
    {
        return view('livewire.admin.gamification.level-management');
    }
}
