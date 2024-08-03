<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'item_id', 'contend',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userID');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'itemID');
    }
}
