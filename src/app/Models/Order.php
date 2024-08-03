<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'user_id', 'total_price',
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
