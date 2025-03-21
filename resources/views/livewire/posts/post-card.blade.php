<div class="border rounded-lg shadow-sm p-4 bg-white dark:bg-gray-800 dark:border-gray-700">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
        <a href="{{ route('dashboard') }}" class="hover:underline">
            {{ $post->title }}
        </a>
    </h3>
    
    <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
        <span>{{ $post->user->name }}</span>
        <span class="mx-1">•</span>
        <span>{{ $post->created_at->format('Y年m月d日') }}</span>
        <span class="mx-1">•</span>
        <span>{{ $post->category->name }}</span>
    </div>
    
    @if ($showExcerpt)
        <p class="mt-2 text-gray-600 dark:text-gray-300">
            {{ $this->excerpt }}
        </p>
        <a href="{{ route('dashboard') }}" class="mt-2 inline-block text-blue-600 dark:text-blue-400 hover:underline">
            続きを読む →
        </a>
    @endif
    
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
