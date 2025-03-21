<?php

namespace App\Livewire\Comments;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class CommentList extends Component
{
    public Post $post;
    
    public function mount(Post $post)
    {
        $this->post = $post;
    }
    
    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        
        // Check permission - only comment author or post author can delete
        if (auth()->id() === $comment->user_id || auth()->id() === $this->post->user_id) {
            $comment->delete();
            session()->flash('message', 'コメントを削除しました。');
        }
    }
    
    public function render()
    {
        $comments = $this->post->comments()->with('user')->latest()->get();
        
        return view('livewire.comments.comment-list', [
            'comments' => $comments
        ]);
    }
}
