<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $parent_id = null;

    protected $rules = [
        'name' => 'required|min:2|max:255|unique:categories,name',
        'description' => 'nullable|max:500',
        'parent_id' => 'nullable|exists:categories,id',
    ];

    protected $messages = [
        'name.required' => 'カテゴリー名は必須です',
        'name.min' => 'カテゴリー名は2文字以上で入力してください',
        'name.unique' => 'このカテゴリー名は既に使用されています',
        'description.max' => '説明は500文字以内で入力してください',
    ];

    public function save()
    {
        $this->validate();

        $category = new Category([
            'name' => $this->name,
            'description' => $this->description,
            'slug' => Str::slug($this->name) . '-' . Str::random(5),
            'parent_id' => $this->parent_id,
        ]);

        $category->save();

        session()->flash('message', 'カテゴリーを作成しました。');

        return redirect()->route('categories.index');
    }

    public function getParentCategoriesProperty()
    {
        return Category::orderBy('name')->whereNull('parent_id')->get();
    }

    public function render()
    {
        return view('livewire.pages.categories.create');
    }
}
