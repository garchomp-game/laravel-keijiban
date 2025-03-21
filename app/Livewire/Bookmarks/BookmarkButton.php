<?php

namespace App\Livewire\Bookmarks;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookmarkButton extends Component
{
    public Post $post;
    public $isBookmarked = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->refreshBookmarkStatus();
    }

    public function refreshBookmarkStatus()
    {
        if (Auth::check()) {
            $this->isBookmarked = $this->post->isBookmarkedBy(Auth::user());
        }
    }

    public function toggleBookmark()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isBookmarked) {
            // ブックマークを取り消す
            $this->post->bookmarks()->where('user_id', Auth::id())->delete();
        } else {
            // ブックマークを追加
            $this->post->bookmarks()->create([
                'user_id' => Auth::id()
            ]);
        }

        $this->refreshBookmarkStatus();
    }

    public function render()
    {
        return view('livewire.bookmarks.bookmark-button');
    }
}
