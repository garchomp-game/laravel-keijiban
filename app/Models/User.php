<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * ユーザーの投稿を取得
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * ユーザーのコメントを取得
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * ユーザーのいいねを取得
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * ユーザーのブックマークを取得
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * ユーザーがいいねした投稿を取得
     */
    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes');
    }

    /**
     * ユーザーがブックマークした投稿を取得
     */
    public function bookmarkedPosts()
    {
        return $this->belongsToMany(Post::class, 'bookmarks');
    }

    /**
     * ユーザーのイニシャルを取得
     */
    public function initials()
    {
        if (empty($this->name)) {
            return 'U';
        }
        
        $parts = explode(' ', trim($this->name));
        $initials = '';
        
        foreach ($parts as $part) {
            if (!empty($part)) {
                $initials .= mb_substr($part, 0, 1);
            }
        }
        
        return mb_strtoupper(mb_substr($initials, 0, 2) ?: 'U');
    }
}
