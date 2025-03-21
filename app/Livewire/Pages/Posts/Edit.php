<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Edit extends Component
{
    public Post $post;
    public $categories;
    public $tagString = '';
    
    public function mount($slug)
    {
        $this->post = Post::where('slug', $slug)->firstOrFail();
        
        // 投稿者本人かチェック
        if (Auth::id() !== $this->post->user_id) {
            abort(403);
        }
        
        $this->categories = Category::orderBy('name')->get();
        $this->tagString = $this->post->tags->pluck('name')->implode(',');
    }
    
    public function save()
    {
        // 投稿者本人かチェック
        if (Auth::id() !== $this->post->user_id) {
            abort(403);
        }
        
        $validated = $this->validate([
            'post.title' => 'required|min:3|max:255',
            'post.content' => 'required|min:10',
            'post.category_id' => 'required|exists:categories,id',
            'post.status' => 'required|in:published,draft',
            'tagString' => 'nullable|string|max:255',
        ], [
            'post.title.required' => 'タイトルは必須です',
            'post.title.min' => 'タイトルは3文字以上で入力してください',
            'post.content.required' => '内容は必須です',
            'post.content.min' => '内容は10文字以上で入力してください',
            'post.category_id.required' => 'カテゴリーは必須です',
            'post.category_id.exists' => '選択されたカテゴリーは存在しません',
        ]);
        
        // スラッグを更新するかどうか（タイトルが変更された場合のみ）
        if ($this->post->isDirty('title')) {
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
        
        session()->flash('message', '投稿を更新しました');
        
        return redirect()->route('dashboard');
    }
    
    public function render()
    {
        return view('livewire.pages.posts.edit');
    }
}
