<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $this->validator($request->all())->validate();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'])) {
            return redirect('/admin');
        }

        // return back()->withErrors([
        //     'email' => 'メールアドレスとパスワードが一致していません。',
        //     'password' => 'メールアドレスとパスワードが一致していません。',
        // ])->withInput($request->only('email'));
        return redirect()->route('admin.login')->with('error', 'メールアドレスとパスワードが一致していません。')->withInput($request->only('email'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }
}
