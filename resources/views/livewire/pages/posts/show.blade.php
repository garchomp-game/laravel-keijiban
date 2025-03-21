<div>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('posts.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('投稿一覧に戻る') }}
            </a>
        </div>
        
        <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $post->title }}</h1>
                
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-300 font-medium">
                                {{ $post->user->initials() }}
                            </div>
                        </div>
                        <div class="ml-2">
                            <span class="font-medium">{{ $post->user->name }}</span>
                        </div>
                    </div>
                    <span class="mx-2">•</span>
                    <span>{{ $post->created_at->format('Y年m月d日') }}</span>
                    <span class="mx-2">•</span>
                    @if ($post->category)
                    <a href="{{ route('categories.show', $post->category->slug) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                        {{ $post->category->name }}
                    </a>
                    @endif
                </div>
                
                @if(auth()->id() === $post->user_id)
                <div class="flex space-x-2 mb-4">
                    <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        編集
                    </a>
                    <button wire:click="deletePost" onclick="return confirm('この投稿を削除してもよろしいですか？')" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        削除
                    </button>
                </div>
                @endif
                
                <div class="prose prose-blue max-w-none dark:prose-dark mt-6">
                    {!! nl2br(e($post->content)) !!}
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <button wire:click="toggleLike" class="{{ $isLiked ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-500' }} hover:text-red-500 dark:hover:text-red-400 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $isLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            <span class="ml-1 text-gray-500 dark:text-gray-400">{{ $post->likes->count() }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <button wire:click="toggleBookmark" class="{{ $isBookmarked ? 'text-yellow-500 dark:text-yellow-400' : 'text-gray-400 dark:text-gray-500' }} hover:text-yellow-500 dark:hover:text-yellow-400 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="text-gray-500 dark:text-gray-400">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $post->view_count }} {{ __('閲覧') }}
                        </span>
                    </div>
                </div>
            </div>
        </article>
        
        <!-- コメントセクション -->
        <div class="mt-10">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('コメント') }} ({{ $comments->count() }})</h2>
            
            @auth
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <form wire:submit.prevent="addComment">
                        <label for="commentContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('コメントを投稿する') }}
                        </label>
                        <textarea wire:model="commentContent" id="commentContent" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md"></textarea>
                        @error('commentContent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('投稿する') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8 p-6 text-center">
                <p class="text-gray-600 dark:text-gray-400">
                    {{ __('コメントを投稿するには') }}
                    <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('ログイン') }}</a>
                    {{ __('してください。') }}
                </p>
            </div>
            @endauth
            
            <div class="space-y-6">
                @forelse($comments as $comment)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-300 font-medium">
                                    {{ $comment->user->initials() }}
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $comment->user->name }}
                                    </h3>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $comment->created_at->format('Y年m月d日 H:i') }}
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                    {!! nl2br(e($comment->content)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-gray-500 dark:text-gray-400">{{ __('まだコメントはありません。最初のコメントを投稿しましょう。') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
