<?php

namespace App\Livewire\Likes;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LikeButton extends Component
{
    public Post $post;
    public $likesCount = 0;
    public $isLiked = false;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->refreshLikeStatus();
    }

    public function refreshLikeStatus()
    {
        $this->likesCount = $this->post->likes()->count();
        
        if (Auth::check()) {
            $this->isLiked = $this->post->isLikedBy(Auth::user());
        }
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isLiked) {
            // いいねを取り消す
            $this->post->likes()->where('user_id', Auth::id())->delete();
        } else {
            // いいねを追加
            $this->post->likes()->create([
                'user_id' => Auth::id()
            ]);
        }

        $this->refreshLikeStatus();
    }

    public function render()
    {
        return view('livewire.likes.like-button');
    }
}
