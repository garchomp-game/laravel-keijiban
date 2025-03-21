<div>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ __('新規投稿') }}</h1>
        
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('タイトル') }}
                </label>
                <div class="mt-1">
                    <input type="text" wire:model="title" id="title" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md">
                </div>
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('カテゴリー') }}
                </label>
                <div class="mt-1">
                    <select wire:model="category_id" id="category_id" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md">
                        <option value="">{{ __('選択してください') }}</option>
                        @foreach($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('内容') }}
                </label>
                <div class="mt-1">
                    <textarea wire:model="content" id="content" rows="10" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md"></textarea>
                </div>
                @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex items-center">
                <input wire:model="is_public" id="is_public" type="checkbox" 
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                <label for="is_public" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    {{ __('公開する') }}
                </label>
            </div>
            
            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('posts.index') }}" 
                        class="bg-white dark:bg-gray-700 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('キャンセル') }}
                    </a>
                    <button type="submit" 
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('投稿する') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
