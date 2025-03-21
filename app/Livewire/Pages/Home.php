<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Collection;

class Home extends Component
{
    public Collection $latestPosts;
    public Collection $popularPosts;
    public Collection $categories;

    public function mount(): void
    {
        $this->latestPosts = Post::with(['user', 'category'])
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        $this->popularPosts = Post::with(['user', 'category'])
            ->where('status', 'published')
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();

        $this->categories = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.home');
    }
}
