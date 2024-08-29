<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register()
    {
        // テストデータを作成
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // POSTリクエストをシミュレートしてユーザーを登録
        $response = $this->post('/register', $data);

        // データベースにユーザーが作成されたかを確認
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);

        // リダイレクトが成功したかを確認
        $response->assertRedirect('/login');
    }
}
