<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ProfileShow extends Component
{
    use WithPagination;

    public $userId;
    public $activeTab = 'about';
    public $perPage = 6;

    protected $queryString = [
        'activeTab' => ['except' => 'about'],
    ];
    
    // Force Livewire to use Bootstrap pagination
    protected $paginationTheme = 'bootstrap';

    public function mount($user = null)
    {
        // Livewire 3: i parametri della route POSSONO essere passati al mount()
        // ma SOLO se non c'è una proprietà pubblica con lo stesso nome
        
        if ($user) {
            // $user può essere un oggetto User (da model binding) o un ID (stringa)
            if (is_object($user) && isset($user->id)) {
                $this->userId = (int) $user->id;
            } else {
                // Se è una stringa, proviamo prima come ID, poi come nickname
                $userModel = null;
                
                // Prova come ID numerico
                if (is_numeric($user)) {
                    $userModel = User::find($user);
                }
                
                // Se non trovato come ID, prova come nickname
                if (!$userModel) {
                    $userModel = User::where('nickname', $user)->first();
                }
                
                if ($userModel) {
                    $this->userId = (int) $userModel->id;
                } else {
                    abort(404, 'User not found');
                }
            }
        } else {
            // Se non c'è parametro user nella route (es. /profile),
            // mostra il profilo dell'utente loggato
            $this->userId = (int) Auth::id();
        }
    }

    public function getUserProperty()
    {
        return User::findOrFail((int) $this->userId);
    }

    public function getIsOwnProfileProperty()
    {
        return (int) $this->userId === (int) Auth::id();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
    
    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function getPhotosProperty()
    {
        return $this->user->photos()
            ->where('status', 'approved')
            ->where('moderation_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage, ['*'], 'photos_page');
    }

    public function getVideosProperty()
    {
        return $this->user->videos()
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage, ['*'], 'videos_page');
    }

    public function getArticlesProperty()
    {
        return $this->user->articles()
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage, ['*'], 'articles_page');
    }

    public function getPoemsProperty()
    {
        return $this->user->poems()
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage, ['*'], 'poems_page');
    }

    public function getRecentPhotosProperty()
    {
        return $this->user->photos()
            ->where('status', 'approved')
            ->where('moderation_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    public function getRecentActivitiesProperty()
    {
        return $this->user->activities()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    public function getTopBadgesProperty()
    {
        return $this->user->userBadges()
            ->with('badge')
            ->where('show_in_profile', true)
            ->orderBy('profile_order', 'asc')
            ->limit(3)
            ->get();
    }

    public function getLevelProperty()
    {
        return $this->user->level;
    }

    public function getTotalPointsProperty()
    {
        return $this->user->totalPoints;
    }

    public function getBadgesCountProperty()
    {
        return $this->user->badgesCount;
    }

    public function getStatsProperty()
    {
        return [
            'photos' => $this->user->photos()->approved()->count(),
            'videos' => $this->user->videos()->approved()->count(),
            'articles' => $this->user->articles()->published()->count(),
            'poems' => $this->user->poems()->published()->count(),
            'followers' => $this->user->followers()->count(),
            'following' => $this->user->following()->count(),
            'total_views' => $this->user->photos()->sum('view_count') + 
                           $this->user->videos()->sum('view_count') + 
                           $this->user->articles()->sum('views_count') + 
                           $this->user->poems()->sum('view_count'),
            'total_likes' => $this->user->photos()->sum('like_count') + 
                           $this->user->videos()->sum('like_count') + 
                           $this->user->articles()->sum('likes_count') + 
                           $this->user->poems()->sum('like_count'),
        ];
    }

    public function getActivityIcon($type)
    {
        $icons = [
            'poem_created' => 'book-open',
            'article_created' => 'newspaper',
            'event_organized' => 'calendar-check',
            'event_participation' => 'users',
            'badge_earned' => 'medal',
            'video_uploaded' => 'video-camera',
            'photo_uploaded' => 'image',
            'comment_added' => 'chat-circle',
            'like_given' => 'heart',
        ];
        
        return $icons[$type] ?? 'activity';
    }

    public function getEventsProperty()
    {
        return $this->user->events()
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage, ['*'], 'events_page');
    }

    public function getActivitiesProperty()
    {
        return $this->user->activities()
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage, ['*'], 'activities_page');
    }

    public function render()
    {
        return view('livewire.profile.profile-show', [
            'user' => $this->user,
            'photos' => $this->photos,
            'videos' => $this->videos,
            'articles' => $this->articles,
            'poems' => $this->poems,
            'events' => $this->events,
            'activities' => $this->activities,
            'recentPhotos' => $this->recentPhotos,
            'recentActivities' => $this->recentActivities,
            'topBadges' => $this->topBadges,
            'level' => $this->level,
            'totalPoints' => $this->totalPoints,
            'badgesCount' => $this->badgesCount,
            'stats' => $this->stats,
            'isOwnProfile' => $this->isOwnProfile,
            'activeTab' => $this->activeTab,
        ])->extends('layout.master')->section('main-content');
    }
}


