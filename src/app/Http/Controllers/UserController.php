<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();
    //     return view('user.mypage', compact('user'));
    // }
    public function index()
    {
        $user = Auth::user();
        if ($user === null) {
            \Log::info('Auth::user() returned null in UserController@index.');
        }
        return view('user.mypage', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_pic')) {
            // 古いプロフィール画像を削除する（必要に応じて）
            if ($user->profile_pic && \Storage::exists('public/' . $user->profile_pic)) {
                \Storage::delete('public/' . $user->profile_pic);
            }

            $profilePicPath = $request->file('profile_pic')->store('profile_pics', 'public');
            $user->profile_pic = $profilePicPath;
        }

        $user->name = $request->input('name');
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->building = $request->input('building');
        $user->save();

        return redirect()->route('mypage')->with('success', 'プロフィールが更新されました。');
    }
}
