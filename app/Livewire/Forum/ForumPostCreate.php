<?php

namespace App\Livewire\Forum;

use App\Models\Subreddit;
use App\Models\ForumPost;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ForumPostCreate extends Component
{
    use WithFileUploads;

    public $subreddit_id;
    public $selectedSubreddit;
    public $postType = 'text'; // text, link, image
    public $title = '';
    public $content = '';
    public $url = '';
    public $image;

    protected function rules()
    {
        $rules = [
            'subreddit_id' => 'required|exists:subreddits,id',
            'title' => 'required|string|min:3|max:300',
            'postType' => 'required|in:text,link,image',
        ];

        switch ($this->postType) {
            case 'text':
                $rules['content'] = 'required|string|min:1|max:40000';
                break;
            case 'link':
                $rules['url'] = 'required|url|max:2000';
                break;
            case 'image':
                $rules['image'] = 'required|image|max:5120'; // 5MB
                break;
        }

        return $rules;
    }

    public function mount($subredditSlug = null)
    {
        if ($subredditSlug) {
            $this->selectedSubreddit = Subreddit::where('slug', $subredditSlug)->firstOrFail();
            $this->subreddit_id = $this->selectedSubreddit->id;
        }
    }

    public function updatedSubredditId($value)
    {
        $this->selectedSubreddit = Subreddit::find($value);
    }

    public function createPost()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $postData = [
            'subreddit_id' => $this->subreddit_id,
            'user_id' => Auth::id(),
            'title' => $this->title,
            'type' => $this->postType,
            'approved_at' => now(), // Auto-approve for now
        ];

        if ($this->postType === 'text') {
            $postData['content'] = $this->content;
        } elseif ($this->postType === 'link') {
            $postData['url'] = $this->url;
        } elseif ($this->postType === 'image' && $this->image) {
            // Convert to WebP and save
            $originalName = $this->image->getClientOriginalName();
            $filename = time() . '_' . uniqid() . '.webp';
            $path = 'forum/images/' . $filename;

            $imageContent = Image::make($this->image->getRealPath())
                ->encode('webp', 90)
                ->stream();

            Storage::disk('public')->put($path, $imageContent);

            $postData['image_path'] = $path;
            $postData['original_image_name'] = $originalName;
        }

        $post = ForumPost::create($postData);

        // Increment subreddit post count
        $this->selectedSubreddit->increment('posts_count');

        session()->flash('success', __('forum.post_created_successfully'));

        return redirect()->route('forum.post.show', [
            'subreddit' => $this->selectedSubreddit->slug,
            'post' => $post->id
        ]);
    }

    public function render()
    {
        $subreddits = Subreddit::active()->public()->orderBy('name')->get();

        return view('livewire.forum-post-create', [
            'subreddits' => $subreddits,
        ]);
    }
}
