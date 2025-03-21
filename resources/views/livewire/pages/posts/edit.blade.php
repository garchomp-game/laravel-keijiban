<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    public $categories;
    public $tagString = '';
    
    public function mount($slug): void
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
}; ?>

<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('投稿の編集') }}</h2>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                <form wire:submit="save" class="p-6 space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">タイトル</label>
                        <input wire:model="post.title" type="text" id="title" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" placeholder="投稿のタイトルを入力してください">
                        @error('post.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">カテゴリー</label>
                        <select wire:model="post.category_id" id="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">カテゴリーを選択</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('post.category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">内容</label>
                        <textarea wire:model="post.content" id="content" rows="12" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" placeholder="投稿の内容を入力してください"></textarea>
                        @error('post.content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">タグ</label>
                        <input wire:model="tagString" type="text" id="tags" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" placeholder="タグをカンマ区切りで入力してください">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">タグはカンマ（,）で区切って入力してください</p>
                        @error('tagString') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ステータス</label>
                        <select wire:model="post.status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="published">公開</option>
                            <option value="draft">下書き</option>
                        </select>
                        @error('post.status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            キャンセル
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            更新する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
