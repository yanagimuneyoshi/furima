<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class FavoriteController extends Controller
{
  public function toggle(Item $item)
  {
    $user = Auth::user();
    if ($user->favorites()->where('item_id', $item->id)->exists()) {
      $user->favorites()->detach($item->id);
      $isFavorited = false;
    } else {
      $user->favorites()->attach($item->id);
      $isFavorited = true;
    }

    return response()->json([
      'success' => true,
      'is_favorited' => $isFavorited,
      'favorites_count' => $item->favoritedByUsers()->count(),
    ]);
  }
}
