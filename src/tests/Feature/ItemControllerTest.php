<?php


namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_displays_all_items_when_no_search_query_is_provided()
  {
    Item::factory()->count(3)->create();

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertViewHas('items');
    $this->assertCount(3, $response->viewData('items'));
  }

  /** @test */
  public function it_displays_filtered_items_based_on_search_query()
  {
    Item::factory()->create(['title' => 'Unique Item']);
    Item::factory()->create(['title' => 'Another Item']);

    $response = $this->get(route('home', ['query' => 'Unique']));

    $response->assertStatus(200);
    $response->assertViewHas('items');
    $this->assertCount(1, $response->viewData('items'));
    $this->assertEquals('Unique Item', $response->viewData('items')->first()->title);
  }

  /** @test */
  public function it_displays_mylist_when_user_is_authenticated()
  {

    $user = User::factory()->create();
    $this->actingAs($user);
    $item = Item::factory()->create();
    $user->favorites()->attach($item->id);
    $response = $this->get(route('home', ['tab' => 'mylist']));

    $response->assertStatus(200);
    $response->assertViewHas('favorites');
    $this->assertCount(1, $response->viewData('favorites'));
    $this->assertEquals($item->id, $response->viewData('favorites')->first()->id);
  }

  /** @test */
  public function it_redirects_to_login_when_trying_to_access_mylist_while_not_authenticated()
  {
    $response = $this->get(route('home', ['tab' => 'mylist']));

    $response->assertRedirect(route('login'));
  }

}
