<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class FavoriteControllerTest extends TestCase
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

  /**
   * 認証済みのユーザーが商品の「お気に入り」ステータスをトグルできることをテスト
   *
   * @return void
   */
  public function test_a_user_can_toggle_favorite_status()
  {
    // テスト用のユーザーとアイテムを作成
    $user = User::factory()->create();
    $item = Item::factory()->create();

    // ユーザーを認証
    $this->actingAs($user);

    // お気に入り追加のリクエストを送信
    $response = $this->postJson(route('favorites.toggle', $item->id));

    // レスポンスの検証
    $response->assertStatus(200)
      ->assertJson([
        'success' => true,
        'is_favorited' => true,
      ]);

    // お気に入りの状態を確認
    $this->assertDatabaseHas('favorites', [
      'user_id' => $user->id,
      'item_id' => $item->id,
    ]);

    // 再度お気に入り削除のリクエストを送信
    $response = $this->postJson(route('favorites.toggle', $item->id));

    // レスポンスの検証
    $response->assertStatus(200)
      ->assertJson([
        'success' => true,
        'is_favorited' => false,
      ]);

    // お気に入りの状態を確認
    $this->assertDatabaseMissing('favorites', [
      'user_id' => $user->id,
      'item_id' => $item->id,
    ]);
  }

  /**
   * 未認証のユーザーがお気に入りをトグルできないことをテスト
   *
   * @return void
   */
  public function test_a_guest_cannot_toggle_favorite_status()
  {
    // テスト用のアイテムを作成
    $item = Item::factory()->create();

    // お気に入りトグルのリクエストを未認証で送信
    $response = $this->postJson(route('favorites.toggle', $item->id));

    // レスポンスの検証
    $response->assertStatus(200)
      ->assertJson(['success' => false]);
  }
}
