<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class PostCard extends Component
{
    public Post $post;
    public bool $showExcerpt = true;
    public int $excerptLength = 150;

    public function getExcerptProperty()
    {
        if ($this->showExcerpt) {
            return \Illuminate\Support\Str::limit(strip_tags($this->post->content), $this->excerptLength);
        }
        
        return $this->post->content;
    }

    public function render()
    {
        return view('livewire.posts.post-card');
    }
}
