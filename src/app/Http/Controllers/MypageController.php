<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;  // Logファサードを追加

class MypageController extends Controller
{
  public function index(Request $request)
  {
    // ログの開始
    Log::info('MypageController@index: Start');

    // クエリパラメータの取得
    $query = $request->input('query');
    Log::info('MypageController@index: Query received', ['query' => $query]);

    // 現在のユーザーを取得し、ユーザーがログインしているかを確認
    $user = Auth::user();
    if (!$user) {
      Log::warning('MypageController@index: User not authenticated');
      return redirect()->route('login');
    }

    // 出品した商品と購入した商品のクエリを取得
    $soldItemsQuery = $user->soldItems();
    $purchasedItemsQuery = $user->purchasedItems();

    // クエリのログを出力
    Log::info('MypageController@index: Sold Items Query', [
      'query' => $soldItemsQuery->toSql(),
      'bindings' => $soldItemsQuery->getBindings()
    ]);

    Log::info('MypageController@index: Purchased Items Query', [
      'query' => $purchasedItemsQuery->toSql(),
      'bindings' => $purchasedItemsQuery->getBindings()
    ]);

    // クエリが存在する場合、商品をフィルタリング
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

    // クエリの結果を取得
    $soldItems = $soldItemsQuery->get();
    $purchasedItems = $purchasedItemsQuery->get();

    // 取得結果をログに出力
    Log::info('MypageController@index: Sold Items Retrieved', ['soldItems' => $soldItems->toArray()]);
    Log::info('MypageController@index: Purchased Items Retrieved', ['purchasedItems' => $purchasedItems->toArray()]);

    // ビューを返す
    return view('user.mypage', [
      'soldItems' => $soldItems,
      'purchasedItems' => $purchasedItems,
    ]);
  }
}
