<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class Edit extends Component
{
    public Category $category;
    public $name;
    public $description;
    public $parent_id;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:2|max:255|unique:categories,name,' . $this->category->id,
            'description' => 'nullable|max:500',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    protected $messages = [
        'name.required' => 'カテゴリー名は必須です',
        'name.min' => 'カテゴリー名は2文字以上で入力してください',
        'name.unique' => 'このカテゴリー名は既に使用されています',
        'description.max' => '説明は500文字以内で入力してください',
    ];

    public function save()
    {
        $this->validate();

        $this->category->update([
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
        ]);

        session()->flash('message', 'カテゴリーを更新しました。');

        return redirect()->route('categories.index');
    }

    public function getParentCategoriesProperty()
    {
        return Category::orderBy('name')
            ->whereNull('parent_id')
            ->where('id', '!=', $this->category->id)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.categories.edit');
    }
}
