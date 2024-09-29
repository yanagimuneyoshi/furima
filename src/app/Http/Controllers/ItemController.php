<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $tab = $request->input('tab', 'recommendations');

        $itemsQuery = Item::with('categories');

        if ($query) {
            $itemsQuery->where('title', 'LIKE', '%' . $query . '%');
        }

        $items = $itemsQuery->get()->unique('id');

        if ($tab === 'mylist' && !auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->check()) {
            $favoritesQuery = auth()->user()->favorites()->with('categories');

            if ($query) {
                $favoritesQuery->where('title', 'LIKE', '%' . $query . '%');
            }

            $favorites = $favoritesQuery->get()->unique('id');
        } else {
            $favorites = collect();
        }

        return view('item', compact('items', 'favorites'));
    }

    public function checkAuth(): JsonResponse
    {
        if (Auth::check()) {
            return response()->json(['authenticated' => true]);
        } else {
            return response()->json(['authenticated' => false]);
        }
    }


    public function create()
    {
        return view('sell');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $item = new Item();
        $item->user_id = auth()->id();
        if ($request->hasFile('item_image')) {
            $item->image_url = $request->file('item_image')->store('item_images', 'public');
        }
        $item->condition = $request->input('condition');
        $item->title = $request->input('title');
        $item->description = $request->input('description');
        $item->price = $request->input('price');
        $item->save();

        $categories = explode(',', $request->input('categories'));
        foreach ($categories as $categoryName) {
            $category = Category::firstOrCreate(['name' => trim($categoryName), 'user_id' => auth()->id()]);
            $item->categories()->attach($category->id);
        }

        return redirect()->route('mypage')->with('success', '商品が出品されました。');
    }


    public function show($item_id)
    {

        $item = Item::with('categories')->findOrFail($item_id);
        $isFavorited = Auth::check() ? Auth::user()->favorites()->where('item_id', $item_id)->exists() : false;
        return view('item_show', compact('item', 'isFavorited'));
    }

}
