<?php

use App\Models\Category;
use App\Models\Post;
use Livewire\WithPagination;
use Livewire\Volt\Component;

new class extends Component {
    use WithPagination;
    
    public Category $category;
    public $sort = 'latest';
    
    public function mount($slug): void
    {
        $this->category = Category::where('slug', $slug)->firstOrFail();
    }
    
    public function updatedSort(): void
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
}; ?>

<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $category->name }}</h1>
                @if ($category->description)
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $category->description }}</p>
                @endif
                
                @if ($category->parent)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        親カテゴリー: 
                        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            {{ $category->parent->name }}
                        </a>
                    </div>
                @endif
                
                @if ($category->children->count() > 0)
                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">サブカテゴリー</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($category->children as $child)
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">「{{ $category->name }}」の投稿</h2>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            新規投稿
                        </a>
                    @endauth
                </div>
                
                <!-- 並び替え -->
                <div class="mb-6">
                    <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300">並び替え</label>
                    <select wire:model.live="sort" id="sort" class="mt-1 max-w-xs pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="latest">最新順</option>
                        <option value="oldest">古い順</option>
                        <option value="popular">人気順</option>
                        <option value="most_commented">コメント数順</option>
                        <option value="most_liked">いいね数順</option>
                    </select>
                </div>
                
                <!-- 投稿一覧 -->
                <div class="space-y-6">
                    @foreach ($this->getPosts() as $post)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('dashboard') }}" class="hover:underline">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $post->user->name }}</span>
                                        <span class="mx-1">•</span>
                                        <span>{{ $post->created_at->format('Y年m月d日') }}</span>
                                    </div>
                                    <p class="mt-2 text-gray-600 dark:text-gray-300 line-clamp-3">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 200) }}
                                    </p>
                                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ $post->view_count }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                            </svg>
                                            {{ $post->likes->count() }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                            {{ $post->comments->count() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if ($this->getPosts()->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">このカテゴリーにはまだ投稿がありません。</p>
                        </div>
                    @endif
                </div>
                
                <!-- ページネーション -->
                <div class="mt-6">
                    {{ $this->getPosts()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
