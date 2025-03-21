<?php

namespace App\Livewire\Comments;

use App\Models\Post;
use Livewire\Component;

class CommentForm extends Component
{
    public Post $post;
    public $content = '';
    
    protected $rules = [
        'content' => 'required|min:3|max:1000',
    ];
    
    protected $messages = [
        'content.required' => 'コメント内容は必須です',
        'content.min' => 'コメントは3文字以上で入力してください',
    ];
    
    public function mount(Post $post)
    {
        $this->post = $post;
    }
    
    public function saveComment()
    {
        $this->validate();
        
        $this->post->comments()->create([
            'content' => $this->content,
            'user_id' => auth()->id(),
        ]);
        
        $this->content = '';
        
        session()->flash('message', 'コメントを投稿しました');
        
        // Emit an event to refresh the comment list
        $this->dispatch('commentAdded');
    }
    
    public function render()
    {
        return view('livewire.comments.comment-form');
    }
}
