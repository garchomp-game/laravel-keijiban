<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => '一般',
                'description' => '一般的な話題',
                'children' => [
                    ['name' => 'お知らせ', 'description' => '管理者からのお知らせ'],
                    ['name' => '雑談', 'description' => '雑談や交流'],
                    ['name' => '自己紹介', 'description' => '自己紹介をする場所'],
                ]
            ],
            [
                'name' => '技術',
                'description' => '技術関連の話題',
                'children' => [
                    ['name' => 'プログラミング', 'description' => 'プログラミング全般'],
                    ['name' => 'ウェブ開発', 'description' => 'ウェブ開発関連'],
                    ['name' => 'モバイル開発', 'description' => 'モバイルアプリ開発'],
                    ['name' => 'デザイン', 'description' => 'UI/UXデザイン'],
                ]
            ],
            [
                'name' => '質問',
                'description' => '質問と回答',
                'children' => [
                    ['name' => '技術的質問', 'description' => '技術的な質問'],
                    ['name' => '使い方', 'description' => '使い方についての質問'],
                ]
            ],
            [
                'name' => '趣味',
                'description' => '趣味や娯楽の話題',
                'children' => [
                    ['name' => 'ゲーム', 'description' => 'ゲーム関連の話題'],
                    ['name' => '音楽', 'description' => '音楽関連の話題'],
                    ['name' => '映画', 'description' => '映画関連の話題'],
                    ['name' => '読書', 'description' => '読書関連の話題'],
                ]
            ],
            [
                'name' => 'その他',
                'description' => 'その他の話題',
                'children' => []
            ],
        ];
        
        foreach ($categories as $category) {
            $children = $category['children'] ?? [];
            unset($category['children']);
            
            $category['slug'] = Str::slug($category['name']);
            $parent = Category::create($category);
            
            foreach ($children as $child) {
                $child['slug'] = Str::slug($child['name']);
                $child['parent_id'] = $parent->id;
                Category::create($child);
            }
        }
    }
}
