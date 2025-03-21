<?php

use Illuminate\Support\Facades\Route;

// 認証関連ルート（Laravelのデフォルト）
require __DIR__.'/auth.php';

// ホームページと認証後のダッシュボード
Route::get('/', \App\Livewire\Pages\Home::class)->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// 投稿関連ルート
Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', \App\Livewire\Pages\Posts\Index::class)->name('index');
    Route::get('/create', \App\Livewire\Pages\Posts\Create::class)->middleware(['auth'])->name('create');
    Route::get('/{post:slug}', \App\Livewire\Pages\Posts\Show::class)->name('show');
    Route::get('/{post:slug}/edit', \App\Livewire\Pages\Posts\Edit::class)->middleware(['auth'])->name('edit');
});

// カテゴリー関連ルート
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', \App\Livewire\Pages\Categories\Index::class)->name('index');
    Route::get('/create', \App\Livewire\Pages\Categories\Create::class)->middleware(['auth'])->name('create');
    Route::get('/{category:slug}/edit', \App\Livewire\Pages\Categories\Edit::class)->middleware(['auth'])->name('edit');
    Route::get('/{slug}', \App\Livewire\Pages\Categories\Show::class)->name('show');
});

// ブックマーク関連ルート
Route::get('/bookmarks', \App\Livewire\Pages\Bookmarks\Index::class)->middleware(['auth'])->name('bookmarks.index');

// プロフィール関連ルート
Route::prefix('profile')->name('profile.')->middleware(['auth'])->group(function () {
    Route::get('/', \App\Livewire\Pages\Profile\Show::class)->name('show');
    Route::get('/{userId}', \App\Livewire\Pages\Profile\Show::class)->name('show.user');
    Route::get('/edit', \App\Livewire\Pages\Profile\Edit::class)->middleware(['auth'])->name('edit');
});

// 設定関連ルート
Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/profile', function () {
        return view('livewire.settings.profile');
    })->name('profile');
    
    Route::get('/password', function () {
        return view('livewire.settings.password');
    })->name('password');
    
    Route::get('/appearance', function () {
        return view('livewire.settings.appearance');
    })->name('appearance');
});
