<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_displays_the_registration_form()
  {
    $response = $this->get(route('register'));

    $response->assertStatus(200)
      ->assertViewIs('auth.register');
  }

  /** @test */
  public function it_registers_a_new_user()
  {
    $response = $this->post(route('register'), [
      'email' => 'testuser@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect('/login')
      ->assertSessionHas('success', '会員登録が完了しました');

    $this->assertDatabaseHas('users', [
      'email' => 'testuser@example.com',
    ]);

    $user = User::where('email', 'testuser@example.com')->first();
    $this->assertTrue(Hash::check('password123', $user->password));
  }

  /** @test */
  public function it_fails_registration_with_invalid_data()
  {
    $response = $this->post(route('register'), [
      'email' => 'invalid-email',
      'password' => 'short',
      'password_confirmation' => 'notmatching',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
  }
}
