<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = null;
    public $sort = 'latest';
    
    public function mount($categoryId = null)
    {
        if ($categoryId) {
            $this->category = $categoryId;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function getAllCategoriesProperty()
    {
        return Category::all();
    }

    public function getPosts()
    {
        $query = Post::query()
            ->with(['user', 'category', 'likes', 'comments'])
            ->where('status', 'published');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        switch ($this->sort) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'most_commented':
                $query->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 'most_liked':
                $query->withCount('likes')->orderBy('likes_count', 'desc');
                break;
            default:
                // Default to latest if an invalid sort option is provided
                $query->latest();
                break;
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.pages.posts.index');
    }
}
