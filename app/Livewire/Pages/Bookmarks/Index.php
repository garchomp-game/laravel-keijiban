<?php

namespace App\Livewire\Pages\Bookmarks;

use App\Models\Bookmark;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $sort = 'latest';
    
    public function updatedSort()
    {
        $this->resetPage();
    }
    
    public function removeBookmark($bookmarkId)
    {
        $bookmark = Bookmark::findOrFail($bookmarkId);
        
        if ($bookmark->user_id === auth()->id()) {
            $bookmark->delete();
            session()->flash('message', 'ブックマークを削除しました');
        }
    }
    
    public function getBookmarks()
    {
        $query = auth()->user()->bookmarks()
            ->with(['post', 'post.user', 'post.category', 'post.likes', 'post.comments']);
        
        if ($this->sort === 'latest') {
            $query->latest();
        } elseif ($this->sort === 'oldest') {
            $query->oldest();
        } elseif ($this->sort === 'popular') {
            $query->whereHas('post', function($q) {
                $q->orderBy('view_count', 'desc');
            });
        } elseif ($this->sort === 'most_commented') {
            $query->whereHas('post', function($q) {
                $q->withCount('comments')->orderBy('comments_count', 'desc');
            });
        } elseif ($this->sort === 'most_liked') {
            $query->whereHas('post', function($q) {
                $q->withCount('likes')->orderBy('likes_count', 'desc');
            });
        }
        
        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.pages.bookmarks.index', [
            'bookmarks' => $this->getBookmarks()
        ]);
    }
}
