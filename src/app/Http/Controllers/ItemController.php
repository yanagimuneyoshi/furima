<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // トップページの処理
        return view('item');
    }

    public function create()
    {
        return view('sell');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $item = new Item();
        $item->user_id = Auth::id();
        if ($request->hasFile('item_image')) {
            $item->image_url = $request->file('item_image')->store('item_images', 'public');
        }
        $item->category = $request->input('category');
        $item->condition = $request->input('condition');
        $item->title = $request->input('title');
        $item->description = $request->input('description');
        $item->price = $request->input('price');
        $item->save();

        return redirect()->route('mypage')->with('success', '商品が出品されました。');
    }
}
