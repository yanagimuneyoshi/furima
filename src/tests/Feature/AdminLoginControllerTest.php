<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminLoginControllerTest extends TestCase
{
  use RefreshDatabase;

  /**
   * テストの前に必要な設定を行うメソッド
   */
  protected function setUp(): void
  {
    parent::setUp();

    // CSRFトークン検証を無効化
    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
  }

  /** @test */
  public function it_displays_the_admin_login_form()
  {
    $response = $this->get('/admin/login');

    $response->assertStatus(200);
    $response->assertViewIs('admin.login');
  }

  /** @test */
  public function it_allows_admin_to_login_with_valid_credentials()
  {
    // 管理者ユーザーを作成
    $admin = User::factory()->create([
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'role' => 'admin'
    ]);

    // ログイン試行
    $response = $this->post('/admin/login', [
      'email' => 'admin@example.com',
      'password' => 'password',
    ]);

    // リダイレクト確認
    $response->assertRedirect('/admin');
    // 認証確認
    $this->assertAuthenticatedAs($admin);
  }

  /** @test */
  public function it_prevents_admin_login_with_invalid_credentials()
  {
    // 管理者ユーザーを作成
    $admin = User::factory()->create([
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'role' => 'admin'
    ]);

    // 不正なログイン試行
    $response = $this->post('/admin/login', [
      'email' => 'admin@example.com',
      'password' => 'wrongpassword',
    ]);

    // リダイレクト確認
    $response->assertRedirect('/admin/login');
    // エラーメッセージのセッション確認
    $response->assertSessionHas('error', 'メールアドレスとパスワードが一致していません。');
    // ゲストであることを確認
    $this->assertGuest();
  }


  /** @test */
  public function it_requires_email_and_password_for_login()
  {
    $response = $this->post('/admin/login', []);

    // バリデーションエラーの確認
    $response->assertSessionHasErrors(['email', 'password']);
  }
}
