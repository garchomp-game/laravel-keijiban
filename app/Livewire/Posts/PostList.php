<?php

namespace App\Livewire\Posts;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class PostList extends Component
{
    public Collection $posts;
    public ?string $emptyMessage = null;

    public function render()
    {
        return view('livewire.posts.post-list');
    }
}
