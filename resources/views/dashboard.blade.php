<x-layouts.app :title="__('Dashboard')">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('ダッシュボード') }}</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- アクティビティカード -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- 統計カード -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-4">
                            <div class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ Auth::user()->posts->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('投稿') }}
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-4">
                            <div class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ Auth::user()->comments->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('コメント') }}
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-4">
                            <div class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ Auth::user()->bookmarks->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('ブックマーク') }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- 最近の投稿 -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('最近の投稿') }}</h3>
                        
                        @if (Auth::user()->posts->count() > 0)
                            <div class="space-y-4">
                                @foreach (Auth::user()->posts->sortByDesc('created_at')->take(5) as $post)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
                                        </h4>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span>{{ $post->created_at->format('Y年m月d日') }}</span>
                                            <span class="mx-1">•</span>
                                            <span>{{ $post->comments->count() }} コメント</span>
                                            <span class="mx-1">•</span>
                                            <span>{{ $post->likes->count() }} いいね</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('posts.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ __('すべての投稿を見る') }} →
                                </a>
                            </div>
                        @else
                            <div class="text-gray-500 dark:text-gray-400">
                                {{ __('まだ投稿がありません。') }}
                                <a href="{{ route('posts.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ __('最初の投稿を作成しましょう') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- 最近のコメント -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('最近のコメント') }}</h3>
                        
                        @if (Auth::user()->comments->count() > 0)
                            <div class="space-y-4">
                                @foreach (Auth::user()->comments->sortByDesc('created_at')->take(5) as $comment)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <a href="{{ route('posts.show', $comment->post) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                "{{ $comment->post->title }}"
                                            </a>
                                            に投稿:
                                        </div>
                                        <div class="mt-1 text-gray-700 dark:text-gray-300">
                                            {{ \Illuminate\Support\Str::limit($comment->content, 100) }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $comment->created_at->format('Y年m月d日 H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-gray-500 dark:text-gray-400">
                                {{ __('まだコメントがありません。') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- サイドバー -->
                <div class="space-y-6">
                    <!-- プロフィールカード -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="relative flex items-center justify-center h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xl font-bold">
                                        {{ Auth::user()->initials() }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ Auth::user()->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('会員登録日') }}: {{ Auth::user()->created_at->format('Y年m月d日') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('profile.show', Auth::id()) }}" class="flex items-center text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ __('プロフィール設定') }}
                                </a>
                                <a href="{{ route('posts.create') }}" class="flex items-center text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('新規投稿') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- クイックリンク -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('クイックリンク') }}
                            </h3>
                            <div class="space-y-1">
                                <a href="{{ route('posts.index') }}" class="block text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ __('投稿一覧') }}
                                </a>
                                <a href="{{ route('bookmarks.index') }}" class="block text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ __('ブックマーク') }}
                                </a>
                                <a href="{{ route('categories.index') }}" class="block text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ __('カテゴリー一覧') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- アクティビティサマリー -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ __('アクティビティ') }}
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('受け取ったいいね') }}</span>
                                    <span class="text-gray-800 dark:text-gray-200">
                                        {{ Auth::user()->posts->sum(function($post) { return $post->likes->count(); }) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('投稿した返信') }}</span>
                                    <span class="text-gray-800 dark:text-gray-200">
                                        {{ Auth::user()->comments->whereNotNull('parent_id')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('総閲覧数') }}</span>
                                    <span class="text-gray-800 dark:text-gray-200">
                                        {{ Auth::user()->posts->sum('view_count') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
