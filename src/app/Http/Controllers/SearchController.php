<?php


// app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
  public function search(Request $request)
  {
    // $query = $request->input('query');
    // $items = Item::where('title', 'LIKE', "%{$query}%")
    // ->orWhere('description', 'LIKE', "%{$query}%")
    // ->get();

    // if (Auth::check()) {
    //   $user = Auth::user();
    //   $favorites = $user->favorites()->where(function ($q) use ($query) {
    //     $q->where('title', 'LIKE', "%{$query}%")
    //     ->orWhere('description', 'LIKE', "%{$query}%");
    //   })->get();

    //   return view('item', compact('items', 'favorites'));
    // }

    // return view('item', compact('items'));
    //   $searchTerm = $request->input('query');

    //   $query = Item::query();

    //   if ($searchTerm) {
    //     $query->where(function ($q) use ($searchTerm) {
    //       $q->where('title', 'LIKE', '%' . $searchTerm . '%')
    //       ->orWhereHas('categories', function ($q) use ($searchTerm) {
    //         $q->where('name', 'LIKE', '%' . $searchTerm . '%');
    //       });
    //     });
    //   }

    //   $items = $query->get();

    //   // お気に入り検索用
    //   $favorites = Auth::check() ? Auth::user()->favorites : collect();

    //   return view('item', compact('items', 'favorites'));
    // }

    // {
    //   $searchTerm = $request->input('query');
    //   $query = Item::query();

    //   if ($searchTerm) {
    //     $query->where(function ($q) use ($searchTerm) {
    //       $q->where('title', 'LIKE', '%' . $searchTerm . '%')
    //         ->orWhereHas('categories', function ($q) use ($searchTerm) {
    //           $q->where('name', 'LIKE', '%' . $searchTerm . '%');
    //         });
    //     });
    //   }

    //   // タブに応じた検索処理
    //   if ($request->input('tab') === 'mylist') {
    //     if (Auth::check()) {
    //       $query->whereHas('favorites', function ($q) {
    //         $q->where('user_id', Auth::id());
    //       });
    //     } else {
    //       return redirect()->route('login');
    //     }
    //   }

    //   $items = $query->get();

    //   // お気に入り検索用
    //   $favorites = Auth::check() ? Auth::user()->favorites : collect();

    //   return view('item', compact('items', 'favorites', 'searchTerm'));
    // }

    // }
    $query = $request->input('query');
    $tab = $request->input('tab', 'recommendations');

    if ($tab === 'mylist' && Auth::check()) {
      $favorites = Auth::user()->favorites()
        ->where('title', 'LIKE',
          "%{$query}%"
        )
        ->orWhereHas('categories', function ($q) use ($query) {
          $q->where('name', 'LIKE', "%{$query}%");
        })
        ->get();

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