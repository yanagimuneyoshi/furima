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
    $admin = User::factory()->create([
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'role' => 'admin'
    ]);

    $response = $this->post('/admin/login', [
      'email' => 'admin@example.com',
      'password' => 'password',
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($admin);
  }

  /** @test */
  public function it_prevents_admin_login_with_invalid_credentials()
  {
    $admin = User::factory()->create([
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'role' => 'admin'
    ]);


    $response = $this->post('/admin/login', [
      'email' => 'admin@example.com',
      'password' => 'wrongpassword',
    ]);

    $response->assertRedirect('/admin/login');
    $response->assertSessionHas('error', 'メールアドレスとパスワードが一致していません。');
    $this->assertGuest();
  }


  /** @test */
  public function it_requires_email_and_password_for_login()
  {
    $response = $this->post('/admin/login', []);

    $response->assertSessionHasErrors(['email', 'password']);
  }
}
