<div>
    <?php    <?php

    use App\Models\Category;ategory;
    use App\Models.Post;
    use Illuminate\Support\Collection;ollection;
    use Livewire\Attributes\Layout;    use Livewire\Attributes\Layout;
    use function Livewire\Volt\state;t\state;

    #[Layout('layouts.app')]
    ) {
    state([            return Post::with(['user', 'category'])
        'latestPosts' => function() { 'published')
            return Post::with(['user', 'category'])           ->latest()
                ->where('status', 'published')
                ->latest()
                ->take(5)
                ->get();' => function() {
        },ost::with(['user', 'category'])
        'popularPosts' => function() {                ->where('status', 'published')
            return Post::with(['user', 'category'])
                ->where('status', 'published')
                ->orderBy('view_count', 'desc')
                ->take(5)
                ->get(); => function() {
        },            return Category::withCount('posts')
        'categories' => function() {
            return Category::withCount('posts')
                ->orderBy('posts_count', 'desc'));
                ->take(10)
                ->get(););
        }
    ]);    ?>

    ?>

    <x-layouts.app>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- ヒーローセクション -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">掲示板へようこそ</h1>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">情報共有や質問、議論のためのコミュニティスペースです。</p>
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            新規投稿
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- メインコンテンツ -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">最新の投稿</h2>
                            <div class="space-y-4">
                                @forelse ($latestPosts as $post)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                        <h3 class="text-lg font-medium">
                                            <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span>{{ $post->user->name }}</span>
                                            <span class="mx-1">•</span>
                                            <span>{{ $post->created_at->format('Y年m月d日') }}</span>
                                            <span class="mx-1">•</span>
                                            <span>{{ $post->category->name }}</span>
                                        </div>
                                        <p class="mt-2 text-gray-600 dark:text-gray-300 line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-gray-500 dark:text-gray-400">投稿がありません。</p>
                                @endforelse
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">すべての投稿を見る →</a>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">人気の投稿</h2>
                            <div class="space-y-4">
                                @forelse ($popularPosts as $post)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                                        <h3 class="text-lg font-medium">
                                            <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <span>{{ $post->view_count }} 閲覧</span>
                                            <span class="mx-1">•</span>
                                            <span>{{ $post->likes->count() }} いいね</span>
                                            <span class="mx-1">•</span>
                                            <span>{{ $post->comments->count() }} コメント</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 dark:text-gray-400">投稿がありません。</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- サイドバー -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">カテゴリー</h2>
                            <ul class="space-y-2">
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex justify-between">
                                            <span>{{ $category->name }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">{{ $category->posts_count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @auth
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">クイックアクセス</h2>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                            ダッシュボード
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                            新規投稿
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('settings.profile') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                            プロフィール
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">アカウント</h2>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">アカウントを作成して投稿やコメントを始めましょう。</p>
                                <div class="space-y-2">
                                    <a href="{{ route('login') }}" class="inline-block w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        ログイン
                                    </a>
                                    <a href="{{ route('register') }}" class="inline-block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        登録
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </x-layouts.app>
</div>
