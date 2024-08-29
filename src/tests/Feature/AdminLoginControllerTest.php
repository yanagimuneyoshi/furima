<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_displays_admin_login_form()
  {
    // 管理者ログインページが正常に表示されるかを確認
    $response = $this->get(route('admin.login'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.login');
  }

  /** @test */
  public function admin_can_login_with_correct_credentials()
  {
    // 管理者ユーザーを作成
    $admin = User::factory()->create([
      'email' => 'admin@example.com',
      'password' => bcrypt('password123'),
      'role' => 'admin'
    ]);

    // 正しい資格情報でログインを試行
    $response = $this->post(route('admin.login'), [
      'email' => 'admin@example.com',
      'password' => 'password123'
    ]);

    // 管理者ページにリダイレクトされることを確認
    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($admin);
  }

  /** @test */
  public function admin_cannot_login_with_incorrect_credentials()
  {
    // 管理者ユーザーを作成
    $admin = User::factory()->create([
      'email' => 'admin@example.com',
      'password' => bcrypt('password123'),
      'role' => 'admin'
    ]);

    // 誤った資格情報でログインを試行
    $response = $this->from(route('admin.login'))->post(route('admin.login'), [
      'email' => 'admin@example.com',
      'password' => 'wrongpassword'
    ]);

    // ログインページにリダイレクトされ、エラーメッセージが表示されることを確認
    $response->assertRedirect(route('admin.login'));
    $response->assertSessionHas('error', 'メールアドレスとパスワードが一致していません。');
    $this->assertGuest();
  }

  /** @test */
  public function non_admin_user_cannot_login_as_admin()
  {
    // 一般ユーザーを作成
    $user = User::factory()->create([
      'email' => 'user@example.com',
      'password' => bcrypt('password123'),
      'role' => 'user'  // 管理者でないユーザー
    ]);

    // 一般ユーザーが管理者としてログインを試行
    $response = $this->from(route('admin.login'))->post(route('admin.login'), [
      'email' => 'user@example.com',
      'password' => 'password123'
    ]);

    // ログインページにリダイレクトされ、エラーメッセージが表示されることを確認
    $response->assertRedirect(route('admin.login'));
    $response->assertSessionHas('error', 'メールアドレスとパスワードが一致していません。');
    $this->assertGuest();
  }
}
