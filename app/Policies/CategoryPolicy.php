<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * カテゴリーの管理（作成・編集・削除）権限をチェック
     */
    public function manage(User $user, Category $category = null)
    {
        // 仮実装：管理者権限を持つユーザーIDのリスト
        $adminUserIds = [1]; // ID=1のユーザーを管理者とする
        
        return in_array($user->id, $adminUserIds);
    }

    /**
     * カテゴリーの表示権限をチェック
     */
    public function view(User $user, Category $category)
    {
        // すべてのユーザーがカテゴリーを表示できる
        return true;
    }
}
