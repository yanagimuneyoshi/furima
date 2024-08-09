<?php


// app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class SearchController extends Controller
{
  public function search(Request $request)
  {
    $query = $request->input('query');

    $items = Item::where('title', 'LIKE', "%{$query}%")
      ->orWhereHas('categories', function ($q) use ($query) {
        $q->where('name', 'LIKE', "%{$query}%");
      })
      ->get();

    return view('item', compact('items'));
  }
}
