<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Mockery;
use Stripe\Stripe;
use Stripe\Charge;

class PurchaseControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_displays_the_purchase_page()
  {
    // 認証ユーザーを作成し、ログイン
    $user = User::factory()->create();
    $this->actingAs($user);

    // テスト用のアイテムを作成
    $item = Item::factory()->create();

    // 購入ページの表示をテスト
    $response = $this->get(route('purchase', ['item_id' => $item->id]));

    $response->assertStatus(200)
      ->assertViewIs('purchase')
      ->assertViewHas('item', $item);
  }

  /** @test */
  public function it_updates_the_address()
  {
    $user = User::factory()->create(); // ユーザーを作成
    $this->actingAs($user); // 作成したユーザーとして認証

    $item = Item::factory()->create();

    // 住所更新のテスト
    $this->post(route('updateAddress', ['item_id' => $item->id]), [
      'postal_code' => '123-4567',
      'address' => 'Test Address',
      'building' => 'Test Building',
    ])
      ->assertRedirect(route('purchase', ['item_id' => $item->id]))
      ->assertSessionHas('success', '住所が更新されました。');

    $this->assertDatabaseHas('users', [
      'id' => $user->id,
      'postal_code' => '123-4567',
      'address' => 'Test Address',
      'building' => 'Test Building',
    ]);
  }

  /** @test */
  public function it_handles_charge_successfully()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create(['price' => 5000]);

    $this->actingAs($user);

    // Stripeのモックを作成
    Stripe::setApiKey('sk_test_51PnN81KUcLKzkipSqUeSEdfsYzteisrRIDF7iF6jxmcP1T1F2LETGxKjX2YGXZxJLA6BDj2IGmPqqiAOKABWXld900m1nVUrHb');
    $chargeMock = Mockery::mock('overload:' . Charge::class);
    $chargeMock->shouldReceive('create')->andReturn((object)['id' => 'charge_id']);

    $response = $this->postJson(route('charge'), [
      'amount' => 5000 * 100, // Stripeは金額を最小通貨単位で受け取る
      'payment_method' => 'クレジットカード',
      'token' => 'tok_test',
      'item_id' => $item->id,
    ]);

    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('orders', [
      'item_id' => $item->id,
      'user_id' => $user->id,
      'total_price' => 5000,
    ]);
  }

  /** @test */
  public function it_redirects_to_login_if_not_authenticated_when_buying()
  {
    $item = Item::factory()->create();

    $response = $this->post(route('buy', ['id' => $item->id]));

    $response->assertRedirect(route('login'));
  }

  /** @test */
  public function it_redirects_to_purchase_page_if_authenticated_when_buying()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('buy', ['id' => $item->id]));

    $response->assertRedirect(route('purchase', ['item_id' => $item->id]));
    dd($response->getContent());
  }

  protected function setUp(): void
  {
    parent::setUp();

    // 認証ユーザーを作成し、ログイン
    $user = User::factory()->create();
    $this->actingAs($user);
  }

}
