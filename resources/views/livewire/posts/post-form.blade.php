<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">{{ $editing ? '投稿の編集' : '新規投稿' }}</h2>
    
    <form wire:submit.prevent="save" class="space-y-6">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">タイトル</label>
            <input wire:model="post.title" type="text" id="title" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" placeholder="投稿のタイトルを入力してください">
            @error('post.title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">カテゴリー</label>
            <select wire:model="post.category_id" id="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="">カテゴリーを選択</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('post.category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">内容</label>
            <textarea wire:model="post.content" id="content" rows="8" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" placeholder="投稿の内容を入力してください"></textarea>
            @error('post.content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">タグ</label>
            <input wire:model="tagString" type="text" id="tags" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" placeholder="タグをカンマ区切りで入力してください">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">タグはカンマ（,）で区切って入力してください</p>
            @error('tagString') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ステータス</label>
            <select wire:model="post.status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="published">公開</option>
                <option value="draft">下書き</option>
            </select>
            @error('post.status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                キャンセル
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ $editing ? '更新' : '投稿' }}
            </button>
        </div>
    </form>
</div>
