<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\Order;

class PurchaseController extends Controller
{
  public function show($item_id)
  {
    $item = Item::findOrFail($item_id);
    return view('purchase', compact('item'));
  }

  public function purchase($item_id)
  {
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

    $user = auth()->user();
    $user->postal_code = $request->input('postal_code');
    $user->address = $request->input('address');
    $user->building = $request->input('building');
    $user->save();

    return redirect()->route('purchase', ['item_id' => $item_id])->with('success', '住所が更新されました。');
  }

  public function charge(Request $request)
  {
    Stripe::setApiKey('sk_test_51PnN81KUcLKzkipSqUeSEdfsYzteisrRIDF7iF6jxmcP1T1F2LETGxKjX2YGXZxJLA6BDj2IGmPqqiAOKABWXld900m1nVUrHb');

    try {
      if ($request->input('payment_method') === 'クレジットカード') {
        $charge = Charge::create([
          'amount' => $request->input('amount'),
          'currency' => 'jpy',
          'source' => $request->input('token'),
          'description' => '商品購入: ' . $request->input('item_id'),
        ]);

        $correctedAmount = $request->input('amount') / 100;

        // クレジットカードの場合のみ金額を修正して保存
        $this->saveOrder($request->input('item_id'), $request->user()->id, $correctedAmount);
      } else {
        // クレジットカード以外の支払い方法の場合
        $correctedAmount = $request->input('amount') / 100;

        // コンビニ払い、銀行振込の場合も修正した金額を保存
        $this->saveOrder($request->input('item_id'), $request->user()->id, $correctedAmount);

      }

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
  }

  private function saveOrder($item_id, $user_id, $amount)
  {
    Order::create([
      'item_id' => $item_id,
      'user_id' => $user_id,
      'total_price' => $amount,
    ]);
  }

  public function savePurchaseData(Request $request)
  {
    $order = Order::create([
      'item_id' => $request->item_id,
      'user_id' => auth()->id(),
      'total_price' => $request->input('amount') / 100, // コンビニ払い、銀行振込の場合も金額を修正
    ]);

    return response()->json(['success' => true]);
  }
}
