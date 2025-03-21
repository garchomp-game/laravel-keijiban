<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;
    
    public Category $category;
    public $sort = 'latest';
    
    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)->firstOrFail();
    }
    
    public function updatedSort()
    {
        $this->resetPage();
    }
    
    public function getPosts()
    {
        $query = Post::with(['user', 'likes', 'comments'])
            ->where('category_id', $this->category->id)
            ->where('status', 'published');
        
        if ($this->sort === 'latest') {
            $query->latest();
        } elseif ($this->sort === 'oldest') {
            $query->oldest();
        } elseif ($this->sort === 'popular') {
            $query->orderBy('view_count', 'desc');
        } elseif ($this->sort === 'most_commented') {
            $query->withCount('comments')->orderBy('comments_count', 'desc');
        } elseif ($this->sort === 'most_liked') {
            $query->withCount('likes')->orderBy('likes_count', 'desc');
        }
        
        return $query->paginate(10);
    }
    
    public function render()
    {
        return view('livewire.pages.categories.show');
    }
}
