<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:8|max:255',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.string' => 'メールアドレスは文字列である必要があります。',
            'email.max' => 'メールアドレスは最大255文字までです。',
            'password.required' => 'パスワードは必須です。',
            'password.string' => 'パスワードは文字列である必要があります。',
            'password.min' => 'パスワードは8文字以上である必要があります。',
            'password.max' => 'パスワードは最大255文字までです。',
        ];
    }
}
