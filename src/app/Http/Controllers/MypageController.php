<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->input('query');

    // 出品した商品と購入した商品のクエリを取得
    $soldItemsQuery = Auth::user()->soldItems();
    $purchasedItemsQuery = Auth::user()->purchasedItems();

    // クエリが存在する場合、フィルタリング
    if ($query) {
      $soldItemsQuery->where(function ($q) use ($query) {
        $q->where('title', 'LIKE', "%{$query}%")
          ->orWhereHas('categories', function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
          });
      });

      $purchasedItemsQuery->where(function ($q) use ($query) {
        $q->where('title', 'LIKE', "%{$query}%")
          ->orWhereHas('categories', function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
          });
      });
    }

    // 検索結果を取得
    $soldItems = $soldItemsQuery->get();
    $purchasedItems = $purchasedItemsQuery->get();

    return view('user/mypage', [
      'soldItems' => $soldItems,
      'purchasedItems' => $purchasedItems,
    ]);
  }
}
