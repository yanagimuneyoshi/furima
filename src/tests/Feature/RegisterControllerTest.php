<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // CSRFトークン検証を無効化
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    /** @test */
    public function it_displays_the_register_view()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register'); // ビューが正しく表示されるかを確認
    }

    /** @test */
    public function it_registers_a_user_and_redirects_to_login()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success', '会員登録が完了しました');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /** @test */
    public function it_fails_registration_with_invalid_data()
    {
        $response = $this->from('/register')->post('/register', [
            'email' => 'invalid-email',
            'password' => 'pass', // パスワードが短すぎる
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email', 'password']); // バリデーションエラーの確認
    }
}
