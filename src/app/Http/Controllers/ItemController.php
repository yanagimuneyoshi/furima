<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    // public function index(Request $request)
    // {
    //     // すべての商品をカテゴリ情報とともに取得
    //     $items = Item::with('categories')->get()->unique('id');

    //     // ログインしている場合、ログインしているユーザーのお気に入りを取得
    //     if (auth()->check()) {
    //         $userId = auth()->id(); // ログインしているユーザーのIDを取得

    //         // 現在のユーザーのお気に入りアイテムのみを取得
    //         $favorites = Item::whereHas('favorites', function ($query) use ($userId) {
    //             $query->where('user_id', $userId);
    //         })
    //             ->with('categories')
    //             ->get()
    //             ->unique('id');

    //         // $favoritesの内容をログに記録
    //         Log::info('User favorites:', ['favorites' => $favorites]);
    //     } else {
    //         $favorites = collect(); // ログインしていない場合、空のコレクション
    //     }

    //     // リクエストの内容をログに記録
    //     Log::info('Request query:', $request->all());

    //     // itemsとfavoritesをBladeテンプレートに渡す
    //     return view('item', compact('items', 'favorites'));
    // }
    public function index(Request $request)
    {
        $query = $request->input('query');
        $tab = $request->input('tab', 'recommendations');

        // すべての商品をカテゴリ情報とともに取得
        $itemsQuery = Item::with('categories');

        if ($query) {
            $itemsQuery->where('title', 'LIKE', '%' . $query . '%');
        }

        $items = $itemsQuery->get()->unique('id');

        // ログインしている場合、ログインしているユーザーのお気に入りを取得
        if (auth()->check()) {
            $favoritesQuery = auth()->user()->favorites()->with('categories');

            if ($query) {
                $favoritesQuery->where('title', 'LIKE', '%' . $query . '%');
            }

            $favorites = $favoritesQuery->get()->unique('id');
        } else {
            $favorites = collect(); // ログインしていない場合、空のコレクション
        }

        // デバッグ情報をログに記録
        // \Log::info('Request query: ', $request->all());
        // \Log::info('User favorites: ', ['favorites' => $favorites]);

        // itemsとfavoritesをBladeテンプレートに渡す
        return view('item', compact('items', 'favorites'));
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
        // $item = Item::with('categories')->findOrFail($item_id);
        // return view('item_show', compact('item'));

        $item = Item::with('categories')->findOrFail($item_id);
        $isFavorited = Auth::check() ? Auth::user()->favorites()->where('item_id', $item_id)->exists() : false;
        return view('item_show', compact('item', 'isFavorited'));
    }

}
