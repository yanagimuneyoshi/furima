<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_user_mypage()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create();

        // ユーザーとして認証済み状態でリクエストを送信
        $this->actingAs($user);

        // ログ出力で認証状態を確認
        \Log::info('Testing user object:', [$user]);

        $response = $this->get(route('mypage'));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // ビューが正しいかどうかを確認し、ユーザーオブジェクトが渡されているかを確認
        $response->assertViewIs('user.mypage')
        ->assertViewHas('user', function ($viewUser) use ($user) {
            if ($viewUser === null) {
                \Log::info('User object is null in view.');
                return false;
            }
            return $viewUser->id === $user->id;
        });
    }




    /** @test */
    public function it_shows_the_profile_edit_page()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create();

        // ユーザーとして認証済み状態でリクエストを送信
        $response = $this->actingAs($user)->get(route('profile.edit'));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // ビューが正しいかどうかを確認
        $response->assertViewIs('profile') // ここを変更
        ->assertViewHas('user', function ($viewUser) use ($user) {
            // $viewUser が null でないこと、かつ $viewUser が User インスタンスであることを確認
            return $viewUser !== null && $viewUser->id === $user->id;
        });
    }



    /** @test */
    public function it_updates_the_user_profile()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create();

        // 更新データを準備
        $updatedData = [
            'name' => 'Updated Name',
            'postal_code' => '1234567',
            'address' => 'New Address',
            'building' => 'New Building',
            'profile_pic' => null, // 画像のアップロードはテストしない場合
        ];

        // ユーザーとして認証済み状態でリクエストを送信
        $response = $this->actingAs($user)->post(route('profile.update'), $updatedData);

        // ユーザーのデータが更新されていることを確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'postal_code' => '1234567',
            'address' => 'New Address',
            'building' => 'New Building',
        ]);

        // リダイレクトが成功したことを確認
        $response->assertRedirect(route('mypage'));
    }
}
