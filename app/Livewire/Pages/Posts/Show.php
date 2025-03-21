<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public Post $post;
    public $commentContent = '';
    
    // Modified mount method to accept a Post model directly
    public function mount(Post $post)
    {
        $this->post = $post;
        
        // Increment view count
        if (!session()->has('viewed_post_' . $this->post->id)) {
            $this->post->increment('view_count');
            session()->put('viewed_post_' . $this->post->id, true);
        }
    }
    
    public function deletePost()
    {
        if (auth()->id() !== $this->post->user_id) {
            return;
        }
        
        $this->post->delete();
        session()->flash('message', '投稿を削除しました');
        
        return redirect()->route('posts.index');
    }
    
    public function addComment()
    {
        $this->validate([
            'commentContent' => 'required|min:3|max:1000',
        ], [
            'commentContent.required' => 'コメント内容は必須です',
            'commentContent.min' => 'コメントは3文字以上で入力してください',
        ]);
        
        $this->post->comments()->create([
            'content' => $this->commentContent,
            'user_id' => auth()->id(),
        ]);
        
        $this->commentContent = '';
        
        session()->flash('message', 'コメントを投稿しました');
    }
    
    public function toggleLike()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        if ($this->post->likes()->where('user_id', $user->id)->exists()) {
            // Unlike
            $this->post->likes()->where('user_id', $user->id)->delete();
        } else {
            // Like
            $this->post->likes()->create([
                'user_id' => $user->id,
            ]);
        }
    }
    
    public function toggleBookmark()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $user = auth()->user();
        
        if ($this->post->bookmarks()->where('user_id', $user->id)->exists()) {
            // Remove bookmark
            $this->post->bookmarks()->where('user_id', $user->id)->delete();
        } else {
            // Add bookmark
            $this->post->bookmarks()->create([
                'user_id' => $user->id,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pages.posts.show', [
            'comments' => $this->post->comments()->with('user')->latest()->get(),
            'isLiked' => auth()->check() ? $this->post->likes()->where('user_id', auth()->id())->exists() : false,
            'isBookmarked' => auth()->check() ? $this->post->bookmarks()->where('user_id', auth()->id())->exists() : false,
        ]);
    }
}
