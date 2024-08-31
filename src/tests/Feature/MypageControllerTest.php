<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class MypageControllerTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
    parent::setUp();

    // ログファサードのモック設定
    \Illuminate\Support\Facades\Log::shouldReceive('info')->andReturnNull();
    \Illuminate\Support\Facades\Log::shouldReceive('warning')->andReturnNull();
    \Illuminate\Support\Facades\Log::shouldReceive('error')->andReturnNull();

    // Logファサードの channel メソッドのモック
    \Illuminate\Support\Facades\Log::shouldReceive('channel')->andReturnSelf();

  }

  /** @test */
  public function it_redirects_to_login_if_user_is_not_authenticated()
  {
    $response = $this->get(route('mypage'));

    $response->assertRedirect(route('login'));
  }

  /** @test */
  public function it_displays_mypage_if_user_is_authenticated()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('mypage'));

    $response->assertStatus(200);
    $response->assertViewIs('user.mypage');
    $response->assertSee('出品した商品');
    $response->assertSee('購入した商品');
  }

  /** @test */
  public function it_filters_sold_and_purchased_items_based_on_query()
  {
    $user = User::factory()->create();

    // 出品商品の作成
    $soldItem = Item::factory()->create([
      'title' => 'Test Sold Item',
      'price' => 1000,
      'condition' => 'New',
      'description' => 'This is a test sold item description',
      'user_id' => $user->id, // 出品商品にuser_idを設定
    ]);

    // 購入商品の作成
    $purchasedItem = Item::factory()->create([
      'title' => 'Test Purchased Item',
      'price' => 2000,
      'condition' => 'Used',
      'description' => 'This is a test purchased item description',
      'user_id' => $user->id, // 購入商品にuser_idを設定
    ]);

    // 購入履歴に追加（注文を作成する場合）
    $user->purchasedItems()->attach($purchasedItem->id, ['total_price' => $purchasedItem->price]);

    $response = $this->actingAs($user)->get(route('mypage', ['query' => 'Test']));

    $response->assertStatus(200);
    $response->assertSee('Test Sold Item');
    $response->assertSee('Test Purchased Item');
  }



}
