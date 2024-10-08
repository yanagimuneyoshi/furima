<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
  public function search(Request $request)
  {
    $query = $request->input('query');
    $tab = $request->input('tab', 'recommendations');

    if ($tab === 'mylist') {
      if (!Auth::check()) {
        return redirect()->route('login');
      }

      $favorites = Auth::user()->favorites()
        ->where('title', 'LIKE', "%{$query}%")
        ->orWhereHas('categories', function ($q) use ($query) {
          $q->where('name', 'LIKE', "%{$query}%");
        })
        ->get()
        ->unique('id');

      return view('item', ['favorites' => $favorites, 'items' => collect(), 'activeTab' => $tab]);
    } else {
      $items = Item::where('title', 'LIKE', "%{$query}%")
        ->orWhereHas('categories', function ($q) use ($query) {
          $q->where('name', 'LIKE', "%{$query}%");
        })
        ->get();

      return view('item', ['items' => $items, 'favorites' => collect(), 'activeTab' => $tab]);
    }
  }

}
