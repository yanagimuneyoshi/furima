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


  public function editAddress($item_id)
  {
    $item = Item::findOrFail($item_id);
    return view('address_edit', compact('item'));
  }

  public function buy(Request $request, $id)
  {
    if (auth()->check()) {
      return redirect()->route('purchase', ['item_id' => $id]);
    } else {
      return redirect()->route('login');
    }
  }

  public function updateAddress(Request $request, $item_id)
  {
    $request->validate([
      'postal_code' => 'required|string|max:10',
      'address' => 'required|string|max:255',
      'building' => 'nullable|string|max:255',
    ]);

    $item = Item::findOrFail($item_id);

    // ユーザーの住所情報を更新する処理
    $user = auth()->user();
    $user->postal_code = $request->input('postal_code');
    $user->address = $request->input('address');
    $user->building = $request->input('building');
    $user->save();

    return redirect()->route('purchase', ['item_id' => $item_id])->with('success', '住所が更新されました。');
  }
}
