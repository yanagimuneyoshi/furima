<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
  public function index()
  {
    return view('auth.register'); // ビューのパスを修正
  }

  public function store(RegisterRequest $request)
  {
    $user = new User();
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect('/login')->with('success', '会員登録が完了しました');
  }
}
