<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NewUsersSection extends Component
{
    public function followUser($userId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Devi essere loggato per seguire un utente.');
            return;
        }

        $user = User::find($userId);
        $currentUser = Auth::user();

        if (!$user) {
            session()->flash('error', 'Utente non trovato.');
            return;
        }

        if ($user->id === $currentUser->id) {
            session()->flash('error', 'Non puoi seguire te stesso.');
            return;
        }

        // Qui implementeremo la logica di follow quando avremo il sistema di follow
        // Per ora mostriamo solo un messaggio di successo
        session()->flash('success', "Hai iniziato a seguire {$user->name}!");
    }

    public function render()
    {
        $newUsers = User::withCount(['poems', 'articles', 'likes', 'comments', 'views'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('livewire.home.new-users-section', [
            'newUsers' => $newUsers
        ]);
    }
}
