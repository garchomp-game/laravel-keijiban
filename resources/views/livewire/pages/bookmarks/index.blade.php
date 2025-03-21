<?php

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Volt\Component;

new class extends Component {
    use WithPagination;
    
    public $sort = 'latest';
    
    public function updatedSort(): void
    {
        $this->resetPage();
    }
    
    public function getBookmarkedPosts()
    {
        $user = Auth::user();
        
        $query = Post::with(['user', 'category', 'likes', 'comments'])
            ->whereHas('bookmarks', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
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

<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">ブックマーク</h1>
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
                    @foreach ($bookmarks as $bookmark)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <h2 class="text-xl font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('posts.show', $bookmark->post) }}" class="hover:underline">
                                            {{ $bookmark->post->title }}
                                        </a>
                                    </h2>
                                    <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $bookmark->post->user->name }}</span>
                                        <span class="mx-1">•</span>
                                        <span>{{ $bookmark->post->created_at->format('Y年m月d日') }}</span>
                                        <span class="mx-1">•</span>
                                        <span>{{ $bookmark->post->category->name }}</span>
                                    </div>
                                    <p class="mt-2 text-gray-600 dark:text-gray-300 line-clamp-3">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($bookmark->post->content), 200) }}
                                    </p>
                                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ $bookmark->post->view_count }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                            </svg>
                                            {{ $bookmark->post->likes->count() }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                            {{ $bookmark->post->comments->count() }}
                                        </span>
                                        <button wire:click="removeBookmark({{ $bookmark->id }})" class="text-red-500 hover:text-red-700" onclick="return confirm('このブックマークを削除しますか？')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if ($bookmarks->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">ブックマークした投稿はありません。</p>
                        </div>
                    @endif
                </div>
                
                <!-- ページネーション -->
                <div class="mt-6">
                    {{ $bookmarks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
