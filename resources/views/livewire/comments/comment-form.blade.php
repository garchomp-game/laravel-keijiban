<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8">
    <div class="p-6">
        <form wire:submit.prevent="saveComment">
            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('コメントを投稿する') }}
            </label>
            <textarea wire:model="content" id="content" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md"></textarea>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            
            <div class="mt-3 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('投稿する') }}
                </button>
            </div>
        </form>
    </div>
</div>
