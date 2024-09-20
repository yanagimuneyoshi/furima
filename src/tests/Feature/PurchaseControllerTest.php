<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Item;
use App\Models\User;
use Mockery;

class PurchaseControllerTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();

    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    $mock = Mockery::mock('alias:\Stripe\StripeClient');
    $mock->shouldReceive('request')
      ->with('post', 'https://api.stripe.com/v1/tokens', Mockery::any())
      ->andReturn((object)[
        'id' => 'tok_test',
        'object' => 'token'
      ]);

    $mock->shouldReceive('request')
      ->with('post', 'https://api.stripe.com/v1/charges', Mockery::any())
      ->andReturn((object)[
        'id' => 'ch_test',
        'status' => 'succeeded'
      ]);
  }

  public function test_purchase_page_displays_correctly()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $response = $this->actingAs($user)->get(route('purchase', ['item_id' => $item->id]));

    $response->assertStatus(200);
    $response->assertSee($item->title);
    $response->assertSee('コンビニ払い');
  }

  public function test_credit_card_payment_processes_correctly()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $response = $this->actingAs($user)->post('/purchase/charge', [
      'item_id' => $item->id,
      'amount' => $item->price * 100,
      'payment_method' => 'クレジットカード',
      'token' => 'tok_test'
    ]);

    $response->assertStatus(200);
  }

  public function test_convenience_store_payment_processes_correctly()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $response = $this->actingAs($user)->post('/purchase/charge', [
      'item_id' => $item->id,
      'amount' => $item->price * 100,
      'payment_method' => 'コンビニ払い'
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('orders', [
      'item_id' => $item->id,
      'user_id' => $user->id,
      'total_price' => $item->price
    ]);
  }



  public function test_address_update_works_correctly()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $response = $this->actingAs($user)->post(route('address.update', ['item_id' => $item->id]), [
      'postal_code' => '123-4567',
      'address' => '東京都新宿区',
      'building' => 'ビル101'
    ]);

    $response->assertRedirect(route('purchase', ['item_id' => $item->id]));
    $this->assertDatabaseHas('users', [
      'id' => $user->id,
      'postal_code' => '123-4567',
      'address' => '東京都新宿区',
      'building' => 'ビル101'
    ]);
  }

  protected function tearDown(): void
  {
    Mockery::close();
    parent::tearDown();
  }
}
