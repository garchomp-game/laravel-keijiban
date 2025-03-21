<div>
    @auth
        <button wire:click="toggleBookmark" class="flex items-center {{ $isBookmarked ? 'text-yellow-500 dark:text-yellow-400' : '' }} hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
            <span>{{ $isBookmarked ? 'ブックマーク済み' : 'ブックマーク' }}</span>
        </button>
    @else
        <a href="{{ route('login') }}" class="flex items-center hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
            <span>ブックマーク</span>
        </a>
    @endauth
</div>
