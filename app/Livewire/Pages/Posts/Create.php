<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $title = '';
    public $content = '';
    public $category_id = '';
    public $is_public = true;

    protected $rules = [
        'title' => 'required|min:5|max:255',
        'content' => 'required|min:10',
        'category_id' => 'required|exists:categories,id',
    ];

    public function save()
    {
        $this->validate();

        $post = new Post([
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => $this->category_id,
            'slug' => Str::slug($this->title) . '-' . Str::random(5),
            'status' => $this->is_public ? 'published' : 'draft',
        ]);

        $post->user_id = auth()->id();
        $post->save();

        session()->flash('message', '投稿が正常に作成されました。');
        
        return redirect()->route('posts.show', $post);
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.pages.posts.create');
    }
}
