<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'item_id', 'content', // 'content' に修正
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); // 'id' に修正
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id'); // 'id' に修正
    }
}
