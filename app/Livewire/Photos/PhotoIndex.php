<?php

namespace App\Livewire\Photos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PhotoIndex extends Component
{
    use WithPagination;

    public $userId = null;
    public $search = '';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount($userId = null)
    {
        $this->userId = $userId;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getPhotosProperty()
    {
        $query = Photo::with(['user'])
            ->where('status', 'approved')
            ->where('moderation_status', 'approved');

        // Filtra per utente se specificato
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        // Filtra per ricerca
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('alt_text', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($this->perPage);
    }

    public function getUserProperty()
    {
        if ($this->userId) {
            return User::find($this->userId);
        }
        return Auth::user();
    }

    public function getIsOwnPhotosProperty()
    {
        return $this->userId === null || $this->userId === Auth::id();
    }

    public function render()
    {
        return view('livewire.photos.photo-index', [
            'photos' => $this->photos,
            'user' => $this->user,
            'isOwnPhotos' => $this->isOwnPhotos,
        ]);
    }
}



