<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 管理者ユーザーの作成
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'bio' => 'サイト管理者です。',
        ]);
        
        // テストユーザーの作成
        User::factory(10)->create();
        
        // カテゴリー作成
        $this->call(CategorySeeder::class);
        
        // 投稿とコメント、いいね、ブックマークの作成
        $this->call(PostSeeder::class);
    }
}
