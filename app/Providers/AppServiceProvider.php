<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // すべてのビューでカテゴリー一覧を使用可能にする
        View::composer('*', function ($view) {
            try {
                $topCategories = Category::withCount('posts')
                    ->whereNull('parent_id')
                    ->orderBy('name')
                    ->take(5)
                    ->get();
                
                $view->with('topCategories', $topCategories);
            } catch (\Exception $e) {
                // データベース接続前などでエラーが発生した場合は空の配列を渡す
                $view->with('topCategories', collect([]));
            }
        });
    }
}
