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

    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
  }

  /**
   * 認証済みのユーザーが商品の「お気に入り」ステータスをトグルできることをテスト
   *
   * @return void
   */
  public function test_a_user_can_toggle_favorite_status()
  {

    $user = User::factory()->create();
    $item = Item::factory()->create();


    $this->actingAs($user);

    $response = $this->postJson(route('favorites.toggle', $item->id));

    $response->assertStatus(200)
      ->assertJson([
        'success' => true,
        'is_favorited' => true,
      ]);

    $this->assertDatabaseHas('favorites', [
      'user_id' => $user->id,
      'item_id' => $item->id,
    ]);

    $response = $this->postJson(route('favorites.toggle', $item->id));

    $response->assertStatus(200)
      ->assertJson([
        'success' => true,
        'is_favorited' => false,
      ]);


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

    $item = Item::factory()->create();

    $response = $this->postJson(route('favorites.toggle', $item->id));
    $response->assertStatus(200)
      ->assertJson(['success' => false]);
  }
}
