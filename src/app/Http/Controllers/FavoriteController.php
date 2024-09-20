<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class FavoriteController extends Controller
{
  public function toggle(Item $item)
  {
    if (Auth::check()) {
      $user = Auth::user();
      $isFavorited = $user->favorites()->where('item_id', $item->id)->exists();

      if ($isFavorited) {
        $user->favorites()->detach($item->id);
      } else {
        $user->favorites()->attach($item->id);
      }

      return response()->json([
        'success' => true,
        'is_favorited' => !$isFavorited,
        'favorites_count' => $item->favoritedByUsers()->count()
      ]);
    }

    return response()->json(['success' => false]);
  }
}

