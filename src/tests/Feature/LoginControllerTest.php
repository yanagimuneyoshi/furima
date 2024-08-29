<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_is_displayed_correctly()
    {
        // ログインページが正しく表示されることをテスト
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 正しい資格情報でログインを試行
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // 認証された状態を確認
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_credentials()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 誤った資格情報でログインを試行
        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // ログイン画面にリダイレクトされ、エラーメッセージが表示されることを確認
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email' => __('auth.failed')]);
        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout_successfully()
    {
        // テスト用のユーザーを作成し、ログイン状態にする
        $user = User::factory()->create();
        $this->actingAs($user);

        // ログアウトを試行
        $response = $this->post('/logout');

        // ホーム画面にリダイレクトされることを確認
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
