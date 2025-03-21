<div>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ __('カテゴリー編集') }}: {{ $category->name }}</h1>
        
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('カテゴリー名') }}
                </label>
                <div class="mt-1">
                    <input type="text" wire:model="name" id="name" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md">
                </div>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('親カテゴリー') }} ({{ __('オプション') }})
                </label>
                <div class="mt-1">
                    <select wire:model="parent_id" id="parent_id" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md">
                        <option value="">{{ __('なし (トップレベルカテゴリー)') }}</option>
                        @foreach($this->parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('parent_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('説明') }} ({{ __('オプション') }})
                </label>
                <div class="mt-1">
                    <textarea wire:model="description" id="description" rows="4" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md"></textarea>
                </div>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('categories.index') }}" 
                        class="bg-white dark:bg-gray-700 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('キャンセル') }}
                    </a>
                    <button type="submit" 
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('更新') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
