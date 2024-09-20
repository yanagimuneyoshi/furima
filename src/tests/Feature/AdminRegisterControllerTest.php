<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRegisterControllerTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();

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
      'name' => '',
      'email' => 'not-an-email',
      'password' => 'short',
      'password_confirmation' => 'different',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['name', 'email', 'password']);
  }
}
