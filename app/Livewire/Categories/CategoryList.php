<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class CategoryList extends Component
{
    public $categories;
    public $parentCategories;
    
    // フォーム項目
    public $name = '';
    public $description = '';
    public $parent_id = null;
    
    // UI制御
    public $showCreateForm = false;
    public $editingCategory = false;
    public $editingCategoryId = null;
    public $deletingCategoryId = null;

    protected $rules = [
        'name' => 'required|min:2|max:50',
        'description' => 'nullable|max:255',
        'parent_id' => 'nullable|exists:categories,id',
    ];

    protected $messages = [
        'name.required' => 'カテゴリー名は必須です',
        'name.min' => 'カテゴリー名は2文字以上で入力してください',
        'name.max' => 'カテゴリー名は50文字以内で入力してください',
        'description.max' => '説明は255文字以内で入力してください',
        'parent_id.exists' => '選択された親カテゴリーは存在しません',
    ];

    public function mount()
    {
        $this->refreshCategories();
    }

    public function refreshCategories()
    {
        $this->categories = Category::withCount('posts')
            ->with('parent')
            ->orderBy('name')
            ->get();
            
        $this->parentCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }

    public function createCategory()
    {
        // 管理者権限チェック
        if (!Auth::user() || !Auth::user()->can('manage', Category::class)) {
            return;
        }
        
        $this->validate();
        
        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(5),
            'description' => $this->description,
            'parent_id' => $this->parent_id ?: null,
        ]);
        
        $this->reset(['name', 'description', 'parent_id', 'showCreateForm']);
        $this->refreshCategories();
    }

    public function editCategory($categoryId)
    {
        // 管理者権限チェック
        if (!Auth::user() || !Auth::user()->can('manage', Category::class)) {
            return;
        }
        
        $category = Category::findOrFail($categoryId);
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->editingCategory = true;
    }

    public function updateCategory()
    {
        // 管理者権限チェック
        if (!Auth::user() || !Auth::user()->can('manage', Category::class)) {
            return;
        }
        
        $this->validate();
        
        $category = Category::findOrFail($this->editingCategoryId);
        $category->update([
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id ?: null,
        ]);
        
        $this->cancelEdit();
        $this->refreshCategories();
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'description', 'parent_id', 'editingCategory', 'editingCategoryId']);
    }

    public function confirmDelete($categoryId)
    {
        // 管理者権限チェック
        if (!Auth::user() || !Auth::user()->can('manage', Category::class)) {
            return;
        }
        
        $this->deletingCategoryId = $categoryId;
    }

    public function deleteCategory()
    {
        // 管理者権限チェック
        if (!Auth::user() || !Auth::user()->can('manage', Category::class)) {
            return;
        }
        
        $category = Category::findOrFail($this->deletingCategoryId);
        
        // このカテゴリーを親カテゴリーとして持つ子カテゴリーの親IDをnullに設定
        Category::where('parent_id', $category->id)
            ->update(['parent_id' => null]);
            
        // このカテゴリーに属する投稿をデフォルトカテゴリー（ID=1）に移動
        $defaultCategoryId = 1; // デフォルトカテゴリーのIDを設定
        
        // デフォルトカテゴリーが存在するか確認し、なければ作成
        $defaultCategory = Category::find($defaultCategoryId);
        if (!$defaultCategory) {
            $defaultCategory = Category::create([
                'id' => $defaultCategoryId,
                'name' => '未分類',
                'slug' => 'uncategorized',
                'description' => 'デフォルトカテゴリー',
            ]);
        }
        
        // 投稿のカテゴリーを変更
        $category->posts()->update(['category_id' => $defaultCategoryId]);
        
        // カテゴリーを削除
        $category->delete();
        
        $this->cancelDelete();
        $this->refreshCategories();
    }

    public function cancelDelete()
    {
        $this->deletingCategoryId = null;
    }

    public function render()
    {
        return view('livewire.categories.category-list');
    }
}
