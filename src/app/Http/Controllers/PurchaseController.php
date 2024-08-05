<?php


namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
  public function show($item_id)
  {
    $item = Item::findOrFail($item_id);
    return view('purchase', compact('item'));
  }

  public function purchase($item_id)
  {
    // 購入ページの表示処理
    $item = Item::findOrFail($item_id);
    return view('purchase', compact('item'));
  }

  public function buy(Request $request, $id)
  {
    if (auth()->check()) {
      return redirect()->route('purchase', ['item_id' => $id]);
    } else {
      return redirect()->route('login');
    }
  }
}
