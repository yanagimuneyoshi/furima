<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MypageController extends Controller
{
  public function index(Request $request)
  {

    $query = $request->input('query');

    $user = Auth::user();
    if (!$user) {
      return redirect()->route('login');
    }

    $soldItemsQuery = $user->soldItems();
    $purchasedItemsQuery = $user->purchasedItems();



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


    $soldItems = $soldItemsQuery->get();
    $purchasedItems = $purchasedItemsQuery->get();

    return view('user.mypage', [
      'soldItems' => $soldItems,
      'purchasedItems' => $purchasedItems,
    ]);
  }
}
