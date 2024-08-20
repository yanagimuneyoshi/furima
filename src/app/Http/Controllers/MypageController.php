<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
  public function index()
  {
    // ログインしているユーザーのIDを取得
    $userId = Auth::id();

    // ユーザーが出品した商品を取得
    $soldItems = Item::where('user_id', $userId)->get();

    // ユーザーが購入した商品を取得（ここでは `purchases` テーブルが存在し、`Item` モデルとリレーションがあることを前提）
    $purchasedItems = Item::whereHas('orders', function ($query) use ($userId) {
      $query->where('user_id', $userId);
    })->get();

    return view('user/mypage', compact('soldItems', 'purchasedItems'));
  }
}
