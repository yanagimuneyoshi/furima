<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email|string|max:191',
            'password' => 'required|string|min:8|max:191',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'email.string' => 'メールアドレスは文字列である必要があります。',
            'email.max' => 'メールアドレスは最大191文字までです。',
            'password.required' => 'パスワードは必須です。',
            'password.string' => 'パスワードは文字列である必要があります。',
            'password.min' => 'パスワードは8文字以上である必要があります。',
            'password.max' => 'パスワードは最大191文字までです。',
        ];
    }
}
