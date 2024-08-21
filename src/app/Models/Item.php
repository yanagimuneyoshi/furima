<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'condition',
        'image_url',
    ];

    /**
     * 商品を所有するユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 商品が属するカテゴリ
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }

    /**
     * 商品に関連する注文
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'item_id');
    }

    /**
     * 商品に関連するお気に入り
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'item_id');
    }

    /**
     * 商品に関連するコメント
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    /**
     * 商品をお気に入りにしているユーザー
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id');
    }
}
