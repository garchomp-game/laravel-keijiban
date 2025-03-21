<div>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('カテゴリー一覧') }}</h1>
            
            @auth
            <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('新規カテゴリー') }}
            </a>
            @endauth
        </div>
        
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    {{ __('すべてのカテゴリー') }}
                </h2>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    {{ __('トピック別に分類されたカテゴリーの一覧です。') }}
                </p>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($categories as $category)
                <div class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('categories.show', $category->slug) }}" 
                           class="text-lg font-medium text-blue-600 dark:text-blue-400 hover:underline">
                            {{ $category->name }}
                        </a>
                        <div class="flex items-center space-x-2">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $category->posts_count }} {{ __('件の投稿') }}
                                @if($category->children_count > 0)
                                    <span class="mx-1">•</span>
                                    {{ $category->children_count }} {{ __('件のサブカテゴリー') }}
                                @endif
                            </div>
                            
                            @auth
                            <a href="{{ route('categories.edit', $category) }}" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @endauth
                        </div>
                    </div>
                    @if($category->description)
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        {{ $category->description }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
