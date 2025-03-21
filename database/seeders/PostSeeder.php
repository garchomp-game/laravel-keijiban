<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // タグの作成
        $tags = [
            'PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'CSS', 'HTML',
            'バックエンド', 'フロントエンド', 'デザイン', 'UX', 'API',
            'データベース', 'MySQL', 'PostgreSQL', 'MongoDB', 'Redis',
            'DevOps', 'AWS', 'Docker', 'CI/CD', 'Git',
            '初心者', '質問', 'チュートリアル', 'ヒント', 'トラブルシューティング'
        ];
        
        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }
        
        $allTags = Tag::all();
        $users = User::all();
        $categories = Category::all();
        
        // 各ユーザーに対して投稿を作成
        foreach ($users as $user) {
            // 各ユーザーが3〜8つの投稿を作成
            $postCount = rand(3, 8);
            
            for ($i = 0; $i < $postCount; $i++) {
                $title = fake()->sentence();
                $post = Post::create([
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . uniqid(),
                    'content' => fake()->paragraphs(rand(3, 8), true),
                    'user_id' => $user->id,
                    'category_id' => $categories->random()->id,
                    'status' => 'published',
                    'view_count' => rand(0, 500),
                    'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                ]);
                
                // タグの付与（1〜5個）
                $post->tags()->attach($allTags->random(rand(1, 5))->pluck('id')->toArray());
                
                // コメントの作成（0〜10個）
                $commentCount = rand(0, 10);
                
                for ($j = 0; $j < $commentCount; $j++) {
                    $comment = Comment::create([
                        'content' => fake()->paragraph(),
                        'user_id' => $users->random()->id,
                        'post_id' => $post->id,
                    ]);
                    
                    // 返信コメント（20%の確率で）
                    if (rand(1, 5) === 1) {
                        Comment::create([
                            'content' => fake()->paragraph(),
                            'user_id' => $users->random()->id,
                            'post_id' => $post->id,
                            'parent_id' => $comment->id,
                        ]);
                    }
                }
                
                // いいねの追加（0〜ユーザー数の半分）
                $likeCount = rand(0, floor($users->count() / 2));
                $likeUsers = $users->random($likeCount);
                
                foreach ($likeUsers as $likeUser) {
                    Like::create([
                        'user_id' => $likeUser->id,
                        'post_id' => $post->id,
                    ]);
                }
                
                // ブックマークの追加（0〜ユーザー数の1/4）
                $bookmarkCount = rand(0, floor($users->count() / 4));
                $bookmarkUsers = $users->random($bookmarkCount);
                
                foreach ($bookmarkUsers as $bookmarkUser) {
                    $post->bookmarks()->create([
                        'user_id' => $bookmarkUser->id,
                    ]);
                }
            }
        }
    }
}
