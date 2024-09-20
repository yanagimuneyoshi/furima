<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'postal_code', 'address', 'building', 'profile_pic', 'email', 'password','role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Item::class, 'favorites', 'user_id', 'item_id')->withTimestamps();;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function soldItems()
    {
        return $this->hasMany(Item::class, 'user_id');
    }

    /**
     * ユーザーが購入した商品
     */
    public function purchasedItems()
    {
        // return $this->hasManyThrough(Item::class, Order::class, 'user_id', 'id', 'id', 'item_id');
        return $this->belongsToMany(Item::class, 'orders', 'user_id', 'item_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

}
