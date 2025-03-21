<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">カテゴリー一覧</h2>
        
        @if (auth()->user() && auth()->user()->can('manage', App\Models.Category::class))
            <button 
                wire:click="$toggle('showCreateForm')" 
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-colors duration-200"
            >
                {{ $showCreateForm ? 'キャンセル' : '新規カテゴリー' }}
            </button>
        @endif
    </div>
    
    @if ($showCreateForm)
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <form wire:submit.prevent="createCategory">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">カテゴリー名</label>
                        <input wire:model="name" type="text" id="name" class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">説明</label>
                        <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300"></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">親カテゴリー</label>
                        <select wire:model="parent_id" id="parent_id" class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                            <option value="">親カテゴリーなし</option>
                            @foreach ($parentCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-colors duration-200">
                            作成
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">カテゴリー名</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">投稿数</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">説明</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                @if ($category->parent)
                                    <span class="text-gray-500 dark:text-gray-400">{{ $category->parent->name }} &gt; </span>
                                @endif
                                {{ $category->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $category->posts_count }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $category->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline ml-2">表示</a>
                            
                            @if (auth()->user() && auth()->user()->can('manage', App\Models.Category::class))
                                <button wire:click="editCategory({{ $category->id }})" class="text-indigo-600 dark:text-indigo-400 hover:underline ml-2">編集</button>
                                <button wire:click="confirmDelete({{ $category->id }})" class="text-red-600 dark:text-red-400 hover:underline ml-2">削除</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                
                @if ($categories->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            カテゴリーがありません
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- 編集モーダル -->
    @if ($editingCategory)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">カテゴリーの編集</h3>
                
                <form wire:submit.prevent="updateCategory">
                    <div class="space-y-4">
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">カテゴリー名</label>
                            <input wire:model="name" type="text" id="edit_name" class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="edit_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">説明</label>
                            <textarea wire:model="description" id="edit_description" rows="3" class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="edit_parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">親カテゴリー</label>
                            <select wire:model="parent_id" id="edit_parent_id" class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                <option value="">親カテゴリーなし</option>
                                @foreach ($parentCategories as $category)
                                    @if ($category->id != $editingCategoryId)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200">
                                キャンセル
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition-colors duration-200">
                                更新
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    
    <!-- 削除確認モーダル -->
    @if ($deletingCategoryId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">カテゴリーの削除</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">このカテゴリーを削除してもよろしいですか？関連する投稿はすべて未分類になります。</p>
                
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200">
                        キャンセル
                    </button>
                    <button wire:click="deleteCategory" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500 transition-colors duration-200">
                        削除
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
