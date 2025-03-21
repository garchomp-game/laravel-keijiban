<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public User $user;
    
    public function mount(string $userId): void
    {
        $this->user = User::findOrFail($userId);
    }
}; ?>

<x-layouts.app>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- プロフィールヘッダー -->
                <div class="p-6 sm:p-8 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        <div class="relative flex h-24 w-24 shrink-0 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                            @if ($user->profile_image)
                                <img src="{{ $user->profile_image }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-3xl font-bold">
                                    {{ $user->initials() }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-center sm:text-left">
                            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                            <p class="mt-1 text-gray-100">{{ __('会員登録日') }}: {{ $user->created_at->format('Y年m月d日') }}</p>
                            
                            @if ($user->bio)
                                <p class="mt-4 text-sm text-gray-100">{{ $user->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- 統計情報 -->
                <div class="grid grid-cols-3 divide-x divide-gray-200 dark:divide-gray-700">
                    <div class="p-6 text-center">
                        <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->posts->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('投稿') }}</div>
                    </div>
                    <div class="p-6 text-center">
                        <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->comments->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('コメント') }}</div>
                    </div>
                    <div class="p-6 text-center">
                        <div class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $user->posts->sum(function($post) { return $post->likes->count(); }) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('いいね') }}</div>
                    </div>
                </div>
                
                <!-- 投稿一覧 -->
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('最近の投稿') }}</h2>
                    
                    @if ($user->posts->count() > 0)
                        <div class="space-y-6">
                            @foreach ($user->posts->sortByDesc('created_at')->take(5) as $post)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0 last:pb-0">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('dashboard') }}" class="hover:underline">{{ $post->title }}</a>
                                    </h3>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <span>{{ $post->created_at->format('Y年m月d日') }}</span>
                                        <span class="mx-1">•</span>
                                        <span>{{ $post->category->name }}</span>
                                    </div>
                                    <p class="mt-2 text-gray-600 dark:text-gray-300 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}
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
                            @endforeach
                        </div>
                        
                        @if ($user->posts->count() > 5)
                            <div class="mt-6 text-center">
                                <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-colors duration-200">
                                    {{ __('すべての投稿を見る') }}
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                            {{ __('投稿がありません') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
