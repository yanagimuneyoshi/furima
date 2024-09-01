<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // CSRFトークン検証を無効化
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    /** @test */
    public function it_shows_the_user_mypage()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('mypage'));

        $response->assertStatus(200);
        $response->assertViewIs('user.mypage');
        $response->assertSee($user->name);
    }

    /** @test */
    public function it_updates_the_user_profile()
    {
        $user = User::factory()->create();

        // プロフィール更新用のデータ
        $data = [
            'name' => 'Updated Name',
            'postal_code' => '123-4567',
            'address' => 'Updated Address',
            'building' => 'Updated Building',
        ];

        $response = $this->actingAs($user)->post(route('profile.update'), $data);

        $response->assertRedirect(route('mypage'));
        $response->assertSessionHas('success', 'プロフィールが更新されました。');

        // データベースに更新が反映されているか確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'postal_code' => '123-4567',
            'address' => 'Updated Address',
            'building' => 'Updated Building',
        ]);
    }

    /** @test */
    public function it_fails_to_update_with_invalid_data()
    {
        $user = User::factory()->create();

        // 無効なデータ（バリデーションエラーが発生するはず）
        $data = [
            'name' => '',  // 必須フィールドを空にする
            'postal_code' => 'invalid_postal_code',  // 無効な郵便番号
            'address' => '',  // 必須フィールドを空にする
        ];

        $response = $this->actingAs($user)->post(route('profile.update'), $data);

        // バリデーションエラーメッセージがセッションに存在するか確認
        $response->assertSessionHasErrors(['name', 'postal_code', 'address']);
    }



    /** @test */
    public function it_updates_user_profile_without_image()
    {
        $user = User::factory()->create();

        // プロフィール更新用のデータ
        $data = [
            'name' => 'Updated Name',
            'postal_code' => '123-4567',
            'address' => 'Updated Address',
            'building' => 'Updated Building',
        ];

        $response = $this->actingAs($user)->post(route('profile.update'), $data);

        $response->assertRedirect(route('mypage'));
        $response->assertSessionHas('success', 'プロフィールが更新されました。');

        // データベースに更新が反映されているか確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'postal_code' => '123-4567',
            'address' => 'Updated Address',
            'building' => 'Updated Building',
        ]);
    }

}
