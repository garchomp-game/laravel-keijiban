<?php

namespace App\Livewire\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class PostForm extends Component
{
    public Post $post;
    public $categories;
    public $tagString = '';
    public $editing = false;

    protected $rules = [
        'post.title' => 'required|min:3|max:255',
        'post.content' => 'required|min:10',
        'post.category_id' => 'required|exists:categories,id',
        'post.status' => 'required|in:published,draft',
        'tagString' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'post.title.required' => 'タイトルは必須です',
        'post.title.min' => 'タイトルは3文字以上で入力してください',
        'post.content.required' => '内容は必須です',
        'post.content.min' => '内容は10文字以上で入力してください',
        'post.category_id.required' => 'カテゴリーは必須です',
        'post.category_id.exists' => '選択されたカテゴリーは存在しません',
    ];

    public function mount(Post $post = null)
    {
        $this->categories = Category::all();
        
        if ($post->exists) {
            $this->post = $post;
            $this->editing = true;
            $this->tagString = $post->tags->pluck('name')->implode(',');
        } else {
            $this->post = new Post();
            $this->post->status = 'published';
        }
    }

    public function save()
    {
        $this->validate();
        
        if (!$this->editing) {
            $this->post->user_id = Auth::id();
            $this->post->view_count = 0;
            $this->post->slug = Str::slug($this->post->title) . '-' . uniqid();
        }
        
        $this->post->save();
        
        // タグの処理
        if ($this->tagString) {
            $tagNames = array_map('trim', explode(',', $this->tagString));
            $tags = [];
            
            foreach ($tagNames as $name) {
                if (!empty($name)) {
                    $tag = Tag::firstOrCreate([
                        'name' => $name,
                        'slug' => Str::slug($name),
                    ]);
                    $tags[] = $tag->id;
                }
            }
            
            $this->post->tags()->sync($tags);
        } else {
            $this->post->tags()->detach();
        }
        
        session()->flash('message', $this->editing ? '投稿を更新しました' : '投稿を作成しました');
        
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.posts.post-form');
    }
}
