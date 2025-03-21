<div>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('投稿一覧') }}</h1>
            
            @auth
            <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('新規投稿') }}
            </a>
            @endauth
        </div>
        
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('検索') }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" wire:model.debounce.300ms="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-10 sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md" placeholder="{{ __('タイトルまたは内容で検索...') }}">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('カテゴリー') }}</label>
                            <select wire:model="category" id="category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">{{ __('すべて') }}</option>
                                @foreach($this->allCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('並び替え') }}</label>
                            <select wire:model="sort" id="sort" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="latest">{{ __('新着順') }}</option>
                                <option value="oldest">{{ __('古い順') }}</option>
                                <option value="popular">{{ __('人気順') }}</option>
                                <option value="most_commented">{{ __('コメント数順') }}</option>
                                <option value="most_liked">{{ __('いいね数順') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            @if($this->getPosts()->count() > 0)
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->getPosts() as $post)
                    <li class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="px-4 py-6 sm:px-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
                                </h2>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $post->created_at->format('Y年m月d日') }}
                                </div>
                            </div>
                            
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 200) }}
                            </div>
                            
                            <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center mr-4">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ $post->view_count }}
                                </div>
                                <div class="flex items-center mr-4">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    {{ $post->comments->count() }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    {{ $post->likes->count() }}
                                </div>
                                
                                @if($post->category)
                                <div class="ml-auto">
                                    <a href="{{ route('categories.show', $post->category->slug) }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        {{ $post->category->name }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-800">
                    {{ $this->getPosts()->links() }}
                </div>
            @else
                <div class="px-4 py-6 sm:px-6 text-center text-gray-500 dark:text-gray-400">
                    {{ __('該当する投稿がありません。検索条件を変更してみてください。') }}
                </div>
            @endif
        </div>
    </div>
</div>
