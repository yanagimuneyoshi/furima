<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRegisterControllerTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();

    // CSRFトークン検証を無効化
    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
  }

  public function testShowRegistrationForm()
  {
    $response = $this->get(route('admin.register'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.register');
  }

  public function testAdminRegistration()
  {
    $response = $this->post(route('admin.register'), [
      'name' => 'Admin User',
      'email' => 'admin@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('admin.login'));
    $response->assertSessionHas('success', '管理者登録が完了しました。ログインしてください。');

    $this->assertDatabaseHas('users', [
      'email' => 'admin@example.com',
      'role' => 'admin',
    ]);
  }

  public function testAdminRegistrationValidationFails()
  {
    $response = $this->post(route('admin.register'), [
      'name' => '', // 名前が空の状態で送信
      'email' => 'not-an-email', // 無効なメールアドレス
      'password' => 'short', // 短すぎるパスワード
      'password_confirmation' => 'different', // パスワード確認が一致しない
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['name', 'email', 'password']);
  }
}
