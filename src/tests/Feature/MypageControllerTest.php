<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;


class MypageControllerTest extends TestCase
{
  use RefreshDatabase;



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

    $soldItem = Item::factory()->create([
      'title' => 'Test Sold Item',
      'price' => 1000,
      'condition' => 'New',
      'description' => 'This is a test sold item description',
      'user_id' => $user->id,
    ]);

    $purchasedItem = Item::factory()->create([
      'title' => 'Test Purchased Item',
      'price' => 2000,
      'condition' => 'Used',
      'description' => 'This is a test purchased item description',
      'user_id' => $user->id,
    ]);


    $user->purchasedItems()->attach($purchasedItem->id, ['total_price' => $purchasedItem->price]);

    $response = $this->actingAs($user)->get(route('mypage', ['query' => 'Test']));

    $response->assertStatus(200);
    $response->assertSee('Test Sold Item');
    $response->assertSee('Test Purchased Item');
  }



}
