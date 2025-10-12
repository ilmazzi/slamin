<?php

namespace App\Livewire;

use App\Models\ForumVote;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ForumVoteButton extends Component
{
    public $voteable;
    public $voteableType;
    public $voteableId;
    public $upvotes;
    public $downvotes;
    public $score;
    public $userVote = null;

    public function mount($voteable)
    {
        $this->voteable = $voteable;
        $this->voteableType = get_class($voteable);
        $this->voteableId = $voteable->id;
        $this->upvotes = $voteable->upvotes;
        $this->downvotes = $voteable->downvotes;
        $this->score = $voteable->score;

        if (Auth::check()) {
            $vote = $voteable->getUserVote(Auth::user());
            $this->userVote = $vote ? $vote->vote_type : null;
        }
    }

    public function vote($type)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $result = ForumVote::handleVote(Auth::user(), $this->voteable, $type);

        $this->upvotes = $result['upvotes'];
        $this->downvotes = $result['downvotes'];
        $this->score = $result['score'];

        // Update user vote state
        if ($result['action'] === 'removed') {
            $this->userVote = null;
        } else {
            $this->userVote = $type;
        }

        $this->voteable->refresh();
    }

    public function upvote()
    {
        $this->vote('upvote');
    }

    public function downvote()
    {
        $this->vote('downvote');
    }

    public function render()
    {
        return view('livewire.forum-vote-button');
    }
}
