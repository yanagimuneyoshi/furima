<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'userID';  // これを追加
    public $incrementing = false;      // これを追加（必要に応じて）
    protected $keyType = 'string';     // これを追加（必要に応じて）

    protected $fillable = [
        'name', 'postal_code', 'address', 'building', 'profile_pic', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'user_id', 'userID');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'userID');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id', 'userID');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'userID');
    }
}
