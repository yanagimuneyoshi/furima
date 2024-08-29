<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_toggles_favorite_status_for_logged_in_user()
  {
    // テスト用のユーザーとアイテムを作成
    $user = User::factory()->create();
    $item = Item::factory()->create();

    // ユーザーをログイン状態に設定
    $this->actingAs($user);

    // アイテムをお気に入りに追加するリクエストを送信
    $response = $this->post(route('favorites.toggle', $item->id));

    // リクエストが成功したことを確認
    $response->assertStatus(200);
    $response->assertJson(['success' => true, 'is_favorited' => true]);

    // お気に入りに追加されたことを確認
    $this->assertTrue($user->favorites()->where('item_id', $item->id)->exists());

    // 同じリクエストを送信して、お気に入りが削除されることを確認
    $response = $this->post(route('favorites.toggle', $item->id));
    $response->assertStatus(200);
    $response->assertJson(['success' => true, 'is_favorited' => false]);

    // お気に入りから削除されたことを確認
    $this->assertFalse($user->favorites()->where('item_id', $item->id)->exists());
  }

  /** @test */
  public function it_returns_error_when_user_is_not_logged_in()
  {
    // テスト用のアイテムを作成
    $item = Item::factory()->create();

    // 認証されていない状態でリクエストを送信
    $response = $this->post(route('favorites.toggle', $item->id));

    // リクエストが失敗したことを確認
    $response->assertStatus(200);
    $response->assertJson(['success' => false]);
  }
}
