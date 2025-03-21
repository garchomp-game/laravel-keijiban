<div class="space-y-6">
    @forelse ($comments as $comment)
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center">
                    <div class="font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</div>
                    <span class="mx-1 text-gray-500 dark:text-gray-400">•</span>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                
                @if (auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                    <button wire:click="deleteComment({{ $comment->id }})" class="text-red-500 hover:text-red-700 transition-colors duration-200" onclick="return confirm('このコメントを削除してもよろしいですか？')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                @endif
            </div>
            
            <div class="text-gray-700 dark:text-gray-300">
                {!! nl2br(e($comment->content)) !!}
            </div>
        </div>
    @empty
        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
            {{ __('まだコメントはありません。最初のコメントを投稿しましょう。') }}
        </div>
    @endforelse
</div>
