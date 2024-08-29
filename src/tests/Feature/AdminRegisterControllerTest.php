<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRegisterControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_displays_admin_registration_form()
  {
    // 管理者登録ページが正しく表示されることをテスト
    $response = $this->get(route('admin.register'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.register');
  }

  /** @test */
  public function it_registers_a_new_admin_with_valid_data()
  {
    // 有効なデータで管理者を登録する
    $response = $this->post(route('admin.register'), [
      'name' => 'Test Admin',
      'email' => 'admin@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    // データベースに新しい管理者が保存されていることを確認
    $response->assertRedirect(route('admin.login'));
    $this->assertDatabaseHas('users', [
      'email' => 'admin@example.com',
      'role' => 'admin'
    ]);
  }

  /** @test */
  public function it_does_not_register_admin_with_invalid_data()
  {
    // 無効なデータで管理者を登録しようとする
    $response = $this->from(route('admin.register'))->post(route('admin.register'), [
      'name' => '',  // 名前が空
      'email' => 'not-an-email',  // 無効なメール形式
      'password' => 'short',  // 短すぎるパスワード
      'password_confirmation' => 'different',  // パスワード確認が一致しない
    ]);

    // エラーメッセージが表示され、登録ページにリダイレクトされることを確認
    $response->assertRedirect(route('admin.register'));
    $response->assertSessionHasErrors(['name', 'email', 'password']);
    $this->assertDatabaseMissing('users', ['email' => 'not-an-email']);
  }

  /** @test */
  public function it_does_not_register_admin_with_existing_email()
  {
    // 既存のユーザーを作成
    User::factory()->create(['email' => 'admin@example.com']);

    // 既存のメールアドレスで管理者を登録しようとする
    $response = $this->from(route('admin.register'))->post(route('admin.register'), [
      'name' => 'Another Admin',
      'email' => 'admin@example.com',  // 既に存在するメール
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    // エラーメッセージが表示され、登録ページにリダイレクトされることを確認
    $response->assertRedirect(route('admin.register'));
    $response->assertSessionHasErrors(['email']);
    $this->assertDatabaseCount('users', 1); // ユーザー数が1のままであることを確認
  }
}
