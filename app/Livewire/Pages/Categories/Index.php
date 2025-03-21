<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $categories = Category::whereNull('parent_id')
            ->withCount(['posts', 'children'])
            ->orderBy('name')
            ->get();
            
        return view('livewire.pages.categories.index', [
            'categories' => $categories
        ]);
    }
}
